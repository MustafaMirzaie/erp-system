<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkflowStep;
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
        return response()->json($this->service->getSteps()->load('role'));
    }

    public function storeStep(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'step_order' => 'required|integer|min:1',
            'role_id'    => 'required|exists:roles,id',
        ]);

        $step = WorkflowStep::create([
            'name'       => $request->name,
            'step_order' => $request->step_order,
            'role_id'    => $request->role_id,
        ]);

        return response()->json($step->load('role'), 201);
    }

    public function orderApprovals($orderId)
    {
        return response()->json($this->service->getOrderApprovals($orderId));
    }

    public function approve(Request $request, $approvalId)
    {
        $request->validate(['description' => 'nullable|string|max:500']);
        return response()->json($this->service->approve($approvalId, $request->description));
    }

    public function reject(Request $request, $approvalId)
    {
        $request->validate(['description' => 'nullable|string|max:500']);
        return response()->json($this->service->reject($approvalId, $request->description));
    }
}
