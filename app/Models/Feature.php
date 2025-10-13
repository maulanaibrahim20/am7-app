<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'description',
        'link_text',
        'link_url',
        'order',
        'background_style',
        'is_active',
    ];
}
