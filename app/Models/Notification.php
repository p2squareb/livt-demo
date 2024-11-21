<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'message',
        'receive_id',
        'receive_nickname',
        'send_id',
        'send_nickname',
        'is_read',
        'read_at',
        'refer_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receive_id', 'id');
    }
}
