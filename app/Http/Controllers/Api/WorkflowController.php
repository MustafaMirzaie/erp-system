<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    protected WorkflowService $service;

    public function __construct(WorkflowService $service)
    {
        $this->service = $service;
    }

    public function steps()
    {
        return response()->json($this->service->getSteps());
    }

    public function orderApprovals($orderId)
    {
        return response()->json($this->service->getOrderApprovals($orderId));
    }

    public function approve(Request $request, $approvalId)
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $approval = $this->service->approve($approvalId, $request->description);

        return response()->json($approval);
    }

    public function reject(Request $request, $approvalId)
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $approval = $this->service->reject($approvalId, $request->description);

        return response()->json($approval);
    }
}
