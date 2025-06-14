<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtImage extends Model
{
    //
    protected $fillable = ['art_id', 'image_path'];
    
    public function art()
    {
        return $this->belongsTo(Art::class);
    }
    
    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
