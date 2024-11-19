<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
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

    public function reply(Ticket $ticket, mixed $replier, string $email, string $message)
    {
        try {
            $ticket->replies()->create([
                'message' => $message,
                'email' => $email,
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

    public function assignTicketToUser(Ticket $ticket, User $user)
    {
        try {
            $ticket->assigned_to = $user->id;
            $ticket->save();

            return response()->json([
                'message' => 'Ticket assigned to user.',
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error while assigning ticket to user.');

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while assigning ticket to user.',
                'success' => false,
            ], 500);
        }
    }
}
