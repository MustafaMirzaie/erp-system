<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderApproval;
use App\Models\OrderSale;
use App\Models\WorkflowStep;
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
                'customer_id'      => $data['customer_id'],
                'company_id'       => $data['company_id'],
                'address_id'       => $data['address_id'],
                'contact_id'       => $data['contact_id'],
                'is_official'      => $data['is_official'] ?? false,
                'status'           => 'pending',
                'created_by'       => auth()->id(),
                'order_number'     => $data['order_number'] ?? null,
                'send_date'        => $data['send_date'] ?? null,
                'freight_type_id'  => $data['freight_type_id'] ?? null,
                'freight_amount'   => $data['freight_amount'] ?? 0,
                'insurance_amount' => $data['insurance_amount'] ?? 0,
                'payment_terms'    => $data['payment_terms'] ?? null,
                'notes'            => $data['notes'] ?? null,
            ]);

            // آیتم‌ها
            foreach ($data['items'] as $item) {
                $order->items()->create([
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'base_price'  => $item['price'],
                    'final_price' => $item['price'] * $item['quantity'],
                ]);
            }

            $order->total_price = $order->items()->sum('final_price');
            $order->save();

            // کارشناسان فروش
            if (!empty($data['sales'])) {
                foreach ($data['sales'] as $sale) {
                    OrderSale::create([
                        'order_id'      => $order->id,
                        'user_id'       => $sale['user_id'],
                        'share_percent' => $sale['share_percent'],
                    ]);
                }
            }

            // ساخت خودکار approval برای هر مرحله workflow
            $steps = WorkflowStep::orderBy('step_order')->get();
            foreach ($steps as $step) {
                OrderApproval::create([
                    'order_id' => $order->id,
                    'step_id'  => $step->id,
                    'status'   => 'pending',
                ]);
            }

            DB::commit();
            return $order->load(['items', 'sales.user']);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getPendingOrders()
    {
        return $this->orders->getPendingOrders();
    }
}
