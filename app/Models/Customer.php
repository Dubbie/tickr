<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasUuids, HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'email',
        'id',
        'uuid',
        'unique_link'
    ];

    protected $appends = ['profile_photo_url'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderByDesc('created_at');
    }

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
        $url = '//www.gravatar.com/avatar/' . $path;

        return Attribute::make(
            get: fn() => $url
        );
    }

    public static function generateUniqueLink()
    {
        return Str::random(32);
    }
}
