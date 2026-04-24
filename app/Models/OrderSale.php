<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSale extends Model
{
    protected $table = 'order_sales';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'user_id',
        'share_percent',
    ];

    protected $casts = [
        'share_percent' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
