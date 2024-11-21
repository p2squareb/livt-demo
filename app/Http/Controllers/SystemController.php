<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function basic(Request $request) 
    {
        $basic = System::where('title', 'basic')->orderByDesc('id')->first();
        $data = json_decode($basic->content);

        return Inertia::render('Admin/System/Basic', [
            'data' => $data,
        ]);
    }

    public function basicUpdate(Request $request): void
    {
        $configData = [
            'basic' => [
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'domain_name' => $request->domain_name,
                'sq_email' => $request->sq_email,
                'use_smtp' => $request->use_smtp ?? '0',
                'use_external_email' => $request->use_external_email ?? '0',
                'use_dormant' => $request->use_dormant ?? '0',
            ],
            'image' => [
                'image_resize' => $request->image_resize,
                'image_width_max' => $request->image_width_max,
            ],
        ];

        System::insert([
            'register_ip' => request()->ip(),
            'register_id' => 'system',
            'title' => 'basic',
            'content' => json_encode($configData),
        ]);

        Cache::forget("config.basic");
    }

    public function external(Request $request) 
    {
        $use_google_analytics = env('USE_GOOGLE_ANALYTICS', '');
        $google_analytics_id = env('GOOGLE_ANALYTICS_ID', '');
        $use_google_login = env('USE_GOOGLE_LOGIN', '');
        $google_client_id = env('GOOGLE_CLIENT_ID', '');
        $google_client_secret = env('GOOGLE_CLIENT_SECRET', '');
        $use_naver_login = env('USE_NAVER_LOGIN', '');
        $naver_client_id = env('NAVER_CLIENT_ID', '');
        $naver_client_secret = env('NAVER_CLIENT_SECRET', '');
        $use_kakao_login = env('USE_KAKAO_LOGIN', '');
        $kakao_client_id = env('KAKAO_CLIENT_ID', '');
        $kakao_client_secret = env('KAKAO_CLIENT_SECRET', '');
        $use_kakao_map = env('USE_KAKAO_MAP', '');
        $kakao_javascript_key = env('KAKAO_JAVASCRIPT_KEY', '');
        
        $data = [
            'use_google_analytics' => $use_google_analytics,
            'google_analytics_id' => $google_analytics_id, 
            'use_google_login' => $use_google_login,
            'google_client_id' => $google_client_id,
            'google_client_secret' => $google_client_secret,
            'use_naver_login' => $use_naver_login,
            'naver_client_id' => $naver_client_id,
            'naver_client_secret' => $naver_client_secret,
            'use_kakao_login' => $use_kakao_login,
            'kakao_client_id' => $kakao_client_id,
            'kakao_client_secret' => $kakao_client_secret, 
            'use_kakao_map' => $use_kakao_map,
            'kakao_javascript_key' => $kakao_javascript_key,
        ];

        return Inertia::render('Admin/System/External', [
            'data' => $data,
        ]);
    }

    public function externalUpdate(Request $request): void
    {
        $envContent = file_get_contents(base_path('.env'));
        
        $envVars = [
            'USE_GOOGLE_ANALYTICS' => $request->use_google_analytics === true ? 'true' : 'false',
            'GOOGLE_ANALYTICS_ID' => $request->google_analytics_id,
            'USE_GOOGLE_LOGIN' => $request->use_google_login === true ? 'true' : 'false',
            'GOOGLE_CLIENT_ID' => $request->google_client_id,
            'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
            'USE_NAVER_LOGIN' => $request->use_naver_login === true ? 'true' : 'false',
            'NAVER_CLIENT_ID' => $request->naver_client_id,
            'NAVER_CLIENT_SECRET' => $request->naver_client_secret,
            'USE_KAKAO_LOGIN' => $request->use_kakao_login === true ? 'true' : 'false',
            'KAKAO_CLIENT_ID' => $request->kakao_client_id,
            'KAKAO_CLIENT_SECRET' => $request->kakao_client_secret,
            'USE_KAKAO_MAP' => $request->use_kakao_map === true ? 'true' : 'false',
            'KAKAO_JAVASCRIPT_KEY' => $request->kakao_javascript_key,
        ];

        foreach ($envVars as $key => $value) {
            if (str_contains($envContent, $key . '=')) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=" . ($value ? $value : ''),
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}=" . ($value ? $value : '');
            }
        }

        file_put_contents(base_path('.env'), $envContent);
    }

    public function policyTerms(Request $request) 
    {
        $terms = Storage::disk('resources')->get('markdown/terms.html');
        $privacy = Storage::disk('resources')->get('markdown/privacy.html');

        return Inertia::render('Admin/System/PolicyTerms', [
            'terms' => $terms,
            'privacy' => $privacy
        ]);
    }

    public function updatePolicyTerms(Request $request)
    {
        $validated = $request->validate([
            'terms' => 'required|string',
            'privacy' => 'required|string',
        ]);

        Storage::disk('resources')->put('markdown/terms.html', $validated['terms']);
        Storage::disk('resources')->put('markdown/privacy.html', $validated['privacy']);

        return redirect()->back();
    }
}
