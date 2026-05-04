<?php

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
        return Order::with([
            'customer',
            'company',
            'address',
            'contact',
            'items.product',
            'items.packagingType',
            'items.unit',
            'sales.user',
            'approvals.step',
            'freightType',
            'createdBy',
        ])->findOrFail($id);
    }

    public function getPendingOrders()
    {
        return $this->model
            ->with(['customer', 'company'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->get();
    }
}
