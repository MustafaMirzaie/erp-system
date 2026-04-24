<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    /**
     * دریافت محصول
     */
    public function getProductWithRelations($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * جستجو بر اساس نام محصول
     */
    public function search(string $keyword, int $limit = 20)
    {
        return $this->model
            ->where('name', 'LIKE', "%{$keyword}%")
            ->limit($limit)
            ->get();
    }

    /**
     * محصولات فعال
     */
    public function getActiveProducts()
    {
        return $this->model
            ->where('status', 'active')
            ->get();
    }
}
