<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    //
    use SoftDeletes;

    protected $with = ['user'];
    protected $appends = ['created_at_diff'];

    protected $fillable = [
        'body',
        'parent_id',
        'user_id',
        'art_id'
    ];

    // Родительский комментарий
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Дочерние комментарии
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }

    // Автор комментария
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Статья, к которой относится комментарий
    public function art(): BelongsTo
    {
        return $this->belongsTo(Art::class); // Замените Art на вашу модель
    }

    public function getCreatedAtDiffAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    protected static function booted()
    {
        static::deleting(function ($comment) {
            if ($comment->isForceDeleting()) {
                $comment->replies()->forceDelete();
            } else {
                $comment->replies()->delete();
            }
        });
        
        static::restoring(function ($comment) {
            $comment->replies()->withTrashed()->restore();
        });
    }
}
