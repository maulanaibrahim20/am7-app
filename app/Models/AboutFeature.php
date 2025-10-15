<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_section_id',
        'order',
        'title',
        'description',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function aboutSection()
    {
        return $this->belongsTo(AboutSection::class);
    }

    public function getIconHtmlAttribute()
    {
        return '<i class="' . $this->icon . '"></i>';
    }
}
