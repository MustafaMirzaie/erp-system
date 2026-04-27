<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerRepository $repo;

    public function __construct(CustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        if ($request->has('search')) {
            $customers = $this->repo->searchByName($request->search);
        } else {
            $customers = $this->repo->getActiveCustomers();
        }

        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = $this->repo->getCustomerWithRelations($id);

        return response()->json($customer);
    }

    public function addresses($id)
    {
        $customer = $this->repo->findOrFail($id);

        $addresses = $customer->addresses()->with('contacts')->get();

        return response()->json($addresses);
    }
}
