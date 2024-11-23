<?php

namespace App\Http\Middleware;

use App\Models\BoardWrite;
use App\Models\BoardComment;
use App\Services\BoardService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BoardAuthCheck
{
    private BoardService $boardService;

    public function __construct(BoardService $boardService) 
    {
        $this->boardService = $boardService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $access) 
    {
        $baseLevel = 0;
        if(Auth::check()) {
            $baseLevel = Auth::user()->group_level;
        }

        $tableId = $request->segment(2);
        $board = $tableId === 'none' ? null : $this->boardService->getBoard($tableId);

        if ($access === 'status') {
            if ($board && $board->status === 0) {
                if(!Auth::check() || Auth::user()->group_level < 11) {
                    abort(404);
                }
            }
        } else if ($access === 'create') {
            if (!Auth::check() || !Auth::user()->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            if($baseLevel < $board->write_level) {
                return response()->view('errors.access-denied', [
                    'type' => 'warning',
                    'next' => 'redirect',
                    'link' => route('write.list', ['tableId' => $tableId]),
                    'message' => '접근 권한이 없습니다.',
                ]);
            }
        } else if ($access === 'read') {
            $writeId = $request->segment(4);
            $write = BoardWrite::where('id', $writeId)->where('is_delete', 0)->first();
            if (is_null($write) && Auth::user()->group_level < 11) {
                abort(404);
            }
        } else if ($access === 'update') {
            if (!Auth::check() || !Auth::user()->email_verified_at) {
                return redirect()->route('verification.notice');
            }

            $writeId = $request->segment(4);
            $write = BoardWrite::where('id', $writeId)->first();

            if (!session()->has(session()->getId() . 'write-' . $tableId . '-' . $writeId)) {
                if (is_null($write->user_id)) {
                    return redirect()->route('write.password.check', ['tableId' => $tableId, 'writeId' => $writeId, 'access' => 'update']);
                }else{
                    if (Auth::user()->group_level >= 11){
                        return $next($request);
                    }elseif (Auth::guest() || $write->user_id !== Auth::user()->id) {
                        abort(403);
                    }
                }
            }
        } else if ($access === 'comment-update') {
            if (!Auth::check() || !Auth::user()->email_verified_at) {
                return redirect()->route('verification.notice'); 
            }

            $commentId = $request->segment(5);
            $comment = BoardComment::where('id', $commentId)->first();

            if (Auth::user()->group_level >= 11){
                return $next($request);
            }elseif (Auth::guest() || $comment->user_id !== Auth::user()->id) {
                abort(403);
            }
        }

        return $next($request);
    }
}
