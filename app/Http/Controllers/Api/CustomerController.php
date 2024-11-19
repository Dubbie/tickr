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
}
