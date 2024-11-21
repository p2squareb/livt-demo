<?php

namespace App\Services;

use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class PointService
{
    public function savePlusPoint(string $target_name, int $target_id, string $point_item, int $to_user_id, int $from_user_id): void
    {
        $point_type = ''; $reason = ''; $amount = 0; $processed_by = '';

        if ($point_item === 'join' && cache('config.point')->point->use_point_join) {
            $point_type = 'P';
            $reason = '회원가입';
            $amount = cache('config.point')->point->use_point_join_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'login' && cache('config.point')->point->use_point_login) {
            $point_type = 'P';
            $reason = '로그인';
            $amount = cache('config.point')->point->use_point_login_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'write' && cache('config.point')->point->use_point_write) {
            $point_type = 'P';
            $reason = '게시글 작성에 따른 포인트 지급';
            $amount = cache('config.point')->point->use_point_write_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'write_comment' && cache('config.point')->point->use_point_write_comment) {
            $point_type = 'P';
            $reason = '본인 작성글에 댓글 달림 포인트 지급';
            $amount = cache('config.point')->point->use_point_write_comment_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'write_up' && cache('config.point')->point->use_point_write_up) {
            $point_type = 'P';
            $reason = '본인 작성글에 추천 포인트 지급';
            $amount = cache('config.point')->point->use_point_write_up_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'comment' && cache('config.point')->point->use_point_comment) {
            $point_type = 'P';
            $reason = '댓글 작성에 따른 포인트 지급';
            $amount = cache('config.point')->point->use_point_comment_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'comment_up' && cache('config.point')->point->use_point_comment_up) {
            $point_type = 'P';
            $reason = '본인 댓글에 추천 포인트 지급';
            $amount = cache('config.point')->point->use_point_comment_up_amt;
            $processed_by = 'system';
        }

        $insertData = [
            'point_type' => $point_type,
            'point_item' => $point_item,
            'to_user_id' => $to_user_id,
            'from_user_id' => $from_user_id,
            'reason' => $reason,
            'amount' => $amount,
            'target_name' => $target_name,
            'target_id' => $target_id,
            'processed_by' => $processed_by,
        ];
        Point::create($insertData);

        $user = User::select('point')->where('id', $to_user_id)->first();
        $newPoints = $user->point + $amount;
        if ($newPoints < 0) {
            User::where('id', $to_user_id)->update(['point' => 0]);
        } else {
            User::where('id', $to_user_id)->increment('point', $amount);
        }
    }

    public function saveMinusPoint(string $target_name, int $target_id, string $point_item, int $to_user_id, int $from_user_id): void
    {
        $point_type = ''; $reason = ''; $amount = 0; $processed_by = '';

        if ($point_item === 'write_up_cancel' && cache('config.point')->point->use_point_write_up) {
            $point_type = 'M';
            $reason = '본인 작성글에 추천 취소, 포인트 차감';
            $amount = cache('config.point')->point->use_point_write_up_amt;
            $processed_by = 'system';
        } elseif ($point_item === 'comment_up_cancel' && cache('config.point')->point->use_point_comment_up) {
            $point_type = 'M';
            $reason = '본인 댓글에 추천 취소, 포인트 차감';
            $amount = cache('config.point')->point->use_point_comment_up_amt;
            $processed_by = 'system';
        }

        $insertData = [
            'point_type' => $point_type,
            'point_item' => $point_item,
            'to_user_id' => $to_user_id,
            'from_user_id' => $from_user_id,
            'reason' => $reason,
            'amount' => $amount,
            'target_name' => $target_name,
            'target_id' => $target_id,
            'processed_by' => $processed_by,
        ];
        Point::create($insertData);

        $user = User::select('point')->where('id', $to_user_id)->first();
        $newPoints = $user->point - $amount;
        if ($newPoints < 0) {
            User::where('id', $to_user_id)->update(['point' => 0]);
        } else {
            User::where('id', $to_user_id)->decrement('point', $amount);
        }
    }

    public function savePointByAdmin(Request $request): void
    {
        DB::beginTransaction();
        try {
            if ($request->target_user === 'g') {
                $users = User::select('id', 'point')->where('group_level', $request->group_level)->where('status', 1)->get();
            }else{
                $users = User::select('id', 'point')->whereIn('id', $request->selected_user_ids)->where('status', 1)->get();
            }

            foreach ($users as $user) {
                if ($request->point_type === 'P') {
                    User::where('id', $user->id)->increment('point', $request->amount);
                }else{
                    $amount = $request->amount * -1;
                    $newPoints = $user->point + $amount;
                    if ($newPoints < 0) {
                        User::where('id', $user->id)->update(['point' => 0]);
                    } else {
                        User::where('id', $user->id)->increment('point', $amount);
                    }
                }

                $insertData = [
                    'point_type' => $request->point_type,
                    'point_item' => 'admin',
                    'to_user_id' => $user->id,
                    'from_user_id' => Auth::id(),
                    'reason' => $request->reason,
                    'amount' => $request->amount,
                    'target_name' => 'users',
                    'target_id' => $user->id,
                    'processed_by' => 'admin',
                ];
                Point::create($insertData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getPoints(Request $request, $userId = null)
    {
        $query = Point::with('user:id,nickname,email', 'sender:id,nickname,email');
        $query->filter($request->only(['pointType', 'pagePeriod', 'searchString', 'userId']));
        if (!is_null($userId)) {
            return $query->where('to_user_id', $userId)->orderBy('id', 'desc')->paginate($request->pageRows ?? 10);
        }else{
            return $query->orderBy('id', 'desc')->paginate($request->pageRows ?? 30)->withQueryString();
        }
    }

    public function getSumPointsByPeriod(Request $request, int $userId = null, int $days = null)
    {
        $query = Point::query();
        if (!is_null($userId)) {
            $query->where('to_user_id', $userId);
        }
        if (!is_null($days)) {
            $query->where('created_at', '>=', now()->subDays($days));
        }
        return $query->sum('amount');
    }

}
