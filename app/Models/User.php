<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['profile_photo_url'];

    public function profilePhotoUrl(): Attribute
    {
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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to', 'id');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!empty($search)) {
            $query = $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        }

        return $query;
    }
}
