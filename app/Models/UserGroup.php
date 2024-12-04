<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    protected $fillable = [
        'name',
        'level',
        'comment',
        'accessible_menus',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_level', 'level');
    }
}
