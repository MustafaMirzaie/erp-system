<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_number',
        'customer_id',
        'company_id',
        'address_id',
        'contact_id',
        'is_official',
        'issue_date',
        'send_date',
        'freight_type_id',
        'freight_amount',
        'payment_terms',
        'notes',
        'total_price',
        'status',
        'created_by',
        'created_at',
        'insurance_amount',
        'payment_type',
    ];

    protected $casts = [
        'is_official'  => 'boolean',
        'issue_date'   => 'date',
        'send_date'    => 'date',
        'created_at'   => 'datetime',
    ];

    public function customer()     { return $this->belongsTo(Customer::class); }
    public function company()      { return $this->belongsTo(Company::class); }
    public function address()      { return $this->belongsTo(CustomerAddress::class); }
    public function contact()      { return $this->belongsTo(CustomerContact::class); }
    public function createdBy()    { return $this->belongsTo(User::class, 'created_by'); }
    public function freightType()  { return $this->belongsTo(FreightType::class); }
    public function items()        { return $this->hasMany(OrderItem::class); }
    public function sales()        { return $this->hasMany(OrderSale::class); }
    public function approvals()    { return $this->hasMany(OrderApproval::class); }
}
