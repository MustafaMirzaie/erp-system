<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'economic_code',
        'national_id',
        'credit_limit',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
