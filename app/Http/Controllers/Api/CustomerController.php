<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable'
        ]);

        $customersQuery = Customer::query();

        if (isset($data['query'])) {
            $customersQuery = $customersQuery->where('name', 'like', '%' . $data['query'] . '%');
        }

        return $customersQuery->orderBy('name')->get();
    }

    public function verifyId(Request $request)
    {
        $data = $request->validate([
            'customerId' => 'required'
        ]);

        $customerExists = Customer::where('id', $data['customerId'])->exists();
        if (!$customerExists) {
            return response()->json([
                'message' => 'Not found',
                'success' => false,
            ]);
        }

        return response()->json([
            'message' => 'Verified',
            'success' => true,
        ]);
    }
}
