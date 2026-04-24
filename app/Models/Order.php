<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'company_id',
        'address_id',
        'contact_id',
        'is_official',
        'total_price',
        'status',
        'created_by',
    ];

    protected $casts = [
        'is_official' => 'boolean',
        'total_price' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }

    public function contact()
    {
        return $this->belongsTo(CustomerContact::class, 'contact_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales()
    {
        return $this->hasMany(OrderSale::class);
    }

    public function approvals()
    {
        return $this->hasMany(OrderApproval::class);
    }
}
