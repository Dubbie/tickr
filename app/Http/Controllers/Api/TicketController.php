<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewTicketRequest;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use App\Services\TicketStatisticsService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    protected TicketStatisticsService $ticketStatisticsService;
    protected TicketService $ticketService;


    public function __construct(TicketService $ticketService, TicketStatisticsService $ticketStatisticsService)
    {
        $this->ticketService = $ticketService;
        $this->ticketStatisticsService = $ticketStatisticsService;
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

    public function counts(Request $request)
    {
        $data = $request->validate([
            'query' => 'nullable|string',
        ]);

        $baseQuery = Ticket::query();

        if (!empty($data['query'])) {
            $baseQuery = $baseQuery->search($data['query']);
        }

        // Clone the base query for each count to avoid conflicts
        return [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $baseQuery)->forTab('open')->count(),
            'archived' => (clone $baseQuery)->forTab('archived')->count(),
            'ttfr' => $this->ticketService->getAverageTimeToFirstReply(),
        ];
    }

    public function averages(Request $request)
    {
        $range = $request->input('range', 'this_week');

        $statistics = $this->ticketStatisticsService->getAverages($range);

        return response()->json($statistics);
    }

    public function ttfrStats()
    {
        $categories = $this->ticketStatisticsService->getTimeToFirstReplyDistribution();

        return response()->json($categories);
    }

    public function show(string $ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();
        $status = 404;
        $response = [
            'success' => false,
            'message' => 'Not found'
        ];

        if ($ticket) {
            $response['success'] = true;
            $response['ticket'] = $ticket;
            $response['message'] = 'Ticket found';

            $status = 200;
        }

        return response()->json($response, $status);
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

    public function close($ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

        return $this->ticketService->close($ticket);
    }

    public function store(NewTicketRequest $newTicketRequest)
    {
        $data = $newTicketRequest->validated();

        $customer = Customer::find($data['customer_uuid']);

        return $this->ticketService->save($customer, $data, true);
    }
}
