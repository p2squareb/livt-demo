<?php

namespace App\Services;

use App\Models\Board;
use App\Models\BoardWrite;
use App\Models\BoardComment;
use App\Models\BoardBookmark;
use App\Models\Category;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoardService
{
    public function getBoard($tableId): Model|Builder|Board
    {
        return Board::where('table_id', $tableId)->firstOrFail();
    }

    public function getCategory($targetName)
    {
        return Category::where('target_name', $targetName)->get();
    }

    public function validateIsWrite($boardWriteLevel): bool
    {
        $baseLevel = 0; $isWrite = false;

        if(Auth::check()) {
            $baseLevel = Auth::user()->group_level;
        }

        if($baseLevel >= $boardWriteLevel) {
            $isWrite = true;
        }

        return $isWrite;
    }

    public function getWriteThreeDotView($writerId): bool
    {
        $threeDotView = false;

        if (Auth::check()) {
            if (is_null($writerId)) {
                $threeDotView = true;
            }else{
                if (Auth::user()->group_level >= 11 || ($writerId === Auth::user()->id)) {
                    $threeDotView = true;
                }
            }
        }else{
            if (is_null($writerId)) {
                $threeDotView = true;
            }
        }

        return $threeDotView;
    }

    public function getWrites(Request $request, $tableId, $userId = null)
    {
        $query = BoardWrite::with(['user' => function($query) {
            $query->select('id', 'profile_photo_path');
        }])->where('is_delete', 0)->where('is_notice', 0);

        if (!is_null($userId)) {
            $query->with('board')->where('board_writes.user_id', $userId);
        }else{
            $query->where('board_writes.table_id', $tableId);
        }
            
        $query->when(Auth::check(), function($query) {
            return $query->leftJoin('board_rates', function($join) {
                $join->on('board_writes.id', '=', 'board_rates.target_id')
                    ->where('board_rates.user_id', Auth::id());
            })
            ->leftJoin('board_bookmarks', function($join) {
                $join->on('board_writes.id', '=', 'board_bookmarks.target_id')
                    ->where('board_bookmarks.target_table', 'board_writes')
                    ->where('board_bookmarks.user_id', Auth::id());
            })
            ->select('board_writes.*', 
                DB::raw("CASE WHEN board_rates.rate = true THEN true ELSE false END as user_rate_up"),
                DB::raw("CASE WHEN board_rates.rate = false THEN true ELSE false END as user_rate_down"),
                DB::raw("CASE WHEN board_bookmarks.id IS NOT NULL THEN true ELSE false END as user_bookmark")
            );
        });

        $query->filter($request->only(['category', 'searchString', 'searchTag', 'sortBy']));
        $writes = $query->paginate($request->pageRows ?? 15);
        return $writes;
    }

    public function getWrite($writeId)
    {
        $query = BoardWrite::with(['user' => function($query) {
            $query->select('id', 'profile_photo_path');
        }]);
        
        $query->when(Auth::check(), function($query) {
            return $query->leftJoin('board_rates', function($join) {
                $join->on('board_writes.id', '=', 'board_rates.target_id')
                    ->where('board_rates.user_id', Auth::id());
            })
            ->leftJoin('board_bookmarks', function($join) {
                $join->on('board_writes.id', '=', 'board_bookmarks.target_id')
                    ->where('board_bookmarks.target_table', 'board_writes')
                    ->where('board_bookmarks.user_id', Auth::id());
            })
            ->select('board_writes.*', 
                DB::raw("CASE WHEN board_rates.rate = true THEN true ELSE false END as user_rate_up"),
                DB::raw("CASE WHEN board_rates.rate = false THEN true ELSE false END as user_rate_down"),
                DB::raw("CASE WHEN board_bookmarks.id IS NOT NULL THEN true ELSE false END as user_bookmark")
            );
        });
        
        $write = $query->where('board_writes.id', $writeId)->first();    
        $threeDotView = $this->getWriteThreeDotView($write->user_id);

        return [
            'data' => $write,
            'threeDotView' => $threeDotView
        ];
    }

    public function getComments($writeId, $userId = null)
    {
        $query = BoardComment::with(['user' => function($query) {
            $query->select('id', 'profile_photo_path');
        }]);

        $query->when(Auth::check(), function($query) {
            return $query->leftJoin('board_rates', function($join) {
                $join->on('board_comments.id', '=', 'board_rates.target_id')
                    ->where('board_rates.user_id', Auth::id());
            })
            ->select('board_comments.*',
                DB::raw("CASE WHEN board_rates.rate = true THEN true ELSE false END as user_rate_up"),
                DB::raw("CASE WHEN board_rates.rate = false THEN true ELSE false END as user_rate_down")
            );
        });

        if (!is_null($userId)) {
            return $query->with('write:id,table_id')->where('board_comments.user_id', $userId)->orderBy('id')->paginate(30);
        }else{
            return $query->where('write_id', $writeId)->orderBy('parent_id')->orderBy('depth')->orderBy('id')->paginate(30);
        }
    }

    public function getBookmarks($userId)
    {
        $query = BoardBookmark::with(['write' => function($query) {
            $query->when(Auth::check(), function($query) {
                return $query->leftJoin('board_rates', function($join) {
                    $join->on('board_writes.id', '=', 'board_rates.target_id')
                        ->where('board_rates.user_id', Auth::id());
                })
                ->leftJoin('board_bookmarks', function($join) {
                    $join->on('board_writes.id', '=', 'board_bookmarks.target_id')
                        ->where('board_bookmarks.target_table', 'board_writes')
                        ->where('board_bookmarks.user_id', Auth::id());
                })
                ->select('board_writes.*',
                    DB::raw("CASE WHEN board_rates.rate = true THEN true ELSE false END as user_rate_up"),
                    DB::raw("CASE WHEN board_rates.rate = false THEN true ELSE false END as user_rate_down"),
                    DB::raw("CASE WHEN board_bookmarks.id IS NOT NULL THEN true ELSE false END as user_bookmark")
                );
            });
        }, 'write.board:id,table_id,use_category,use_tags,use_rate']);

        return $query->where('user_id', $userId)->get();
    }

    public function updateArticleCount($tableId, $type): void
    {
        if ($type === 'create') {
            Board::where('table_id', $tableId)->increment('article_count');
        }else if ($type === 'delete') {
            Board::where('table_id', $tableId)->decrement('article_count');
        }
    }

    public function updateCommentCount($tableId, $writeId, $type): void
    {
        if ($type === 'create') {
            Board::where('table_id', $tableId)->increment('comment_count');
            BoardWrite::where('id', $writeId)->increment('comment_count');
        }else if ($type === 'delete') {
            Board::where('table_id', $tableId)->decrement('comment_count');
            BoardWrite::where('id', $writeId)->decrement('comment_count');
        }
    }

    public function updateReportCount($targetTable, $targetId): void
    {
        if ($targetTable === 'write') {
            BoardWrite::where('id', $targetId)->increment('report_count');
        }else if ($targetTable === 'comment') {
            BoardComment::where('id', $targetId)->increment('report_count');
        }
    }

    public function getInquiries(Request $request, $userId = null)
    {
        $query = Inquiry::with(['user' => function($query) {
            $query->select('id', 'nickname');
        }, 'answer' => function($query) {
            $query->select('id', 'nickname');
        }]);
        
        $query->filter($request->only(['category', 'is_answer', 'searchString', 'userId']));

        if (!is_null($userId)) {
            return $query->where('user_id', $userId)->orderBy('id', 'desc')->paginate($request->pageRows ?? 15);
        }else{
            return $query->orderBy('id', 'desc')->paginate($request->pageRows ?? 15)->withQueryString();
        }
    }



    public function getUserWriteCount($userId)
    {
        return BoardWrite::where('user_id', $userId)->count();
    }

    public function getUserCommentCount($userId)
    {
        return BoardComment::where('user_id', $userId)->count();
    }

    public function getUserInquiryCount($userId)
    {
        return Inquiry::where('user_id', $userId)->count();
    }
}
