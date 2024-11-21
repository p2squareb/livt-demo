<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => ['required', 'string', 'min:2', 'max:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'nickname.required' => '닉네임을 입력해 주세요.',
            'nickname.string' => '닉네임은 문자열이어야 합니다.',
            'nickname.min' => '닉네임은 최소 2자 이상이어야 합니다.',
            'nickname.max' => '닉네임은 최대 8자 이하이어야 합니다.',
        ];
    }
}
