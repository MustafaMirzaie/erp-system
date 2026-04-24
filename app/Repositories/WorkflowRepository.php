<?php

namespace App\Repositories;

use App\Models\WorkflowStep;
use App\Models\OrderApproval;

class WorkflowRepository
{
    protected WorkflowStep $workflowStep;
    protected OrderApproval $orderApproval;

    public function __construct(WorkflowStep $workflowStep, OrderApproval $orderApproval)
    {
        $this->workflowStep = $workflowStep;
        $this->orderApproval = $orderApproval;
    }

    /**
     * دریافت همه مراحل workflow بر اساس ترتیب
     */
    public function getWorkflowSteps()
    {
        return $this->workflowStep
            ->with('role')
            ->orderBy('step_order')
            ->get();
    }

    /**
     * دریافت approvalهای یک سفارش
     */
    public function getOrderApprovals(int $orderId)
    {
        return $this->orderApproval
            ->with(['step', 'approvedBy'])
            ->where('order_id', $orderId)
            ->orderBy('id')
            ->get();
    }

    /**
     * بروزرسانی وضعیت یک approval
     */
    public function updateApprovalStatus(int $approvalId, string $status, ?int $userId = null, ?string $description = null)
    {
        $approval = $this->orderApproval->findOrFail($approvalId);

        $approval->status = $status;
        $approval->action_by = $userId;
        $approval->action_at = now();

        if ($description !== null) {
            $approval->description = $description;
        }

        $approval->save();

        return $approval;
    }

    /**
     * پیدا کردن approval خاص
     */
    public function findApproval(int $id)
    {
        return $this->orderApproval->findOrFail($id);
    }
}
