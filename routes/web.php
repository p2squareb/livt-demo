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
    Route::get('/system/basic', [SystemController::class, 'basic'])->name('admin.system.basic')->middleware('menu-access-check:B1');
    Route::post('/system/basic', [SystemController::class, 'basicUpdate'])->name('admin.system.basic.update')->middleware('menu-access-check:B1');
    Route::get('/system/policy-terms', [SystemController::class, 'policyTerms'])->name('admin.system.policy-terms')->middleware('menu-access-check:B2');
    Route::post('/system/policy-terms', [SystemController::class, 'updatePolicyTerms'])->name('admin.system.policy-terms.update')->middleware('menu-access-check:B3');
    Route::get('/system/external', [SystemController::class, 'external'])->name('admin.system.external')->middleware('menu-access-check:B3');
    Route::post('/system/external', [SystemController::class, 'externalUpdate'])->name('admin.system.external.update')->middleware('menu-access-check:B3');

    /** 회원 */
    Route::get('/user/list', [UserManageController::class, 'list'])->name('admin.user.list')->middleware('menu-access-check:C1');
    Route::post('/user/create', [UserManageController::class, 'create'])->name('admin.user.create')->middleware('menu-access-check:C1');
    Route::post('/user/change-group', [UserManageController::class, 'changeGroup'])->name('admin.user.changeGroup')->middleware('menu-access-check:C1');
    Route::get('/user/read/{userId}', [UserManageController::class, 'read'])->name('admin.user.read')->middleware('menu-access-check:C1');
    Route::get('/user/info-extra', [UserManageController::class, 'infoExtra'])->name('admin.user.info-extra')->middleware('menu-access-check:C1');
    Route::get('/user/search', [UserManageController::class, 'search'])->name('admin.user.search')->middleware('menu-access-check:C1');
    Route::post('/user/prohibit-update', [UserManageController::class, 'prohibitUpdate'])->name('admin.user.prohibit-update')->middleware('menu-access-check:C1');
    Route::post('/user/send-temp-password', [UserManageController::class, 'sendTempPassword'])->name('admin.user.send-temp-password')->middleware('menu-access-check:C1');
    Route::get('/user/group-list', [UserManageController::class, 'groupList'])->name('admin.user.group-list')->middleware('menu-access-check:C2');
    Route::post('/user/group-create', [UserManageController::class, 'groupCreate'])->name('admin.user.group-create')->middleware('menu-access-check:C2');
    Route::put('/user/group-update', [UserManageController::class, 'groupUpdate'])->name('admin.user.group-update')->middleware('menu-access-check:C2');
    Route::delete('/user/group-delete', [UserManageController::class, 'groupDelete'])->name('admin.user.group-delete')->middleware('menu-access-check:C2');

    /** 게시판 */
    Route::get('/board/list', [BoardController::class, 'list'])->name('admin.board.list')->middleware('menu-access-check:D1');
    Route::post('/board/create', [BoardController::class, 'create'])->name('admin.board.create')->middleware('menu-access-check:D1');
    Route::put('/board/update', [BoardController::class, 'update'])->name('admin.board.update')->middleware('menu-access-check:D1');
    Route::get('/board/write-list', [BoardController::class, 'writeList'])->name('admin.board.write.list')->middleware('menu-access-check:D2');
    Route::put('/board/write-delete-status-update', [BoardController::class, 'writeDeleteStatusUpdate'])->name('admin.board.write.delete-status-update')->middleware('menu-access-check:D2');
    Route::get('/board/comment-list', [BoardController::class, 'commentList'])->name('admin.board.comment.list')->middleware('menu-access-check:D3');
    Route::put('/board/comment-delete-status-update', [BoardController::class, 'commentDeleteStatusUpdate'])->name('admin.board.comment.delete-status-update')->middleware('menu-access-check:D3');
    Route::get('/board/inquiry-list', [BoardController::class, 'inquiryList'])->name('admin.board.inquiry.list')->middleware('menu-access-check:D4');
    Route::put('/board/inquiry-answer', [BoardController::class, 'inquiryAnswer'])->name('admin.board.inquiry.answer')->middleware('menu-access-check:D4');

    /** 포인트 */
    Route::get('/point/setting', [PointController::class, 'setting'])->name('admin.point.setting')->middleware('menu-access-check:E1');
    Route::post('/point/setting', [PointController::class, 'settingUpdate'])->name('admin.point.setting.update')->middleware('menu-access-check:E1');
    Route::get('/point/list', [PointController::class, 'list'])->name('admin.point.list')->middleware('menu-access-check:E2');
    Route::post('/point/create', [PointController::class, 'create'])->name('admin.point.create')->middleware('menu-access-check:E2');
});
