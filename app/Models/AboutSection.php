<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

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

    protected $casts = [
        'experience_years' => 'integer',
    ];

    public function features()
    {
        return $this->hasMany(AboutFeature::class)->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function hasButton()
    {
        return !empty($this->button_url) && !empty($this->button_text);
    }
}
