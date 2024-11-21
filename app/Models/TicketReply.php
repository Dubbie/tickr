<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'replier_type',
        'replier_id',
        'email',
    ];

    protected $with = ['replier'];

    protected $appends = ['time_ago', 'profile_photo_url'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_uuid', 'uuid');
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
        $url = null;

        if (!$this->email) {
            $url =  $this->replier->profile_photo_url;
        } else {
            $defaultType = 'identicon';
            $params = [
                'd' => htmlentities($defaultType)
            ];
            $address = strtolower(trim($this->email));
            $hash = hash('sha256', $address);
            $query = http_build_query($params);
            $path = sprintf('%s?%s', $hash, $query);

            $url = '//www.gravatar.com/avatar/' . $path;
        }


        return Attribute::make(
            get: fn() => $url
        );
    }
}
