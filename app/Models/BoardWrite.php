<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class BoardWrite extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories',
        'is_notice',
        'is_delete',
        'subject',
        'content',
        'password',
        'writer',
    ];

    protected $hidden = [];

    protected $guarded = [
        'tid',
        'table_id',
        'hit',
        'rate_up',
        'rate_down',
        'user_id',
        'ip',
        'comment_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id', 'id');
    }

    public function bookmark(): HasOne
    {
        return $this->hasOne(BoardBookmark::class, 'target_id', 'id');
    }

    public function report(): HasMany
    {
        return $this->hasMany(BoardReport::class, 'target_id', 'id')->where('target_table', 'board_writes');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if ($filters['category'] ?? false) {
            $query->where('categories', $filters['category']);
        }

        if ($filters['searchString'] ?? false) {
            $query->where(function($q) use ($filters) {
                $q->where('subject', 'like', '%'.$filters['searchString'].'%')
                ->orWhere('content', 'like', '%'.$filters['searchString'].'%')
                ->orWhere('writer', 'like', '%'.$filters['searchString'].'%');
            });
        }

        if ($filters['searchTag'] ?? false) {
            $query->where('tags', 'like', '%'.$filters['searchTag'].'%');
        }

        if ($filters['sortBy'] ?? false) {
            switch ($filters['sortBy']) {
                case 'new':
                    $query->orderBy('id', 'desc');
                    break;
                case 'hot':
                    $query->where('board_writes.created_at', '>=', now()->subDays(3))->orderByRaw('(board_writes.hit + (board_writes.comment_count*5) + (board_writes.rate_up*10)) desc');
                    break;
                default:
                    $query->orderBy('id', 'desc');
                    break;
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        if ($filters['userId'] ?? false) {
            if (Auth::user()->group_level > 11) {
                $query->where('user_id', $filters['userId']);
            }
        }

        return $query;
    }
}
