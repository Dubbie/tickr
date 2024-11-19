<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'replier_type',
        'replier_id',
        'email'
    ];

    protected $with = ['replier'];

    protected $appends = ['time_ago'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function replier()
    {
        return $this->morphTo();
    }

    public function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans()
        );
    }
}
