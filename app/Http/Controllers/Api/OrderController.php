<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $orders = auth()->user()->createdOrders()
            ->with(['customer', 'company', 'items'])
            ->latest('created_at')
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'          => 'required|exists:customers,id',
            'company_id'           => 'required|exists:companies,id',
            'address_id'           => 'required|exists:customer_addresses,id',
            'contact_id'           => 'required|exists:customer_contacts,id',
            'is_official'          => 'boolean',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric|min:0',
            'sales'                => 'nullable|array|max:3',
            'sales.*.user_id'      => 'required|exists:users,id',
            'sales.*.share_percent'=> 'required|numeric|min:1|max:100',
        ]);

        // بررسی جمع درصدها
        if (!empty($data['sales'])) {
            $total = array_sum(array_column($data['sales'], 'share_percent'));
            if ($total > 100) {
                return response()->json([
                    'message' => 'جمع درصد مشارکت کارشناسان نمی‌تواند بیشتر از 100 باشد',
                ], 422);
            }
        }

        $order = $this->service->createOrder($data);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = $this->service->getOrder($id);
        return response()->json($order);
    }
}
