<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumComment extends Model
{
    protected $fillable = ['forum_post_id', 'user_id', 'content', 'parent_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'forum_post_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Recursively gather all descendant replies in a flat collection, sorted by date.
     */
    public function getAllRepliesAttribute()
    {
        $replies = collect();
        $gather = function ($comment) use (&$gather, &$replies) {
            foreach ($comment->replies as $reply) {
                $replies->push($reply);
                $gather($reply);
            }
        };
        $gather($this);
        return $replies->sortBy('created_at');
    }
}
