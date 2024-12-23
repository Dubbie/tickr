<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Index');
    }

    public function show(string $customer)
    {
        return Inertia::render('Customer/Show', ['customerUuid' => $customer]);
    }
}
