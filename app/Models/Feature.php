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

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getIconHtmlAttribute()
    {
        return '<i class="' . $this->icon . '"></i>';
    }

    public function hasLink()
    {
        return !empty($this->link_url) && !empty($this->link_text);
    }
}
