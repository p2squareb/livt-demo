<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
class Inquiry extends Model
{
    protected $fillable = [
        'category',
        'subject',
        'content',
        'user_id',
        'ip',
        'answer_content',
        'answer_user_id',
        'answer_at',
    ];

    protected $guarded = [
        'hit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answer_user_id', 'id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if ($filters['category'] ?? false) {
            $query->where('category', $filters['category']);
        }

        if ($filters['is_answer'] ?? false) {
            if ($filters['is_answer'] === 'on') {
                $query->where('status', true);
            }elseif ($filters['is_answer'] === 'off') {
                $query->where('status', false);
            }
        }

        if ($filters['searchString'] ?? false) {
            $query->where(function($q) use ($filters) {
                $q->where('subject', 'like', '%'.$filters['searchString'].'%')
                    ->orWhere('content', 'like', '%'.$filters['searchString'].'%')
                    ->orWhereHas('user', function($query) use ($filters) {
                        $query->where('nickname', 'like', '%'.$filters['searchString'].'%');
                    });
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
