<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $table = 'workflow_steps';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'step_order',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function approvals()
    {
        return $this->hasMany(OrderApproval::class, 'step_id');
    }
}
