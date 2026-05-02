<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return response()->json($this->repo->searchByName($request->search));
        }
        return response()->json($this->repo->getActiveCustomers());
    }

    public function show($id)
    {
        return response()->json($this->repo->getCustomerWithRelations($id));
    }

    public function addresses($id)
    {
        $customer = $this->repo->findOrFail($id);
        return response()->json($customer->addresses()->with('contacts')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string',
            'economic_code'=> 'nullable|string',
            'national_id'  => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
            'status'       => 'in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'name'          => $request->name,
                'economic_code' => $request->economic_code,
                'national_id'   => $request->national_id,
                'credit_limit'  => $request->credit_limit ?? 0,
                'status'        => $request->status ?? 'active',
            ]);

            if ($request->address && $request->address['full_address']) {
                $address = CustomerAddress::create([
                    'customer_id'  => $customer->id,
                    'title'        => $request->address['title'] ?? 'پیش‌فرض',
                    'province'     => $request->address['province'] ?? null,
                    'city'         => $request->address['city'] ?? null,
                    'full_address' => $request->address['full_address'],
                    'is_default'   => true,
                ]);

                if ($request->contact && $request->contact['full_name']) {
                    CustomerContact::create([
                        'address_id' => $address->id,
                        'full_name'  => $request->contact['full_name'],
                        'phone'      => $request->contact['phone'] ?? null,
                        'mobile'     => $request->contact['mobile'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return response()->json($customer->load('addresses.contacts'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
