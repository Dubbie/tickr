<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'subject',
        'description',
        'status',
        'priority',
        'ticket_number',
        'contact_email',
        'assigned_to'
    ];

    protected $with = ['customer', 'assignee', 'replies'];

    protected $appends = ['formatted_created_at', 'time_ago'];

    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];
    public const PRIORITIES = ['low', 'medium', 'high'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->format('Y.m.d H:i')
        );
    }

    public function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at->diffForHumans()
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Normalize customer name
            $customerName = strtoupper(preg_replace('/[^A-Z]/', '', Str::ascii($ticket->customer->name)));
            $prefix = substr($customerName, 0, 4);

            // Find the latest ticket number for this customer
            $latestTicket = static::where('ticket_number', 'like', "$prefix-%")
                ->orderBy('ticket_number', 'desc')
                ->first();

            // Extract and increment the number
            $nextNumber = $latestTicket
                ? ((int) Str::afterLast($latestTicket->ticket_number, '-') + 1)
                : 1;

            // Format the ticket number
            $ticket->ticket_number = sprintf('%s-%04d', $prefix, $nextNumber);
        });
    }
}
