<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'username',
        'password',
        'role_id',
        'status',
        'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function role()         { return $this->belongsTo(Role::class); }
    public function createdOrders(){ return $this->hasMany(Order::class, 'created_by'); }
    public function sales()        { return $this->hasMany(OrderSale::class); }
    public function approvals()    { return $this->hasMany(OrderApproval::class, 'action_by'); }
}
