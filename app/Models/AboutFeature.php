<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutFeature extends Model
{
    protected $fillable = [
        'about_section_id',
        'order',
        'title',
        'description',
    ];

    public function section()
    {
        return $this->belongsTo(AboutSection::class);
    }
}
