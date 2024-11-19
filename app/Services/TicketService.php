<?php

namespace App\Services;

use App\Models\Customer;
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
}
