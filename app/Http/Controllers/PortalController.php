<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PortalController extends Controller
{
    public function index()
    {
        return Inertia::render('Portal/Index');
    }
}
