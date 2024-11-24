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
            'perPage' => 'nullable',
            'sortField' => 'nullable|string',
            'sortOrder' => 'nullable|string|in:asc,desc'
        ]);

        $perPage = null;
        if (isset($data['perPage'])) {
            $perPage = $data['perPage'];
        }

        $query = Customer::query();

        if (isset($data['query'])) {
            $query = $query->search($data['query']);
        }

        if (isset($data['sortField']) && isset($data['sortOrder'])) {
            $query = $query->orderBy($data['sortField'], $data['sortOrder']);
        } else {
            $query = $query->orderBy('name');
        }

        // Attach ticket counts
        $query = $query->withCount('tickets');

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
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
