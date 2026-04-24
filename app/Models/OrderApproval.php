<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\WorkflowStep;
use App\Models\User;

class OrderApproval extends Model
{
    protected $table = 'order_approvals';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'step_id',
        'status',
        'action_by',
        'action_at',
        'description',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class, 'step_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
