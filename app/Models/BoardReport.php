<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardReport extends Model
{
    protected $fillable = [
        'target_table',
        'target_id',
        'user_id',
        'target_user_id',
    ];

    public function write(): BelongsTo
    {
        return $this->belongsTo(BoardWrite::class, 'target_id', 'id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(BoardComment::class, 'target_id', 'id');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
