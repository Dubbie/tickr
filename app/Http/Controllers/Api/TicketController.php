<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected TicketService $ticketService;


    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable|string',
            'page' => 'required|integer',
            'perPage' => 'required|integer',
            'tab' => 'nullable|string',
        ]);

        $tickets = Ticket::query()
            ->when($data['query'], fn($query, $search) => $query->search($search))
            ->when($data['tab'], fn($query, $tab) => $query->forTab($tab))
            ->defaultSort()
            ->paginate($data['perPage']);

        return response()->json($tickets);
    }

    public function show(string $ticketNumber)
    {
        return response()->json(Ticket::where('ticket_number', $ticketNumber)->first() ?? null);
    }

    public function reply(Request $request, string $ticketNumber)
    {
        $data = $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

        return $this->ticketService->reply($ticket, $request->user(), $data['message']);
    }

    public function replies(string $ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

        return response()->json([
            'message' => 'Ticket replies loaded.',
            'success' => true,
            'replies' => $ticket->replies
        ]);
    }

    public function assignToUser(Request $request, string $ticketNumber)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($data['user_id']);
        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

        return $this->ticketService->assignTicketToUser($ticket, $user);
    }
}
