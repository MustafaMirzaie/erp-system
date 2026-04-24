<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $table = 'customer_contacts';

    public $timestamps = false;

    protected $fillable = [
        'address_id',
        'full_name',
        'phone',
        'mobile',
    ];

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }
}
