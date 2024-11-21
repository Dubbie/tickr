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
            $reply = $ticket->replies()->create([
                'message' => $message,
                'email' => $email,
                'replier_type' => get_class($replier),
                'replier_id' => $replier->id ?? $replier->uuid
            ]);

            // If replies is a user not a customer, do specific automations
            if (!$email) {
                $ticket->status = Ticket::STATUSES['1'];
                $ticket->time_to_first_reply = $ticket->created_at->diffInMinutes($reply->created_at);
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

    public function getAverageTimeToFirstReply()
    {
        $averageMinutes = Ticket::whereNotNull('time_to_first_reply')->avg('time_to_first_reply');

        if (is_null($averageMinutes)) {
            return '00:00 min'; // No data yet
        }

        $minutes = floor($averageMinutes);
        $seconds = round(($averageMinutes - $minutes) * 60);

        return sprintf('%02d:%02d min', $minutes, $seconds);
    }
}
