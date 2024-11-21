<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\UserGroup;
use App\Services\PointService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class PointController extends Controller
{
    private PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function setting()
    {
        $point = System::where('title', 'point')->orderByDesc('id')->first();
        $data = json_decode($point->content);

        return Inertia::render('Admin/Point/PointSetting', [
            'data' => $data,
        ]);
    }

    public function settingUpdate(Request $request)
    {
        $configData = [
            'point' => [
                'use_point_join' => $request->use_point_join ?? '0',
                'use_point_join_amt' => $request->use_point_join_amt ?? '0',
                'use_point_login' => $request->use_point_login ?? '0',
                'use_point_login_amt' => $request->use_point_login_amt ?? '0',
                'use_point_write' => $request->use_point_write ?? '0',
                'use_point_write_amt' => $request->use_point_write_amt ?? '0',
                'use_point_write_comment' => $request->use_point_write_comment ?? '0',
                'use_point_write_comment_amt' => $request->use_point_write_comment_amt ?? '0',
                'use_point_write_up' => $request->use_point_write_up ?? '0',
                'use_point_write_up_amt' => $request->use_point_write_up_amt ?? '0',
                'use_point_comment' => $request->use_point_comment ?? '0',
                'use_point_comment_amt' => $request->use_point_comment_amt ?? '0',
                'use_point_comment_up' => $request->use_point_comment_up ?? '0',
                'use_point_comment_up_amt' => $request->use_point_comment_up_amt ?? '0',
            ],
        ];

        System::insert([
            'register_ip' => request()->ip(),
            'register_id' => 'system',
            'title' => 'point',
            'content' => json_encode($configData),
        ]);

        Cache::forget("config.point");
    }

    public function list(Request $request)
    {
        return Inertia::render('Admin/Point/PointList', [
            'userGroups' => UserGroup::withCount('users')->where('level', '<=', 11)->get(),
            'points' => $this->pointService->getPoints($request),
            'request' => $request->all(),
        ]);
    }

    public function myPointList(Request $request)
    {
        return response()->json([
            'points' => $this->pointService->getPoints($request, Auth::user()->id),
            'sumPoint7days' => $this->pointService->getSumPointsByPeriod($request, Auth::user()->id, 7),
            'request' => $request->all(),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'group_level' => ['required_if:target_user,g'],
            'selected_user_ids' => ['required_if:target_user,p'],
        ], [
            'amount.required' => '금액을 입력해 주세요.',
            'amount.numeric' => '금액은 숫자만 입력 가능합니다.',
            'amount.gt' => '금액은 0보다 커야 합니다.',
            'group_level.required_if' => '회원그룹을 선택해 주세요.',
            'selected_user_ids.required_if' => '회원을 선택해 주세요.',
        ]);

        try {
            $this->pointService->savePointByAdmin($request);
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 포인트가 지급/차감되었습니다.',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', '포인트 지급/차감에 실패하였습니다.');
        }
    }
}
