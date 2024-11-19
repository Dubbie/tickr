<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasUuids, HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'email',
        'id',
        'uuid'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderByDesc('created_at');
    }
}
