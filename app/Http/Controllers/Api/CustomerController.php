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
}
