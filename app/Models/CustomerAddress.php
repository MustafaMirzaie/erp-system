<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'title',
        'province',
        'city',
        'full_address',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class, 'address_id');
    }
}
