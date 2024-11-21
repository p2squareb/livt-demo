<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Models\UserProhibit;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate($request): void
    {
        $this->ensureIsNotRateLimited();

        // Auth::attempt() 메소드가 성공하면 자동으로 세션에 사용자 정보가 저장됩니다.
        // 먼저 사용자 정보를 조회하여 상태를 체크합니다
        $credentials = $this->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user) {
            if ($user->status === 2) {
                throw ValidationException::withMessages([
                    'email' => '이용할 수 없는 계정입니다.',
                ]);
            }elseif ($user->status === 3) {
                $prohibit = UserProhibit::where('user_id', $user->id)->where('gubun', 1)->orderBy('id', 'desc')->first();
                throw ValidationException::withMessages([
                    'email' => '이용 수칙에 어긋나 계정이 정지되었습니다. 해지일 : ' . substr($prohibit->until_date, 0, 10),
                ]); 
            }elseif ($user->status === 4){
                $request->session()->put('login.url.intended', '/email/verify');
            }
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('일치하는 사용자 정보가 없습니다.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
