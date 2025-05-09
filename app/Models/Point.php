<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //
    protected $primaryKey = ['lat', 'lng'];
    public $incrementing = false;
    protected $fillable = ['lat', 'lng'];

    public function arts()
    {
        return $this->hasMany(Art::class, ['lat', 'lng']);
    }
}
