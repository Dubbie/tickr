<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'old_status',
        'new_status',
        'changed_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
