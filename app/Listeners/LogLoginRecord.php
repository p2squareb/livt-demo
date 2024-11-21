<?php

namespace App\Listeners;

use App\Models\LoginRecord;
use App\Services\PointService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;

class LogLoginRecord
{
    private PointService $pointService;
    /**
     * Create the event listener.
     */
    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (!empty($user->id)) {

            $loginData = LoginRecord::where('user_id', $user->id)->whereDate('login_at', now())->exists();
            if (!$loginData) {
                $this->pointService->savePlusPoint('users', $user->id, 'login', $user->id, $user->id);
            }

            LoginRecord::create([
                'user_id' => $user->id,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'login_at' => now(),
                'provider' => $user->provider,
            ]);
            
        }
    }
}
