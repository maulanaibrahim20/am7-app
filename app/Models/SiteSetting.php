<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'address',
        'open_hours',
        'phone',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
    ];
}
