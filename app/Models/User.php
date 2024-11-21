<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'provider',
        'provider_id', 
        'provider_token', 
        'last_login_at',
        'login_ip',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class, 'group_level', 'level');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BoardComment::class, 'user_id', 'id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if ($filters['status'] ?? false) {
            $query->where('status', $filters['status']);
        }

        if ($filters['group'] ?? false) {
            $query->where('group_level', $filters['group']);
        }

        if ($filters['searchString'] ?? false) {
            $query->where('nickname', 'like', '%'. $filters['searchString']. '%')->orWhere('email', 'like', '%'. $filters['searchString']. '%');
        }

        return $query;
    }
}
