<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProfileController;
use App\Models\Inquiry;
use App\Services\BoardService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MypageController extends Controller
{
    private BoardService $boardService;
    private FileService $fileService;
    private ProfileController $profileController;

    public function __construct(BoardService $boardService, FileService $fileService, ProfileController $profileController) 
    {
        $this->boardService = $boardService;
        $this->fileService = $fileService;
        $this->profileController = $profileController;
    }

    public function overview()
    {
        $loginRecords = $this->profileController->loginRecordLatest();
        return Inertia::render('Mypage/MyOverview', [
            'loginRecords' => $loginRecords,
        ]);
    }

    public function write(Request $request)
    {
        $writes = $this->boardService->getWrites($request, null, Auth::user()->id);
        return Inertia::render('Mypage/MyWrite', [
            'writes' => $writes,
        ]);
    }

    public function comment()
    {
        $comments = $this->boardService->getComments(null, Auth::user()->id);
        return Inertia::render('Mypage/MyComment', [
            'comments' => $comments,
        ]);
    }

    public function bookmark()
    {
        $bookmarks = $this->boardService->getBookmarks(Auth::user()->id);
        return Inertia::render('Mypage/MyBookmark', [
            'bookmarks' => $bookmarks,
        ]);
    }

    public function inquiryList(Request $request)
    {
        $inquiryCategories = $this->boardService->getCategory('inquiry');
        $inquiries = $this->boardService->getInquiries($request, Auth::user()->id);
        return Inertia::render('Mypage/MyInquiryList', [
            'inquiryCategories' => $inquiryCategories,
            'inquiries' => $inquiries,
        ]);
    }

    public function inquiryListMore(Request $request)
    {
        $inquiries = $this->boardService->getInquiries($request, Auth::user()->id);
        return response()->json([
            'inquiries' => $inquiries,
        ]);
    }

    public function inquiryCreate(Request $request)
    {
        $request->validate([
            'category' => ['required'],
            'subject' => ['required'],
            'content' => ['required'],
        ], [
            'category.required' => '문의 유형을 선택해 주세요.',
            'subject.required' => '제목을 입력해 주세요.',
            'content.required' => '문의 내용을 입력해 주세요.',
        ]);

        $insertData = [
            'category' => $request->category,
            'subject' => $request->subject,
            'content' => $request->content,
            'ip' => request()->ip(),
            'user_id' => Auth::user()->id,
        ];

        try{
            Inquiry::create($insertData);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '문의 등록에 실패하였습니다.');
        }
    }

}
