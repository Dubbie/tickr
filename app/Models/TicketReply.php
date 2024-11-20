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

    protected $appends = ['time_ago', 'profile_photo_url'];

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

    public function profilePhotoUrl(): Attribute
    {
        if (!$this->email) {
            return $this->replier->profile_photo_url;
        }

        $defaultType = 'identicon';
        $params = [
            'd' => htmlentities($defaultType)
        ];
        $address = strtolower(trim($this->email));
        $hash = hash('sha256', $address);
        $query = http_build_query($params);
        $path = sprintf('%s?%s', $hash, $query);

        return Attribute::make(
            get: fn() => '//www.gravatar.com/avatar/' . $path
        );
    }
}
