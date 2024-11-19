<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        return Inertia::render('Ticket/Index');
    }

    public function show(string $ticketNumber)
    {
        return Inertia::render('Ticket/Show', [
            'ticketNumber' => $ticketNumber,
        ]);
    }
}
