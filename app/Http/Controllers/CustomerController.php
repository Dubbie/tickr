<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('Customer/Index');
    }
}
