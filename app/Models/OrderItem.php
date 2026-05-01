<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'packaging_type_id',
        'unit_id',
        'quantity',
        'amount',
        'base_price',
        'discount_percent',
        'commission_percent',
        'jump_percent',
        'final_price',
        'tax_percent',
    ];

    public function product()       { return $this->belongsTo(Product::class); }
    public function packagingType() { return $this->belongsTo(PackagingType::class); }
    public function unit()          { return $this->belongsTo(Unit::class); }
    public function order()         { return $this->belongsTo(Order::class); }
}
