<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
class BoardComment extends Model
{
    protected $fillable = [
        'write_id',
        'comment',
        'password',
        'writer',
        'parent_id',
        'depth',
        'user_id',
        'ip',
    ];

    protected $hidden = [];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function write(): BelongsTo
    {
        return $this->belongsTo(BoardWrite::class, 'write_id', 'id');
    }

    public function report(): HasMany
    {
        return $this->hasMany(BoardReport::class, 'target_id', 'id')->where('target_table', 'board_comments');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if ($filters['searchString'] ?? false) {
            $query->where(function($q) use ($filters) {
                $q->where('comment', 'like', '%'.$filters['searchString'].'%');
            });
        }

        if ($filters['userId'] ?? false) {
            if (Auth::user()->group_level > 11) {
                $query->where('user_id', $filters['userId']);
            }
        }

        return $query;
    }
}
