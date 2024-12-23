<?php

namespace App\Models;

use App\Traits\GeneratesTicketNumber;
use App\Traits\TracksStatusChanges;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory, HasUuids, GeneratesTicketNumber, TracksStatusChanges;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'subject',
        'description',
        'status',
        'priority',
        'ticket_number',
        'contact_email',
        'assigned_to',
        'time_to_first_reply'
    ];

    protected $with = ['customer', 'assignee', 'replies', 'statusChanges'];

    protected $appends = ['formatted_updated_at', 'time_ago', 'profile_photo_url', 'is_archived'];

    public const STATUSES = ['open', 'in_progress', 'resolved', 'closed'];
    public const CATEGORY_OPEN = ['open', 'in_progress'];
    public const CATEGORY_ARCHIVED = ['closed', 'resolved'];
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

    public function formattedUpdatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at->format('M d, H:i')
        );
    }

    public function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at->diffForHumans()
        );
    }

    public function profilePhotoUrl(): Attribute
    {
        $url = null;

        if (!$this->contact_email) {
            $url =  $this->replier->profile_photo_url;
        } else {
            $defaultType = 'identicon';
            $params = [
                'd' => htmlentities($defaultType)
            ];
            $address = strtolower(trim($this->contact_email));
            $hash = hash('sha256', $address);
            $query = http_build_query($params);
            $path = sprintf('%s?%s', $hash, $query);

            $url = '//www.gravatar.com/avatar/' . $path;
        }


        return Attribute::make(
            get: fn() => $url
        );
    }

    public function isArchived(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->status, self::CATEGORY_ARCHIVED)
        );
    }

    public function scopeForTab(Builder $query, string $tab)
    {
        if ($tab === 'open') {
            return $query->whereIn('status', self::CATEGORY_OPEN);
        } else {
            return $query->whereIn('status', self::CATEGORY_ARCHIVED);
        }
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!empty($search)) {
            return $query->where('subject', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
        }

        return $query;
    }

    public function scopeByPriority(Builder $query, array $priorities)
    {
        if (!empty($priorities)) {
            return $query->whereIn('priority', $priorities);
        }

        return $query;
    }

    public function scopeByStatus(Builder $query, array $statuses)
    {
        if (!empty($statuses)) {
            return $query->whereIn('status', $statuses);
        }

        return $query;
    }

    public function scopeDefaultSort(Builder $query)
    {
        return $query
            ->orderByRaw("
                CASE
                    WHEN status IN ('closed', 'resolved') THEN 2 -- Resolved tickets go last
                    ELSE 1 -- All other statuses
                END
            ")
            ->orderByRaw("
                CASE
                    WHEN priority = 'high' THEN 1
                    WHEN priority = 'medium' THEN 2
                    WHEN priority = 'low' THEN 3
                    ELSE 4 -- Default case for unexpected priorities
                END
            ")
            ->orderByDesc('created_at');
    }
}
