<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Log;

class TicketService
{
    public function save(Customer $customer, array $ticketData)
    {
        // Create ticket add it to customer
        try {
            $customer->tickets()->create($ticketData);

            return response()->json([
                'message' => 'Ticket saved.',
                'success' => true,
            ], 201);
        } catch (Exception $e) {
            Log::error('Error while saving ticket.');

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while saving ticket.',
                'success' => false,
            ], 500);
        }
    }

    public function reply(Ticket $ticket, mixed $replier, string $message)
    {
        try {
            $ticket->replies()->create([
                'message' => $message,
                'replier_type' => get_class($replier),
                'replier_id' => $replier->id ?? $replier->uuid
            ]);

            return response()->json([
                'message' => 'Ticket reply saved.',
                'success' => true,
            ], 201);
        } catch (Exception $e) {
            Log::error('Error while saving ticket reply.');

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while saving ticket reply.',
                'success' => false,
            ], 500);
        }
    }
}
