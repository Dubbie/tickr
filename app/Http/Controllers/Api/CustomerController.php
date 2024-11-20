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

        $perPage = 10;
        if (isset($data['perPage'])) {
            $perPage = $data['perPage'];
        }

        $customersQuery = Customer::query();

        if (isset($data['query'])) {
            $customersQuery = $customersQuery->where('name', 'like', '%' . $data['query'] . '%');
        }

        return $customersQuery->orderBy('name')->paginate($perPage);
    }
}
