<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesTicketNumber
{
    protected static function bootGeneratesTicketNumber()
    {
        static::creating(function ($model) {
            $customerName = strtoupper(preg_replace('/[^A-Z]/', '', Str::ascii($model->customer->name)));
            $prefix = substr($customerName, 0, 4);

            $latestTicket = $model::where('ticket_number', 'like', "$prefix-%")
                ->orderBy('ticket_number', 'desc')
                ->first();

            $nextNumber = $latestTicket
                ? ((int) Str::afterLast($latestTicket->ticket_number, '-') + 1)
                : 1;

            $model->ticket_number = sprintf('%s-%04d', $prefix, $nextNumber);
        });
    }
}
