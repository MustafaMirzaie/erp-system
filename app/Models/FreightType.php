<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightType extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
