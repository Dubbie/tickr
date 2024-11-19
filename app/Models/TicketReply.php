<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'replier_type',
        'replier_id'
    ];

    protected $with = ['replier'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function replier()
    {
        return $this->morphTo();
    }
}
