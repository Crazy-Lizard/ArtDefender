<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportArt extends Model
{
    //
    protected $table = 'report_arts';
    
    protected $fillable = [
        'user_id',
        'art_id',
        'reason',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function art()
    {
        return $this->belongsTo(Art::class)->withTrashed();
    }
}
