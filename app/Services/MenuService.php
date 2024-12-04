<?php

namespace App\Services;

use App\Models\UserGroup;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuService
{
    private const CACHE_KEY = 'menu_permissions';
    private const CACHE_TTL = 3600;

    public function getMenuPermissions(int $groupLevel): string
    {
        return Cache::remember(
            $this->getCacheKey($groupLevel),
            self::CACHE_TTL,
            fn() => $this->fetchMenuPermissions($groupLevel)
        );
    }

    public function hasAccess(int $groupLevel, string $menuId): bool
    {
        $permissions = $this->getMenuPermissions($groupLevel);

        if (empty($permissions)) {
            return false;
        }

        $permissionArray = explode(',', $permissions);

        return in_array($menuId, $permissionArray);
    }

    private function fetchMenuPermissions(int $groupLevel): string
    {
        $group = UserGroup::select('accessible_menus')->where('level', $groupLevel)->first();
        
        if (!$group || empty($group->accessible_menus)) {
            return '';
        }

        return $group->accessible_menus;
    }

    private function getCacheKey(int $groupLevel): string
    {
        return self::CACHE_KEY . ':' . $groupLevel;
    }

    public function clearCache(int $groupLevel): void
    {
        Cache::forget($this->getCacheKey($groupLevel));
    }
} 