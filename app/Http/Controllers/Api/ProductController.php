<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            $products = $this->repo->search($request->search);
        } else {
            $products = $this->repo->getActiveProducts();
        }

        return response()->json($products);
    }

    public function show($id)
    {
        $product = $this->repo->findOrFail($id);
        return response()->json($product);
    }
}
