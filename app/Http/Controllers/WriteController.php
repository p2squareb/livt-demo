<?php

namespace App\Http\Controllers;

use App\Models\BoardWrite;
use App\Models\BoardRate;
use App\Models\BoardComment;
use App\Models\BoardReport;
use App\Models\BoardBookmark;
use App\Services\BoardService;
use App\Services\FileService;
use App\Services\PointService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class WriteController extends Controller
{
    private PointService $pointService;
    private BoardService $boardService;
    private FileService $fileService;
    private NotificationService $notificationService;

    public function __construct(BoardService $boardService, FileService $fileService, PointService $pointService, NotificationService $notificationService) 
    {
        $this->boardService = $boardService;
        $this->fileService = $fileService;
        $this->pointService = $pointService;
        $this->notificationService = $notificationService;
    }

    public function list(Request $request, $tableId)
    {
        $board = $this->boardService->getBoard($tableId);
        $isWrite = $this->boardService->validateIsWrite($board->write_level);
        $writes = $this->boardService->getWrites($request, $board->table_id);

        return Inertia::render('Write/List', [
            'board' => $board,
            'writes' => $writes,
            'isWrite' => $isWrite,
            'request' => $request->all(),
        ]);
    }

    public function listMore(Request $request, $tableId)
    {
        $userId = $request->userId ?? null;
        $writes = $this->boardService->getWrites($request, $tableId, $userId);

        return response()->json([
            'writes' => $writes,
            'request' => $request->all(),
        ]);
    }

    public function read(Request $request, $tableId, $writeId)
    {
        BoardWrite::where('id', $writeId)->increment('hit');
        $board = $this->boardService->getBoard($tableId); 
        $write = $this->boardService->getWrite($writeId);
        $comments = $this->boardService->getComments($writeId);

        return Inertia::render('Write/Read', [
            'board' => $board,
            'write' => $write,
            'comments' => $comments,
            'request' => $request->all(),
        ]);
    }

    public function create(Request $request, $tableId)
    {
        $board = $this->boardService->getBoard($tableId);

        return Inertia::render('Write/Create', [
            'board' => $board,
            'request' => $request->all(),
        ]);
    }

    public function store(Request $request, $tableId)
    {
        $request->validate([
            'subject' => ['required'],
            'content' => ['required'],
        ], [
            'subject.required' => '제목을 입력해 주세요.',
            'content.required' => '내용을 입력해 주세요.',
        ]);

        $board = $this->boardService->getBoard($tableId);

        $insertData = [
            'board_id' => $board->id,
            'table_id' => $board->table_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'ip' => request()->ip(),
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'writer' => Auth::check() ? Auth::user()->nickname : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($board->use_category) {
            $insertData['categories'] = $request->category ?? '일반';
        }

        if ($board->use_tags) {
            $insertData['tags'] = $request->tags;
        }

        $hasImage = $this->fileService->containsImage($request->content);
        $insertData['has_image'] = $hasImage;

        if ($hasImage){
            $firstImageUrl = $this->fileService->extractFirstImageUrl($request->content);
            $insertData['list_file'] = $firstImageUrl;
        }

        $hasVideo = $this->fileService->containsVideo($request->content);
        $insertData['has_video'] = $hasVideo;

        try {
            $newWriteId = BoardWrite::insertGetId($insertData);
            $this->boardService->updateArticleCount($board->table_id, 'create');

            $this->pointService->savePlusPoint('board_writes', $newWriteId, 'write', Auth::id(), Auth::id());
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'subject' => '게시글을 등록하는데 실패하였습니다.',
            ]);
        }
    }

    public function edit($tableId, $writeId)
    {
        $board = $this->boardService->getBoard($tableId); 
        $write = $this->boardService->getWrite($writeId);

        return Inertia::render('Write/Update', [
            'board' => $board,
            'write' => $write,
        ]);
    }

    public function update(Request $request, $tableId, $writeId)
    {
        $request->validate([
            'subject' => ['required'],
            'content' => ['required'],
        ], [
            'subject.required' => '제목을 입력해 주세요.',
            'content.required' => '내용을 입력해 주세요.',
        ]);

        $board = $this->boardService->getBoard($tableId);

        $updateData = [
            'subject' => $request->subject,
            'content' => $request->content,
            'ip' => request()->ip(),
            'updated_at' => now(),
        ];

        if ($board->use_category) {
            $updateData['categories'] = $request->category ?? '일반';
        }

        if ($board->use_tags) {
            $updateData['tags'] = $request->tags;
        }

        $hasImage = $this->fileService->containsImage($request->content);
        $updateData['has_image'] = $hasImage;

        if ($hasImage){
            $firstImageUrl = $this->fileService->extractFirstImageUrl($request->content);
            $updateData['list_file'] = $firstImageUrl;
        }

        $hasVideo = $this->fileService->containsVideo($request->content);
        $updateData['has_video'] = $hasVideo;

        try {
            BoardWrite::where('id', $writeId)->update($updateData);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'subject' => '게시글을 수정하는데 실패하였습니다.',
            ]);
        }
    }

    public function delete($tableId, $writeId)
    {
        try {
            BoardWrite::where('id', $writeId)->update([
                'is_delete' => 1,
                'deleted_at' => Carbon::now()
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', '게시글 삭제에 실패하였습니다.');
        }
    }

    public function commentListMore(Request $request, $tableId, $writeId)
    {
        $userId = $request->userId ?? null;
        $comments = $this->boardService->getComments($writeId, $userId);
        return response()->json([
            'comments' => $comments,
        ]);
    }

    public function commentCreate(Request $request, $tableId, $writeId)
    {
        $write = BoardWrite::select('user_id')->where('id', $writeId)->first();

        try {
            $lastCommentInsertId = BoardComment::insertGetId([
                'write_id' => $writeId,
                'depth' => 0,
                'user_id' => Auth::user()->id,
                'writer' => Auth::user()->nickname,
                'comment' => $request->comment,
                'ip' => request()->ip(), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            BoardComment::where('id', $lastCommentInsertId)->update([
               'parent_id' => $lastCommentInsertId,
            ]);
            $this->boardService->updateCommentCount($tableId, $writeId, 'create');

            $this->pointService->savePlusPoint('board_comments', $lastCommentInsertId, 'comment', Auth::id(), Auth::id());

            if ($write->user_id !== Auth::id()){
                $this->pointService->savePlusPoint('board_writes', $writeId, 'write_comment', $write->user_id, Auth::id());
            }

            if (Auth::id() !== $write->user_id) {
                $this->notificationService->saveNotification('createComment', $writeId, $lastCommentInsertId);
            }

        }catch (Exception $e) {
            return redirect()->back()->with('error', '댓글 등록에 실패하였습니다.');
        }
    }

    public function commentReplyCreate(Request $request, $tableId, $writeId)
    {
        $write = BoardWrite::select('user_id')->where('id', $writeId)->first();
        $comment = BoardComment::where('id', $request->parent_id)->first();

        if ($comment->depth > 0){
            $parentWriter = '<span class="text-indigo-500 dark:text-indigo-300 font-semibold mr-2">@'.$comment->writer.'</span>';
        }else{
            $parentWriter = '';
        }
        
        try {
            $lastCommentInsertId = BoardComment::insertGetId([
                'write_id' => $writeId,
                'parent_id' => $comment->depth > 0 ? $comment->parent_id : $request->parent_id,
                'depth' => $comment->depth + 1,
                'user_id' => Auth::user()->id,
                'writer' => Auth::user()->nickname,
                'comment' => $parentWriter.$request->replyComment,
                'ip' => request()->ip(), 
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->boardService->updateCommentCount($tableId, $writeId, 'create');

            $this->pointService->savePlusPoint('board_comments', $lastCommentInsertId, 'comment', Auth::id(), Auth::id());

            if ($write->user_id !== Auth::id()){
                $this->pointService->savePlusPoint('board_writes', $writeId, 'write_comment', $write->user_id, Auth::id());
            }

            if (Auth::id() !== $comment->user_id) {
                $this->notificationService->saveNotification('createReplyComment', $request->parent_id, $lastCommentInsertId);
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', '댓글 등록에 실패하였습니다.');
        }
    }

    public function commentUpdate(Request $request, $tableId, $writeId, $commentId)
    {
        try {
            BoardComment::where('id', $commentId)->update([
                'comment' => $request->comment,
                'ip' => request()->ip(),
                'updated_at' => now(),
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '댓글 수정에 실패하였습니다.');
        }
    }

    public function commentDelete($tableId, $writeId, $commentId)
    {
        try{
            BoardComment::where('id', $commentId)->update([
                'is_delete' => 1,
                'deleted_at' => Carbon::now()
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '댓글 삭제에 실패하였습니다.');
        }
    }

    public function rateUpdate(Request $request): void
    {
        if ($request->targetType === 'writes'){
            $data = BoardWrite::Select('user_id')->where('id', $request->targetId)->first();
        }else if($request->targetType === 'comments'){
            $data = BoardComment::Select('user_id')->where('id', $request->targetId)->first();
        }
        
        $existingRate = BoardRate::where('target_table', 'board_'.$request->targetType)->where('target_id', $request->targetId)->where('user_id', Auth::user()->id)->first();
        if ($existingRate) {
            BoardRate::where('id', $existingRate->id)->delete();
            if ($existingRate->rate === $request->option) {
                if ($request->option === true) {
                    if ($request->targetType === 'writes'){
                        BoardWrite::where('id', $request->targetId)->decrement('rate_up');
                        $this->pointService->saveMinusPoint('board_writes', $request->targetId, 'write_up_cancel', $data->user_id, Auth::id());
                    }else if($request->targetType === 'comments'){
                        BoardComment::where('id', $request->targetId)->decrement('rate_up');
                        $this->pointService->saveMinusPoint('board_comments', $request->targetId, 'comment_up_cancel', $data->user_id, Auth::id());
                    }
                } else {
                    if ($request->targetType === 'writes'){
                        BoardWrite::where('id', $request->targetId)->decrement('rate_down');
                    }else if($request->targetType === 'comments'){
                        BoardComment::where('id', $request->targetId)->decrement('rate_down');
                    }
                }
            }else{
                BoardRate::create([
                    'target_table' => 'board_'.$request->targetType,
                    'target_id' => $request->targetId,
                    'user_id' => Auth::user()->id,
                    'rate' => $request->option,
                    'target_user_id' => $data->user_id,
                ]);
                if ($request->targetType === 'writes'){
                    if ($request->option === true) {
                        BoardWrite::where('id', $request->targetId)->decrement('rate_down');
                        BoardWrite::where('id', $request->targetId)->increment('rate_up');
                        $this->pointService->savePlusPoint('board_writes', $request->targetId, 'write_up', $data->user_id, Auth::id());
                    } else {
                        BoardWrite::where('id', $request->targetId)->decrement('rate_up');
                        BoardWrite::where('id', $request->targetId)->increment('rate_down');
                        $this->pointService->saveMinusPoint('board_writes', $request->targetId, 'write_up_cancel', $data->user_id, Auth::id());
                    }
                }else if($request->targetType === 'comments'){
                    if ($request->option === true) {
                        BoardComment::where('id', $request->targetId)->decrement('rate_down');
                        BoardComment::where('id', $request->targetId)->increment('rate_up');
                        $this->pointService->savePlusPoint('board_comments', $request->targetId, 'comment_up', $data->user_id, Auth::id());
                    } else {
                        BoardComment::where('id', $request->targetId)->decrement('rate_up');
                        BoardComment::where('id', $request->targetId)->increment('rate_down');
                        $this->pointService->saveMinusPoint('board_comments', $request->targetId, 'comment_up_cancel', $data->user_id, Auth::id());
                    }
                }
            }
        }else{
            BoardRate::create([
                'target_table' => 'board_'.$request->targetType,
                'target_id' => $request->targetId,
                'user_id' => Auth::user()->id,
                'rate' => $request->option,
                'target_user_id' => $data->user_id,
            ]);
            if ($request->option === true) {
                if ($request->targetType === 'writes'){ 
                    BoardWrite::where('id', $request->targetId)->increment('rate_up');
                    $this->pointService->savePlusPoint('board_writes', $request->targetId, 'write_up', $data->user_id, Auth::id());
                }else if($request->targetType === 'comments'){
                    BoardComment::where('id', $request->targetId)->increment('rate_up');
                    $this->pointService->savePlusPoint('board_comments', $request->targetId, 'comment_up', $data->user_id, Auth::id());
                }
            } else {
                if ($request->targetType === 'writes'){
                    BoardWrite::where('id', $request->targetId)->increment('rate_down');
                }else if($request->targetType === 'comments'){
                    BoardComment::where('id', $request->targetId)->increment('rate_down');
                }
            }
        }
    }

    public function bookmarkStore(Request $request)
    {
        $write = BoardWrite::select('subject')->where('id', $request->targetId)->first();
        $existingBookmark = BoardBookmark::where('target_id', $request->targetId)->where('target_table', 'board_writes')->where('user_id', Auth::user()->id)->first();

        try {
            if ($existingBookmark) {
                $existingBookmark->delete();
            } else {
                BoardBookmark::create([
                    'target_id' => $request->targetId,
                    'target_table' => 'board_writes',
                    'title' => $write->subject,
                    'user_id' => Auth::user()->id,
                    'ip' => request()->ip(),
                ]);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', '북마크 등록에 실패하였습니다.');
        }
    }

    public function reportStore(Request $request)
    {
        if ($request->targetType === 'write'){
            $data = BoardWrite::where('id', $request->targetId)->first();
            $tblName = 'board_writes';
        }else if($request->targetType === 'comment'){
            $data = BoardComment::where('id', $request->targetId)->first();
            $tblName = 'board_comments';
        }

        if (Auth::id() === $data->user_id){
            return redirect()->back()->with([
                'status' => 'warning',
                'message' => '본인이 작성한 글은 신고가 불가합니다.',
            ]);
        }else{
            $report = BoardReport::where('target_table', $tblName)->where('target_id', $request->targetId)->where('user_id', Auth::user()->id)->exists();
            if ($report) {
                return redirect()->back()->with([
                    'status' => 'warning',
                    'message' => '이미 신고한 게시물입니다.',
                ]);
            }else{
                BoardReport::create([
                    'target_table' => $tblName,
                    'target_id' => $request->targetId,
                    'user_id' => Auth::user()->id,
                    'target_user_id' => $data->user_id,
                ]);

                $this->boardService->updateReportCount($request->targetType, $request->targetId);
                
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '신고가 접수되었습니다.',
                ]);
            }
        }
    }
}
