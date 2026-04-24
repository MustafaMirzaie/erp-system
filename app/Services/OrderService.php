<?php
/*
 * این نسخه پایه را الآن بساز تا بعداً روی آن منطق کامل Order Workflow را اضافه کنیم.

 *  این سرویس چه کارهایی را انجام می‌دهد؟
ساخت سفارش (Order)
اضافه کردن آیتم‌ها
محاسبه قیمت نهایی
ساخت مراحل Workflow (Approval Steps)
انجام همه عملیات داخل یک Transaction
دریافت سفارش با تمام روابط
*/

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

    /**
     * دریافت یک سفارش با تمام روابط
     */
    public function getOrder($id)
    {
        return $this->orders->getOrderWithRelations($id);
    }

    /**
     * ایجاد سفارش جدید
     */
    public function createOrder(array $data)
    {
        DB::beginTransaction();

        try {
            // 1. ساخت سفارش اصلی
            $order = $this->orders->create([
                'customer_id' => $data['customer_id'],
                'company_id' => $data['company_id'],
                'address_id' => $data['address_id'],
                'contact_id' => $data['contact_id'],
                'is_official' => $data['is_official'] ?? false,
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);

            // 2. افزودن آیتم‌های سفارش
            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'final_price' => $item['price'] * $item['quantity'],
                    ]);
                }
            }

            // 3. محاسبه قیمت نهایی سفارش
            $order->total_price = $order->items->sum('final_price');
            $order->save();

            // 4. ایجاد مراحل Workflow
            if ($order->customer && $order->customer->workflowSteps) {
                foreach ($order->customer->workflowSteps as $step) {
                    $order->approvals()->create([
                        'step_id' => $step->id,
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();
            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
