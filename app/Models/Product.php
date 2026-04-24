<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'base_price',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];
}
