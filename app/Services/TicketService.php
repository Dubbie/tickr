<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class TicketService
{
    public function save(Customer $customer, array $ticketData, bool $includeTicket = false)
    {
        // Create ticket add it to customer
        try {
            $ticket = $customer->tickets()->create($ticketData);

            $responseData = [
                'message' => 'Ticket saved.',
                'success' => true,
            ];

            if ($includeTicket) {
                $responseData['ticket'] = $ticket;
            }

            return response()->json($responseData, 201);
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

    public function reply(Ticket $ticket, mixed $replier, string $message, ?string $email = null)
    {
        try {
            $ticket->replies()->create([
                'message' => $message,
                'email' => $email,
                'replier_type' => get_class($replier),
                'replier_id' => $replier->id ?? $replier->uuid
            ]);

            // If replier is a user move ticket to In Progress status
            if (!$email) {
                $ticket->status = Ticket::STATUSES['1'];
            }

            $ticket->updated_at = now();
            $ticket->save();

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
            $ticket->updated_at = now();
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

    public function close(Ticket $ticket)
    {
        try {
            $ticket->status = Ticket::STATUSES['3'];
            $ticket->save();

            return response()->json([
                'message' => 'Ticket closed.',
                'success' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error while closing ticket.');

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error while closing ticket.',
                'success' => false,
            ], 500);
        }
    }
}
