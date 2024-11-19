<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PortalController extends Controller
{
    public function index()
    {
        return Inertia::render('Portal/Index');
    }


    public function create()
    {
        return Inertia::render('Portal/Create');
    }
}
