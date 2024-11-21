<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardWrite;
use App\Models\BoardComment;
use App\Models\BoardReport;
use App\Models\Inquiry;
use App\Services\BoardService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BoardController extends Controller
{
    protected $boardService;
    protected $notificationService;

    public function __construct(BoardService $boardService, NotificationService $notificationService)
    {
        $this->boardService = $boardService;
        $this->notificationService = $notificationService;
    }

    public function list(Request $request)
    {
        $boards = Board::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Board/BoardList', [
            'boards' => $boards,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'table_id' => ['required', 'regex:/^[a-zA-Z]+$/', 'min:2', 'max:8', 'unique:'.Board::class],
            'table_name' => ['required'],
            'skin' => ['required'],
            'category_list' => ['required_if:use_category,1'],
        ], [
            'table_id.required' => '게시판ID를 입력해 주세요.',
            'table_id.min' => '게시판ID는 최소 2자 이상이어야 합니다.',
            'table_id.max' => '게시판ID는 최대 8자까지 가능합니다.',
            'table_id.unique' => '이미 사용 중인 게시판ID입니다.',
            'table_id.regex' => '게시판ID는 영문자만 입력 가능합니다',
            'table_name.required' => '게시판 이름을 입력해 주세요.',
            'skin.required' => '스킨을 선택해주세요.',
            'category_list.required_if' => '카테고리 항목을 입력해 주세요.',
        ]);

        $insertData = [
            'table_id' => $request->table_id,
            'table_name' => $request->table_name,
            'status' => $request->status,
            'use_category' => $request->use_category,
            'category_list' => $request->category_list,
            'write_level' => $request->write_level,
            'use_comment' => $request->use_comment,
            'use_rate' => $request->use_rate,
            'use_report' => $request->use_report,
            'use_tags' => $request->use_tags,
            'skin' => $request->skin,
        ];

        try {
            Board::create($insertData);
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 게시판이 등록되었습니다.',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', '게시판 등록에 실패하였습니다.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'table_name' => ['required'],
            'skin' => ['required'],
            'category_list' => ['required_if:use_category,1'],
        ], [
            'table_name.required' => '게시판 이름을 입력해 주세요.',
            'skin.required' => '스킨을 선택해주세요.',
            'category_list.required_if' => '카테고리 항목을 입력해 주세요.',
        ]);

        $updateData = [
            'table_name' => $request->table_name,
            'status' => $request->status,
            'use_category' => $request->use_category,
            'category_list' => $request->category_list,
            'write_level' => $request->write_level,
            'use_comment' => $request->use_comment,
            'use_rate' => $request->use_rate,
            'use_report' => $request->use_report,
            'use_tags' => $request->use_tags,
            'skin' => $request->skin,
        ];

        try {
            Board::where('id', $request->id)->update($updateData);
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 게시판이 수정되었습니다.',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', '게시판 수정에 실패하였습니다.');
        }
    }

    public function writeList(Request $request)
    {
        $boards = Board::select('id', 'table_id', 'table_name')->orderBy('id', 'desc')->get();
        $writes = $this->getWrites($request);
        return Inertia::render('Admin/Board/BoardWriteList', [
            'writes' => $writes,
            'request' => $request->all(),
            'boards' => $boards,
        ]);
    }

    public function getWrites($request)
    {
        $query = BoardWrite::with('board');
        if ($request->tableId ?? false) $query->where('table_id', $request->tableId);
        if ($request->is_delete ?? false) $query->where('is_delete', $request->is_delete);
        if ($request->is_report ?? false) $query->where('report_count', '>', 0);
        $query->filter($request->only(['category', 'searchString', 'searchTag', 'sortBy', 'userId']));

        return $query->orderBy('id', 'desc')->paginate($request->pageRows ?? 15)->withQueryString();
    }

    public function writeDeleteStatusUpdate(Request $request)
    {
        try{
            if ($request->operation === 'delete') {
                BoardWrite::where('id', $request->writeId)->update([
                    'is_delete' => 1,
                    'deleted_at' => Carbon::now()
                ]);

                $this->notificationService->saveNotification('writeDeleteStatusUpdate', $request->writeId);

                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '정상적으로 삭제 처리되었습니다.',
                ]);
            } elseif ($request->operation === 'restore') {
                BoardWrite::where('id', $request->writeId)->update([
                    'is_delete' => 0,
                    'deleted_at' => null
                ]);
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '정상적으로 복구 처리되었습니다.',
                ]);
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', '게시글 삭제 처리에 실패하였습니다.');
        }
    }

    public function commentList(Request $request)
    {
        $boards = Board::select('id', 'table_id', 'table_name')->orderBy('id', 'desc')->get();
        $comments = $this->getComments($request);
        return Inertia::render('Admin/Board/BoardCommentList', [
            'boards' => $boards,
            'comments' => $comments,
            'request' => $request->all(),
        ]);
    }

    public function getComments($request)
    {
        $query = BoardComment::with(['write:id,board_id', 'write.board:id,table_id,table_name']);
        if ($request->tableId ?? false) $query->whereHas('write', function($q) use ($request) {
            $q->where('table_id', $request->tableId);
        });
        if ($request->is_delete ?? false) $query->where('is_delete', $request->is_delete);
        if ($request->is_report ?? false) $query->where('report_count', '>', 0);
        $query->filter($request->only(['searchString', 'userId']));
       
        return $query->orderBy('id', 'desc')->paginate($request->pageRows ?? 15)->withQueryString();
    }

    public function commentDeleteStatusUpdate(Request $request)
    {
        try{
            if ($request->operation === 'delete') {
                BoardComment::where('id', $request->commentId)->update([
                    'is_delete' => 1,
                    'deleted_at' => Carbon::now()
                ]);

                $this->notificationService->saveNotification('commentDeleteStatusUpdate', $request->commentId);

                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '정상적으로 삭제 처리되었습니다.',
                ]);
            } elseif ($request->operation === 'restore') {
                BoardComment::where('id', $request->commentId)->update([
                    'is_delete' => 0,
                    'deleted_at' => null
                ]);
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '정상적으로 복구 처리되었습니다.',
                ]);
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', '댓글 삭제 처리에 실패하였습니다.');
        }
    }

    public function reportList($targetTable, $targetId)
    {
        $reports = BoardReport::with('user:id,nickname')->where('target_table', 'board_'.$targetTable)->where('target_id', $targetId)->orderBy('id', 'desc')->get();
        return response()->json($reports);
    }

    public function inquiryList(Request $request)
    {
        $inquiryCategories = $this->boardService->getCategory('inquiry');
        $inquiries = $this->boardService->getInquiries($request);
        return Inertia::render('Admin/Board/BoardInquiryList', [
            'inquiryCategories' => $inquiryCategories,
            'inquiries' => $inquiries,
            'request' => $request->all(),
        ]);
    }

    public function inquiryAnswer(Request $request)
    {
        $updateData = [
            'answer_user_id' => Auth::user()->id,
            'answer_content' => $request->answer_content,
            'answered_at' => Carbon::now(),
        ];
        if ($request->process === 'proc') $updateData['status'] = 1;

        try{
            Inquiry::where('id', $request->inquiry_id)->update($updateData);
            if ($request->process === 'proc') {
                $this->notificationService->saveNotification('inquiryAnswer', $request->inquiry_id);
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => '정상적으로 답변 처리되었습니다.',
                ]);
            }else{
                return redirect()->back()->with([
                    'status' => 'warning',
                    'message' => '답변이 임시저장되었습니다.',
                ]);
            }
        }catch (Exception $e) {
            return redirect()->back()->with('error', '답변 처리에 실패하였습니다.');
        }
    }

}
