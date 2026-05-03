<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderApproval;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    private function isAdmin($user): bool
    {
        return in_array(strtolower($user->role?->name ?? ''), ['مدیر سیستم', 'admin', 'administrator']);
    }

    public function myTasks(Request $request)
    {
        $user = $request->user()->load('role');

        if ($this->isAdmin($user)) {
            // admin همه pending ها رو می‌بینه
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->where('status', 'pending')
                ->whereHas('order', fn($q) => $q->where('status', 'pending'))
                ->get();
        } else {
            $myStepIds = WorkflowStep::where('role_id', $user->role_id)->pluck('id');
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->whereIn('step_id', $myStepIds)
                ->where('status', 'pending')
                ->whereHas('order', fn($q) => $q->where('status', 'pending'))
                ->get();
        }

        return response()->json($this->formatApprovals($approvals));
    }

    public function inProgress(Request $request)
    {
        $user = $request->user()->load('role');

        if ($this->isAdmin($user)) {
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->where('status', 'pending')
                ->whereHas('order', fn($q) => $q->where('status', 'pending'))
                ->get();
        } else {
            $myStepIds = WorkflowStep::where('role_id', $user->role_id)->pluck('id');
            $myOrderIds = OrderApproval::whereIn('step_id', $myStepIds)->pluck('order_id')->unique();
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->whereIn('order_id', $myOrderIds)
                ->where('status', 'pending')
                ->whereNotIn('step_id', $myStepIds)
                ->whereHas('order', fn($q) => $q->where('status', 'pending'))
                ->get();
        }

        return response()->json($this->formatApprovals($approvals));
    }

    public function completed(Request $request)
    {
        $user = $request->user()->load('role');

        if ($this->isAdmin($user)) {
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->whereIn('status', ['approved', 'rejected'])
                ->get();
        } else {
            $myStepIds = WorkflowStep::where('role_id', $user->role_id)->pluck('id');
            $approvals = OrderApproval::with(['order.customer', 'order.company', 'step', 'order.createdBy'])
                ->whereIn('step_id', $myStepIds)
                ->whereIn('status', ['approved', 'rejected'])
                ->get();
        }

        return response()->json($this->formatApprovals($approvals));
    }

    public function myInboxRoles(Request $request)
    {
        $user = $request->user()->load('role');

        if ($this->isAdmin($user)) {
            return response()->json([
                'role'      => $user->role?->name,
                'steps'     => WorkflowStep::orderBy('step_order')->get(),
                'has_inbox' => true,
                'is_admin'  => true,
            ]);
        }

        $steps = WorkflowStep::where('role_id', $user->role_id)->orderBy('step_order')->get();
        return response()->json([
            'role'      => $user->role?->name,
            'steps'     => $steps,
            'has_inbox' => $steps->count() > 0,
            'is_admin'  => false,
        ]);
    }

    private function formatApprovals($approvals)
    {
        return $approvals->map(fn($a) => [
            'approval_id'  => $a->id,
            'order_id'     => $a->order_id,
            'order_number' => $a->order?->order_number ?? '#' . $a->order_id,
            'customer'     => $a->order?->customer?->name,
            'company'      => $a->order?->company?->name,
            'created_by'   => $a->order?->createdBy?->full_name,
            'created_at'   => $a->order?->created_at,
            'step_name'    => $a->step?->name,
            'step_order'   => $a->step?->step_order,
            'status'       => $a->status,
            'total_price'  => $a->order?->total_price,
        ]);
    }
}
