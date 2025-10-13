<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = [
        'subtitle',
        'title',
        'description',
        'image',
        'experience_years',
        'experience_label',
        'button_text',
        'button_url',
    ];

    public function features()
    {
        return $this->hasMany(AboutFeature::class)->orderBy('order');
    }
}
