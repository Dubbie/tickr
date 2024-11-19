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
            'query' => 'nullable'
        ]);

        $query = Ticket::query();

        if (isset($data['query'])) {
            $query = $query->where('subject', 'like', '%' . $data['query'] . '%')
                ->orWhere('description', 'like', '%' . $data['query'] . '%');
        }

        return $query->orderByDesc('created_at')->get();
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

        return $this->ticketService->reply($ticket, $request->user(), $request->user()->email, $data['message']);
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
