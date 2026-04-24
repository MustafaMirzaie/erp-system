<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    /**
     * دریافت مشتری همراه با آدرس‌ها و مخاطبین آدرس‌ها
     */
    public function getCustomerWithRelations($id)
    {
        return $this->model->with([
            'addresses',
            'addresses.contacts',
            'orders',
        ])->findOrFail($id);
    }

    /**
     * جستجوی مشتری بر اساس نام
     */
    public function searchByName(string $keyword, int $limit = 20)
    {
        return $this->model
            ->where('name', 'LIKE', "%{$keyword}%")
            ->limit($limit)
            ->get();
    }

    /**
     * لیست مشتری‌های فعال
     */
    public function getActiveCustomers()
    {
        return $this->model
            ->where('status', 'active')
            ->get();
    }
}
