<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    private PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Display the registration view.
     */
    public function create() 
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nickname' => ['required', 'string', 'min:2', 'max:8'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'max:16', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed', Password::defaults()],
            'terms' => 'accepted',
            'privacy' => 'accepted',
        ], [
            'nickname.required' => '닉네임을 입력해 주세요.',
            'nickname.min' => '닉네임은 최소 2자 이상이어야 합니다.',
            'nickname.max' => '닉네임은 최대 8자까지 가능합니다.',
            'email.required' => '이메일을 입력해 주세요.',
            'email.email' => '유효한 이메일 형식을 입력해 주세요.',
            'email.lowercase' => '이메일은 소문자로 입력해야 합니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'password.required' => '비밀번호를 입력해 주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 16자 이하이어야 합니다.',
            'password.regex' => '비밀번호는 대문자와 숫자를 포함해야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'terms.accepted' => '서비스 이용약관에 동의해야 합니다.',
            'privacy.accepted' => '개인정보 처리방침에 동의해야 합니다.',
        ]);

        $user = User::create([
            'name' => $request->nickname,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'point' => 1000,
            'provider' => 'email',
            'last_login_at' => now(),
            'login_ip' => request()->ip(),
        ]);

        $this->pointService->savePlusPoint('users', $user->id, 'join', $user->id, $user->id);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
