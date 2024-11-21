<?php

namespace App\Http\Middleware;

use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $configNames = [
            'basic', 'point',
        ];

        foreach($configNames as $configName) {
            $this->cacheConfig($configName);
        }

        return array_merge(parent::share($request), [
            // Synchronously...
            'appName' => cache('config.basic')->basic->site_name,
            'notifications_count' => $this->getNotificationsCount(),

            // Lazily...
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'nickname', 'email', 'group_level', 'status', 'profile_photo_path', 'point')
                : null,
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
                'message' => fn () => $request->session()->get('message'), 
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }

    private function cacheConfig($configName): void
    {
        if(!Cache::has("config.$configName")) {
            Cache::forever("config.$configName", $this->getConfig($configName));
        }
    }

    private function getConfig($configName)
    {
        $config = System::query()->where('title', $configName)->orderByDesc('id')->first();
        return json_decode($config->content);
    }

    private function getNotificationsCount()
    {
        $notificationsCount = 0;
        if (Auth::check()){
            if (Auth::user()->group_level > 10) {
                $notificationsCount = Inquiry::query()->where('status', 0)->count();
            }
        } 
        return $notificationsCount;
    }
}
