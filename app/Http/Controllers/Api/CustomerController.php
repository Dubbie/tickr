<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCustomerRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable',
            'perPage' => 'nullable'
        ]);

        $perPage = null;
        if (isset($data['perPage'])) {
            $perPage = $data['perPage'];
        }

        $customersQuery = Customer::query();

        if (isset($data['query'])) {
            $customersQuery = $customersQuery->where('name', 'like', '%' . $data['query'] . '%');
        }

        $customersQuery = $customersQuery->withCount('tickets')->orderBy('name');

        if ($perPage) {
            return $customersQuery->paginate($perPage);
        }

        return $customersQuery->get();
    }

    public function show(Customer $customer)
    {
        return $customer->load([
            'tickets' => function ($query) {
                $query->latest()->limit(5);
            }
        ]);
    }

    public function store(NewCustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['unique_link'] = Customer::generateUniqueLink();

            Customer::create($data);

            return response()->json([
                'message' => 'Customer created.',
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error while saving customer');
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while saving customer',
                'success' => false,
            ], 500);
        }
    }
}
