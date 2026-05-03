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
            'name'          => 'required|string',
            'economic_code' => 'nullable|string',
            'national_id'   => 'nullable|string',
            'credit_limit'  => 'nullable|numeric|min:0',
            'status'        => 'in:active,inactive',
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

    public function storeAddress(Request $request, $id)
    {
        $request->validate([
            'title'        => 'required|string',
            'province'     => 'required|string',
            'city'         => 'required|string',
            'full_address' => 'required|string',
            'is_default'   => 'boolean',
        ]);

        if ($request->is_default) {
            CustomerAddress::where('customer_id', $id)->update(['is_default' => false]);
        }

        $address = CustomerAddress::create([
            'customer_id'  => $id,
            'title'        => $request->title,
            'province'     => $request->province,
            'city'         => $request->city,
            'full_address' => $request->full_address,
            'is_default'   => $request->is_default ?? false,
            'is_active'    => true,
        ]);

        return response()->json($address, 201);
    }

    public function addressContacts($addressId)
    {
        $address = CustomerAddress::with('contacts')->findOrFail($addressId);
        return response()->json($address->contacts);
    }

    public function updateAddress(Request $request, $id)
    {
        $address = CustomerAddress::findOrFail($id);
        $address->update($request->only(['title', 'province', 'city', 'full_address', 'is_default']));
        return response()->json($address);
    }

    public function toggleAddress($id)
    {
        $address = CustomerAddress::findOrFail($id);
        $address->is_active = !($address->is_active ?? true);
        $address->save();
        return response()->json($address);
    }

    public function storeContact(Request $request, $addressId)
    {
        $request->validate(['full_name' => 'required|string']);
        $contact = CustomerContact::create([
            'address_id' => $addressId,
            'full_name'  => $request->full_name,
            'mobile'     => $request->mobile ?? null,
            'phone'      => $request->phone ?? null,
            'is_active'  => true,
        ]);
        return response()->json($contact, 201);
    }

    public function updateContact(Request $request, $id)
    {
        $contact = CustomerContact::findOrFail($id);
        $contact->update($request->only(['full_name', 'mobile', 'phone']));
        return response()->json($contact);
    }

    public function toggleContact($id)
    {
        $contact = CustomerContact::findOrFail($id);
        $contact->is_active = !($contact->is_active ?? true);
        $contact->save();
        return response()->json($contact);
    }
}
