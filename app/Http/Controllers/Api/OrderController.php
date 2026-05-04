<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
        $orders = Order::with(['customer', 'company', 'items'])
            ->latest('created_at')
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'           => 'required|exists:customers,id',
            'company_id'            => 'required|exists:companies,id',
            'address_id'            => 'required|exists:customer_addresses,id',
            'contact_id'            => 'required|exists:customer_contacts,id',
            'is_official'           => 'boolean',
            'order_number'          => 'nullable|string|max:50',
            'issue_date'            => 'nullable|date',
            'send_date'             => 'nullable|date',
            'freight_type_id'       => 'nullable|exists:freight_types,id',
            'freight_amount'        => 'nullable|numeric|min:0',
            'insurance_amount'      => 'nullable|numeric|min:0',
            'payment_type'          => 'required|in:cash,check,credit',
            'payment_terms'         => 'nullable|string',
            'notes'                 => 'nullable|string',
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.price'         => 'required|numeric|min:0',
            'items.*.amount'        => 'nullable|numeric|min:0',
            'items.*.packaging_type_id' => 'nullable|exists:packaging_types,id',
            'items.*.unit_id'       => 'nullable|exists:units,id',
            'sales'                 => 'nullable|array|max:3',
            'sales.*.user_id'       => 'required|exists:users,id',
            'sales.*.share_percent' => 'required|numeric|min:1|max:100',
        ]);

        if (!empty($data['sales'])) {
            $total = array_sum(array_column($data['sales'], 'share_percent'));
            if ($total > 100) {
                return response()->json([
                    'message' => 'جمع درصد مشارکت کارشناسان نمی‌تواند بیشتر از 100 باشد',
                ], 422);
            }
        }

        \Log::info('Order payload:', $data);
        $order = $this->service->createOrder($data);
        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::with([
            'customer', 'company',
            'address', 'contact',
            'freightType',
            'items.product',
            'items.packagingType',
            'items.unit',
            'sales.user',
            'approvals.step',
            'createdBy',
        ])->findOrFail($id);

        return response()->json($order);
    }

    public function nextNumber()
    {
        $last = Order::max('id') ?? 0;
        return response()->json(['number' => $last + 1]);
    }
}
