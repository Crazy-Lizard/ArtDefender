<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Art extends Model
{
    //
    protected $table = 'arts';
    
    protected $fillable = ['lat', 'lng', 'image_path', 'description', 'creator', 'user_id', 'art_status', 'request_status', 'art_type', 'art_created_year'];

    public function point() {
        return $this->belongsTo(Point::class, ['lat', 'lng']);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
