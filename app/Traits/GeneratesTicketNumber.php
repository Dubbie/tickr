<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait GeneratesTicketNumber
 *
 * This trait automatically generates a unique ticket number
 * for models when they are being created. The ticket number is
 * based on the customer's name and follows the format:
 * PREFIX-XXXX, where PREFIX is the first four characters of the
 * customer's name (letters only), and XXXX is a zero-padded number
 * incremented for each customer.
 *
 * Example: For a customer named "John Doe", the ticket numbers could
 * be "JOHN-0001", "JOHN-0002", etc.
 *
 * @package App\Traits
 */
trait GeneratesTicketNumber
{
    /**
     * Boot the GeneratesTicketNumber trait.
     *
     * Registers a model event handler for the `creating` event
     * to automatically generate the `ticket_number` attribute.
     *
     * @return void
     */
    protected static function bootGeneratesTicketNumber()
    {
        static::creating(function ($model) {
            // Generate a prefix based on the customer's name
            $customerName = strtoupper(preg_replace('/[^A-Z]/', '', Str::ascii($model->customer->name)));
            $prefix = substr($customerName, 0, 4);

            // Retrieve the latest ticket number for this customer
            $latestTicket = $model::where('ticket_number', 'like', "$prefix-%")
                ->orderBy('ticket_number', 'desc')
                ->first();

            // Determine the next ticket number
            $nextNumber = $latestTicket
                ? ((int) Str::afterLast($latestTicket->ticket_number, '-') + 1)
                : 1;

            // Set the ticket_number attribute on the model
            $model->ticket_number = sprintf('%s-%04d', $prefix, $nextNumber);
        });
    }
}
