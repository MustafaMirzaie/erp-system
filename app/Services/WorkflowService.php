<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\WorkflowRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class WorkflowService
{
    protected WorkflowRepository $workflow;

    public function __construct(WorkflowRepository $workflow)
    {
        $this->workflow = $workflow;
    }

    public function getSteps()
    {
        return $this->workflow->getWorkflowSteps();
    }

    public function getOrderApprovals(int $orderId)
    {
        return $this->workflow->getOrderApprovals($orderId);
    }

    public function approve(int $approvalId, ?string $description = null)
    {
        DB::beginTransaction();
        try {
            $approval = $this->workflow->updateApprovalStatus(
                $approvalId,
                'approved',
                auth()->id(),
                $description
            );

            // بررسی آیا همه مراحل تایید شدن
            $order = Order::findOrFail($approval->order_id);
            $allApprovals = $this->workflow->getOrderApprovals($order->id);
            $allApproved = $allApprovals->every(fn($a) => $a->status === 'approved');

            if ($allApproved) {
                $order->status = 'approved';
                $order->save();
            }

            DB::commit();
            return $approval;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reject(int $approvalId, ?string $description = null)
    {
        DB::beginTransaction();
        try {
            $approval = $this->workflow->updateApprovalStatus(
                $approvalId,
                'rejected',
                auth()->id(),
                $description
            );

            // وقتی رد می‌شه، سفارش هم rejected می‌شه
            $order = Order::findOrFail($approval->order_id);
            $order->status = 'rejected';
            $order->save();

            DB::commit();
            return $approval;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function initializeWorkflow(int $orderId)
    {
        $steps = $this->workflow->getWorkflowSteps();

        foreach ($steps as $step) {
            \App\Models\OrderApproval::create([
                'order_id' => $orderId,
                'step_id'  => $step->id,
                'status'   => 'pending',
            ]);
        }
    }
}
