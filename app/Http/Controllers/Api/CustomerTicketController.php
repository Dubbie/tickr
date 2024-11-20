<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewCustomerTicketRequest;
use App\Models\Customer;
use App\Services\TicketService;
use Illuminate\Http\Request;

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

    public function show(string $link, string $ticketNumber)
    {
        $customer = Customer::where('unique_link', $link)->first();
        $ticket = $customer->tickets()->where('ticket_number', $ticketNumber)->first();

        return response()->json($ticket ?? null);
    }


    public function store(NewCustomerTicketRequest $request)
    {
        $data = $request->validated();

        return $this->ticketService->save($request->get('customer'), $data);
    }

    public function reply(Request $request, string $link, string $ticketNumber)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string'
        ]);

        $customer = Customer::where('unique_link', $link)->first();
        $ticket = $customer->tickets()->where('ticket_number', $ticketNumber)->first();

        return $this->ticketService->reply($ticket, $customer, $data['message'], $data['email']);
    }
}
