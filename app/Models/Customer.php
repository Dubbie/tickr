<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasUuids, HasFactory;

    protected $primaryKey = 'uuid';

    protected $hidden = ['uuid'];

    protected $fillable = [
        'name',
        'email',
        'id',
        'uuid'
    ];
}
