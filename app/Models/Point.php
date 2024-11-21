<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
class Point extends Model
{
    protected $fillable = [
        'point_type',
        'point_item',
        'to_user_id',
        'from_user_id',
        'reason',
        'amount',
        'target_name',
        'target_id',
        'processed_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if ($filters['pointType'] ?? false) {
            $query->where('point_type', $filters['pointType']);
        }

        if ($filters['pagePeriod'] ?? false) {
            if ($filters['pagePeriod'] === '7' || $filters['pagePeriod'] === '30') {
                $query->where('created_at', '>=', Carbon::now()->subDays($filters['pagePeriod']));
            }else if ($filters['pagePeriod'] === '3m') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(3));
            }else if ($filters['pagePeriod'] === '6m') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(6));
            }else if ($filters['pagePeriod'] === '1y') {
                $query->where('created_at', '>=', Carbon::now()->subYear());
            }
        }

        if ($filters['searchString'] ?? false) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('nickname', 'like', '%'. $filters['searchString']. '%')
                ->orWhere('email', 'like', '%'. $filters['searchString']. '%');
            });
        }

        if ($filters['userId'] ?? false) {
            if (Auth::user()->group_level > 11) {
                $query->where('to_user_id', $filters['userId']);
            }
        }

        return $query;
    }
}
