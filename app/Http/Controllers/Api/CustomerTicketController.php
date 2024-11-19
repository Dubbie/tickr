<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewTicketRequest;
use App\Models\Customer;
use App\Services\TicketService;

class CustomerTicketController extends Controller
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(string $link)
    {
        $customer = Customer::where('unique_link', $link)->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Unauthorized access',
                'success' => false,
            ], 403);
        }

        return response()->json([
            'message' => 'Tickets fetched',
            'success' => true,
            'tickets' => $customer->tickets
        ]);
    }

    public function store(NewTicketRequest $request)
    {
        $data = $request->validated();

        return $this->ticketService->save($request->get('customer'), $data);
    }
}
