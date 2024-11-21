<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProhibit;
use App\Services\PointService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    private PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialLite = Socialite::driver($provider)->user();

        $user = User::where('provider', $provider)->where('provider_id', $socialLite->id)->first();
        if (!$user) {
            $checkUser = User::where('email', $socialLite->email)->exists();
            if ($checkUser) {
                return redirect()->route('login')->with('error', '이미 가입된 이메일입니다.');
            }

            $user = User::create([
                'name' => $provider === 'google' ? $socialLite->name : $socialLite->nickname, 
                'nickname' => $provider === 'google' ? $socialLite->name : $socialLite->nickname, 
                'email' => $socialLite->email, 
                'point' => 1000,
                'last_login_at' => now(),
                'login_ip' => request()->ip(),
                'provider' => $provider,
                'provider_id' => $socialLite->id,
                'provider_token' => $socialLite->token,
                'provider_refresh_token' => $socialLite->refreshToken,
            ]);
            User::where('provider', $provider)->where('provider_id', $socialLite->id)->update([
                'email_verified_at' => now(),
            ]);

            $this->pointService->savePlusPoint('users', $user->id, 'join', $user->id, $user->id);
            event(new Registered($user));
            Auth::login($user);
            return redirect(route('home', absolute: false));
        }else{
            if ($user->status === 2) {
                return redirect()->route('login')->with('error', '이용할 수 없는 계정입니다.');
            }elseif ($user->status === 3) {
                $prohibit = UserProhibit::where('user_id', $user->id)->where('gubun', 1)->orderBy('id', 'desc')->first();
                return redirect()->route('login')->with('error', '이용 수칙에 어긋나 계정이 정지되었습니다. 해지일 : ' . substr($prohibit->until_date, 0, 10));
            }

            User::where('provider', $provider)->where('provider_id', $socialLite->id)->update([
                'provider_token' => $socialLite->token,
                'provider_refresh_token' => $socialLite->refreshToken,
            ]);

            Auth::login($user);
            return redirect()->intended(route('home', absolute: false));
        }
    }
}
