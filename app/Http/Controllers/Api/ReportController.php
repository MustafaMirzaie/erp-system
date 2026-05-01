<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summary()
    {
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $approvedOrders = Order::where('status', 'approved')->count();
        $rejectedOrders = Order::where('status', 'rejected')->count();
        $totalRevenue   = Order::where('status', 'approved')->sum('total_price');
        $totalCustomers = Customer::where('status', 'active')->count();
        $totalProducts  = Product::where('status', 'active')->count();
        $totalUsers     = User::where('status', 'active')->count();

        return response()->json([
            'orders' => [
                'total'    => $totalOrders,
                'pending'  => $pendingOrders,
                'approved' => $approvedOrders,
                'rejected' => $rejectedOrders,
            ],
            'revenue'   => $totalRevenue,
            'customers' => $totalCustomers,
            'products'  => $totalProducts,
            'users'     => $totalUsers,
        ]);
    }

    public function ordersByStatus()
    {
        $data = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn($item) => [$item->status => $item->count]);

        return response()->json([
            'draft'    => $data['draft']    ?? 0,
            'pending'  => $data['pending']  ?? 0,
            'approved' => $data['approved'] ?? 0,
            'rejected' => $data['rejected'] ?? 0,
            'revision' => $data['revision'] ?? 0,
        ]);
    }

    public function topCustomers()
    {
        $customers = Order::select('customer_id', DB::raw('count(*) as order_count'), DB::raw('sum(total_price) as total_revenue'))
            ->where('status', 'approved')
            ->with('customer:id,name')
            ->groupBy('customer_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get()
            ->map(fn($o) => [
                'name'          => $o->customer?->name ?? '-',
                'order_count'   => $o->order_count,
                'total_revenue' => $o->total_revenue,
            ]);

        return response()->json($customers);
    }

    public function topSalesUsers()
    {
        $users = DB::table('order_sales')
            ->join('users', 'order_sales.user_id', '=', 'users.id')
            ->join('orders', 'order_sales.order_id', '=', 'orders.id')
            ->where('orders.status', 'approved')
            ->select(
                'users.full_name',
                DB::raw('count(distinct order_sales.order_id) as order_count'),
                DB::raw('avg(order_sales.share_percent) as avg_share')
            )
            ->groupBy('users.id', 'users.full_name')
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();

        return response()->json($users);
    }
}
