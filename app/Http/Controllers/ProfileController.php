<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LoginRecord;
use App\Models\User;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProfileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService) 
    {
        $this->fileService = $fileService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'email_verified_at' => $request->user()->email_verified_at,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => '비밀번호를 입력해 주세요.',
            'password.current_password' => '비밀번호가 일치하지 않습니다.',
        ]);

        $user = $request->user();

        Auth::logout();

        User::where('id', $user->id)->update([
            'status' => 2,
            'leaved_at' => now(),
        ]);
        //$user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function profileImageUpload(Request $request)
    {
        $request->validate([
            'profile' => ['nullable', 'sometimes', 'image', 'max:3072', 'mimes:jpeg,png,jpg,gif'],
        ], [
            'profile.image' => '이미지 파일만 업로드 가능합니다.',
            'profile.mimes' => '이미지는 jpeg, png, jpg, gif 형식만 가능합니다.',
            'profile.max' => '최대 3MB 업로드 가능합니다.',
        ]);

        try {
            $uploadFile = $this->fileService->uploadProfileFile($request->profile);

            $profileData = [
                'profile_photo_path' => $uploadFile['fileSource'],
            ];
            User::where('id', Auth::id())->update($profileData);

            return redirect()->back();
        }catch (Exception $e) {
            throw ValidationException::withMessages([
                'profile' => '이미지를 업로드할 수 없습니다.',
            ]);
        }
    }

    public function profileImageDestroy()
    {
        try {
            $this->fileService->deleteFileOnServer('profiles', Auth::user()->profile_photo_path);
            User::where('id', Auth::id())->update([
                'profile_photo_path' => null,
            ]);
            return redirect()->back();
        }catch (Exception $e) {
            throw ValidationException::withMessages([
                'profile' => '이미지를 삭제할 수 없습니다.',
            ]);
        }
    }

    public function loginRecordLatest()
    {
        return LoginRecord::where('user_id', Auth::user()->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('id', 'desc')
            ->get();
    }
}
