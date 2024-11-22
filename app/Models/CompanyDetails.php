<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetails extends Model
{
    protected $fillable = [
        'name',
        'profile_photo_url'
    ];
}
