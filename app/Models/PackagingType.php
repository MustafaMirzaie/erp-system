<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagingType extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'is_active'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
