<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepository $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            return response()->json($this->repo->search($request->search));
        }
        return response()->json($this->repo->getActiveProducts());
    }

    public function show($id)
    {
        return response()->json($this->repo->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string',
            'base_price' => 'nullable|numeric|min:0',
            'status'     => 'in:active,inactive',
        ]);

        $product = Product::create([
            'name'       => $request->name,
            'base_price' => $request->base_price ?? 0,
            'status'     => $request->status ?? 'active',
        ]);

        return response()->json($product, 201);
    }
}
