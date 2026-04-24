<?php
/*
    * این ریپازیتوری از همین حالا آماده است برای:
    بارگذاری سفارش با همه روابط
    دریافت سفارش‌های در وضعیت Pending
    و هر Query حرفه‌ای که بعداً نیاز داریم
*/

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getOrderWithRelations($id)
    {
        return $this->model->with([
            'customer',
            'items',
            'approvals',
            'company',
            'address',
            'contact'
        ])->findOrFail($id);
    }

    public function getPendingOrders()
    {
        return $this->model->where('status', 'pending')->get();
    }
}
