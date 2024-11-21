<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'max:16', 'regex:/[A-Z]/', 'regex:/[0-9]/', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => '현재 비밀번호를 입력해 주세요.',
            'current_password.current_password' => '현재 비밀번호가 일치하지 않습니다.',
            'password.required' => '새로운 비밀번호를 입력해 주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 16자 이하이어야 합니다.',
            'password.regex' => '비밀번호는 대문자와 숫자를 포함해야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
