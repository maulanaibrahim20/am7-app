<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $cast = [
        'is_active' => 'boolean',
    ];


    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_url',
        'background_image',
        'foreground_image',
        'order',
        'is_active',
    ];
}
