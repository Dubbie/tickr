<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Index', [
            'customers' => Customer::all()
        ]);
    }
}
