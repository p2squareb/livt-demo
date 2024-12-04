<?php

namespace App\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;

class MenuAccessCheck
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function handle(Request $request, Closure $next, $menuId)
    {
        $user = $request->user();
        
        if (!$this->menuService->hasAccess($user->group_level, $menuId)) {
            abort(403);
        }

        return $next($request);
    }
}
