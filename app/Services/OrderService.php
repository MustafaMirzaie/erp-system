<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected OrderRepository $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function getOrder($id)
    {
        return $this->orders->getOrderWithRelations($id);
    }

    public function createOrder(array $data)
    {
        DB::beginTransaction();

        try {
            $order = $this->orders->create([
                'customer_id' => $data['customer_id'],
                'company_id'  => $data['company_id'],
                'address_id'  => $data['address_id'],
                'contact_id'  => $data['contact_id'],
                'is_official' => $data['is_official'] ?? false,
                'status'      => 'pending',
                'created_by'  => auth()->id(),
            ]);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                        'base_price'  => $item['price'],        // ← اضافه شد
                        'final_price' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            $order->total_price = $order->items()->sum('final_price');
            $order->save();

            DB::commit();
            return $order->load('items');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
