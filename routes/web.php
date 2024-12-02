<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\WriteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

/** 게시판 */
Route::group(['prefix' => '/bbs/{tableId}', 'middleware' => 'board-auth-check:status'], function()
{
    Route::get('/list', [WriteController::class, 'list'])->name('write.list');
    Route::get('/list-more', [WriteController::class, 'listMore'])->name('write.list.more');
    Route::get('/read/{writeId}', [WriteController::class, 'read'])->name('write.read')->middleware('board-auth-check:read');
    Route::get('/create', [WriteController::class, 'create'])->name('write.create')->middleware('board-auth-check:create');
    Route::post('/create', [WriteController::class, 'store'])->name('write.store')->middleware('board-auth-check:create');
    Route::get('/update/{writeId}', [WriteController::class, 'edit'])->name('write.edit')->middleware('board-auth-check:update');
    Route::put('/update/{writeId}', [WriteController::class, 'update'])->name('write.update')->middleware('board-auth-check:update');
    Route::put('/delete/{writeId}', [WriteController::class, 'delete'])->name('write.delete')->middleware('board-auth-check:update');
    Route::post('/comment-create/{writeId}', [WriteController::class, 'commentCreate'])->name('write.comment.create')->middleware('board-auth-check:create');
    Route::post('/comment-reply-create/{writeId}', [WriteController::class, 'commentReplyCreate'])->name('write.comment.reply.create')->middleware('board-auth-check:create');
    Route::get('/comment-more/{writeId}', [WriteController::class, 'commentListMore'])->name('write.comment.list.more');
    Route::put('/comment-update/{writeId}/{commentId}', [WriteController::class, 'commentUpdate'])->name('write.comment.update')->middleware('board-auth-check:comment-update');
    Route::put('/comment-delete/{writeId}/{commentId}', [WriteController::class, 'commentDelete'])->name('write.comment.delete')->middleware('board-auth-check:comment-update');
});

Route::middleware('auth')->group(function () {
    /** 게시판 관련 */
    Route::post('/report/create', [WriteController::class, 'reportStore'])->name('write.report.create');
    Route::post('/bookmark/create', [WriteController::class, 'bookmarkStore'])->name('write.bookmark.create');
    Route::post('/rate/update', [WriteController::class, 'rateUpdate'])->name('write.rate.update');

    /** 프로필 */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/image-upload', [ProfileController::class, 'profileImageUpload'])->name('profile.image.upload');
    Route::delete('/profile/image-destroy', [ProfileController::class, 'profileImageDestroy'])->name('profile.image.destroy');

    /** 마이페이지 */
    Route::get('/mypage/overview', [MypageController::class, 'overview'])->name('mypage.overview');
    Route::get('/mypage/write', [MypageController::class, 'write'])->name('mypage.write');
    Route::get('/mypage/comment', [MypageController::class, 'comment'])->name('mypage.comment');
    Route::get('/mypage/bookmark', [MypageController::class, 'bookmark'])->name('mypage.bookmark');
    Route::get('/mypage/inquiry-list', [MypageController::class, 'inquiryList'])->name('mypage.inquiry.list');
    Route::get('/mypage/inquiry-list-more', [MypageController::class, 'inquiryListMore'])->name('mypage.inquiry.list.more');
    Route::post('/mypage/inquiry-create', [MypageController::class, 'inquiryCreate'])->name('mypage.inquiry.create');
    Route::get('/point/my-list', [PointController::class, 'myPointList'])->name('point.my-list');
});

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'admin']], function() {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    /** 시스템 설정 */
    Route::get('/system/basic', [SystemController::class, 'basic'])->name('admin.system.basic');
    Route::post('/system/basic', [SystemController::class, 'basicUpdate'])->name('admin.system.basic.update');
    Route::get('/system/external', [SystemController::class, 'external'])->name('admin.system.external');
    Route::post('/system/external', [SystemController::class, 'externalUpdate'])->name('admin.system.external.update');
    Route::get('/system/policy-terms', [SystemController::class, 'policyTerms'])->name('admin.system.policy-terms');
    Route::post('/system/policy-terms', [SystemController::class, 'updatePolicyTerms'])->name('admin.system.policy-terms.update');

    /** 회원 */
    Route::get('/user/list', [UserManageController::class, 'list'])->name('admin.user.list');
    Route::post('/user/create', [UserManageController::class, 'create'])->name('admin.user.create');
    Route::post('/user/change-group', [UserManageController::class, 'changeGroup'])->name('admin.user.changeGroup');
    Route::get('/user/read/{userId}', [UserManageController::class, 'read'])->name('admin.user.read');
    Route::get('/user/info-extra', [UserManageController::class, 'infoExtra'])->name('admin.user.info-extra');
    Route::get('/user/search', [UserManageController::class, 'search'])->name('admin.user.search');
    Route::get('/user/group-list', [UserManageController::class, 'groupList'])->name('admin.user.group-list');
    Route::post('/user/group-create', [UserManageController::class, 'groupCreate'])->name('admin.user.group-create');
    Route::put('/user/group-update', [UserManageController::class, 'groupUpdate'])->name('admin.user.group-update');
    Route::delete('/user/group-delete', [UserManageController::class, 'groupDelete'])->name('admin.user.group-delete');
    Route::post('/user/prohibit-update', [UserManageController::class, 'prohibitUpdate'])->name('admin.user.prohibit-update');
    Route::post('/user/send-temp-password', [UserManageController::class, 'sendTempPassword'])->name('admin.user.send-temp-password');

    /** 포인트 */
    Route::get('/point/setting', [PointController::class, 'setting'])->name('admin.point.setting');
    Route::post('/point/setting', [PointController::class, 'settingUpdate'])->name('admin.point.setting.update');
    Route::get('/point/list', [PointController::class, 'list'])->name('admin.point.list');
    Route::post('/point/create', [PointController::class, 'create'])->name('admin.point.create');

    /** 게시판 */
    Route::get('/board/list', [BoardController::class, 'list'])->name('admin.board.list');
    Route::post('/board/create', [BoardController::class, 'create'])->name('admin.board.create');
    Route::put('/board/update', [BoardController::class, 'update'])->name('admin.board.update');
    Route::get('/board/write-list', [BoardController::class, 'writeList'])->name('admin.board.write.list');
    Route::put('/board/write-delete-status-update', [BoardController::class, 'writeDeleteStatusUpdate'])->name('admin.board.write.delete-status-update');
    Route::get('/board/comment-list', [BoardController::class, 'commentList'])->name('admin.board.comment.list');
    Route::put('/board/comment-delete-status-update', [BoardController::class, 'commentDeleteStatusUpdate'])->name('admin.board.comment.delete-status-update');
    Route::get('/board/report/{targetTable}/{targetId}', [BoardController::class, 'reportList'])->name('admin.board.report.list');
    Route::get('/board/inquiry-list', [BoardController::class, 'inquiryList'])->name('admin.board.inquiry.list');
    Route::put('/board/inquiry-answer', [BoardController::class, 'inquiryAnswer'])->name('admin.board.inquiry.answer');
});
