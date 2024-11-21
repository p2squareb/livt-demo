<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProhibit extends Model
{
    protected $fillable = [
        'user_id',
        'gubun',
        'period_type',
        'until_date',
        'cause',
        'processed_by_user_id',
        'processed_by_user_nickname',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
