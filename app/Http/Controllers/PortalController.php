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

    public function show(string $link, string $ticketNumber)
    {
        return Inertia::render('Portal/Show', [
            'ticketNumber' => $ticketNumber
        ]);
    }
}
