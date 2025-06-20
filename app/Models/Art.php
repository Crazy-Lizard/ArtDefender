<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Art extends Model
{
    //
    use SoftDeletes;
    
    protected $table = 'arts';

    protected $appends = ['image_url'];
    
    protected $fillable = ['lat', 'lng', 'image_path', 'description', 'creator', 'user_id', 'art_status', 'request_status', 'art_type', 'art_created_year'];

    public function point() {
        return $this->belongsTo(Point::class, ['lat', 'lng']);
    }

    public function additionalImages()
    {
        return $this->hasMany(ArtImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function reports()
    {
        return $this->hasMany(ReportArt::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
