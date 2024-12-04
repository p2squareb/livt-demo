<?php

namespace App\Http\Controllers;

use App\Mail\TempPassword;
use App\Mail\ProhibitUserNotification;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserProhibit;
use App\Models\UserDormant;
use App\Services\BoardService;
use App\Services\FileService;
use App\Services\PointService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Services\MenuService;

class UserManageController extends Controller
{
    private BoardService $boardService;
    private FileService $fileService;
    private MenuService $menuService;

    public function __construct(BoardService $boardService, FileService $fileService, MenuService $menuService) 
    {
        $this->boardService = $boardService;
        $this->fileService = $fileService;
        $this->menuService = $menuService;
    }

    public function list(Request $request) 
    {
        $userList = $this->getUserList($request);
        $userGroupList = $this->getUserGroupList();

        return Inertia::render('Admin/User/UserList', [
            'userList' => $userList,
            'userGroupList' => $userGroupList,
            'request' => $request->all(),
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nickname' => ['required', 'string', 'min:2', 'max:8'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'max:16', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed', Password::defaults()],
            'profile' => ['nullable', 'sometimes', 'image', 'max:3072', 'mimes:jpeg,png,jpg,gif'],
        ], [
            'nickname.required' => '닉네임을 입력해 주세요.',
            'nickname.min' => '닉네임은 최소 2자 이상이어야 합니다.',
            'nickname.max' => '닉네임은 최대 8자까지 가능합니다.',
            'email.required' => '이메일을 입력해 주세요.',
            'email.email' => '유효한 이메일 형식을 입력해 주세요.',
            'email.lowercase' => '이메일은 소문자로 입력해야 합니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'password.required' => '비밀번호를 입력해 주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 16자 이하이어야 합니다.',
            'password.regex' => '비밀번호는 대문자와 숫자를 포함해야 합니다.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'profile.image' => '이미지 파일만 업로드 가능합니다.',
            'profile.mimes' => '이미지는 jpeg, png, jpg, gif 형식만 가능합니다.',
            'profile.max' => '최대 3MB 업로드 가능합니다.',
        ]);

        $insertData = [
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'provider' => 'email',
        ];

        if ($request->profile){
            $uploadFile = $this->fileService->uploadProfileFile($request->profile);
            $profileData = [
                'profile_photo_path' => $uploadFile['fileSource'],
            ];
            $insertData = array_merge($insertData, $profileData);
        }

        try{
            User::create($insertData);
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 회원이 등록되었습니다.',
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '회원 등록에 실패하였습니다.');
        }
    }

    public function read(Request $request, $userId) 
    {
        $userData = $this->getUserData($userId);

        return Inertia::render('Admin/User/UserRead', [
            'userInfo' => $userData['userInfo'],
            'userProhibit' => $userData['userProhibit'],
            'userDormant' => $userData['userDormant'],
        ]);
    }

    public function infoExtra(Request $request)
    {
        $userData = $this->getUserData($request->userId);
        return response()->json([
            'userProhibit' => $userData['userProhibit'],
            'userDormant' => $userData['userDormant'],
            'userWriteCount' => $this->boardService->getUserWriteCount($request->userId),
            'userCommentCount' => $this->boardService->getUserCommentCount($request->userId),
            'userInquiryCount' => $this->boardService->getUserInquiryCount($request->userId),
        ]);
    }

    public function search(Request $request)
    {
        if ($request->searchString == '') {
            return response()->json([]);
        }

        if ($request->fromWhere == 'point') {
            $users = User::select('id', 'nickname', 'email')->filter($request->only(['searchString']))->orderBy('id', 'desc')->get();
        } else {
            $users = User::select('id', 'nickname', 'email')->filter($request->only(['searchString']))->orderBy('id', 'desc')->limit(10)->get();
        }
        return response()->json($users);
    }

    public function getUserData($userId): array
    {
        $userInfo = User::with('group')->find($userId);
        $userProhibit = UserProhibit::where('user_id', $userId)->first();
        $userDormant = UserDormant::where('user_id', $userId)->first();

        return [
            'userInfo' => $userInfo,
            'userProhibit' => $userProhibit,
            'userDormant' => $userDormant,
        ];
    }

    public function getUserList(Request $request)
    {
        if (Auth::user()->group_level == 99) {
            $query = User::with('group')->whereNotIn('group_level', [99]);
        } else {
            $query = User::with('group')->whereNotIn('group_level', [11,99]);
        }

        $query->filter($request->only(['group', 'status', 'searchString']));

        $pageRows = $request->input('pageRows', 15);
        return $query->orderBy('id', 'desc')->paginate($pageRows)->withQueryString();
    }

    public function getUserGroupList()
    {
        if (Auth::user()->group_level == 99) {
            return UserGroup::where('level', '<', 99)->orderBy('level')->get();
        } else {
            return UserGroup::where('level', '<', 11)->orderBy('level')->get();
        }
    }

    public function changeGroup(Request $request)
    {
        try {
            User::whereIn('id', $request->selectedUsersIds)->update(['group_level' => $request->newGroup]);
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '선택한 회원들의 그룹이 변경되었습니다.',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', '그룹 변경에 실패하였습니다.');
        }
    }
    
    public function groupList(Request $request) 
    {
        $userGroup = UserGroup::withCount('users')->where('level', '<', 99)->get();

        return Inertia::render('Admin/User/UserGroupList', [
            'userGroups' => $userGroup,
        ]);
    }

    public function groupCreate(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'level' => ['required', 'unique:user_groups,level'],
        ], [
            'name.required' => '그룹명을 입력해주세요.',
            'level.required' => '레벨을 선택해주세요.',
            'level.unique' => '이미 사용중인 레벨입니다.'
        ]);

        $sortedMenus = $request->accessibleMenus;
        asort($sortedMenus);
        $accessibleMenus = implode(',', $sortedMenus);

        $insertData = [
            'name' => $request->name,
            'level' => $request->level,
            'comment' => $request->comment,
            'accessible_menus' => $accessibleMenus,
        ];

        try{
            UserGroup::create($insertData);
            $this->menuService->clearCache($request->level);

            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 그룹이 생성되었습니다.',
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '그룹 생성에 실패하였습니다.');
        }
    }

    public function groupUpdate(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'level' => ['required', Rule::unique('user_groups')->ignore($request->id)],
        ], [
            'name.required' => '그룹명을 입력해주세요.',
            'level.required' => '레벨을 선택해주세요.',
            'level.unique' => '이미 사용중인 레벨입니다.'
        ]);

        $sortedMenus = $request->accessibleMenus;
        asort($sortedMenus);
        $accessibleMenus = implode(',', $sortedMenus);
        
        $updateData = [
            'name' => $request->name,
            'level' => $request->level,
            'comment' => $request->comment,
            'accessible_menus' => $accessibleMenus,
        ];

        try{
            UserGroup::where('id', $request->id)->update($updateData);
            $this->menuService->clearCache($request->level);

            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 그룹이 수정되었습니다.',
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '그룹 수정에 실패하였습니다.');
        }
    }

    public function groupDelete(Request $request)
    {
        try{
            UserGroup::where('id', $request->groupId)->delete();
            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 그룹이 삭제되었습니다.',
            ]);
        }catch (Exception $e) {
            return redirect()->back()->with('error', '그룹 삭제에 실패하였습니다.');
        }
    }

    public function prohibitUpdate(Request $request)
    {
        $request->validate([
            'period_type' => ['required_if:gubun,1'],
            'cause' => ['required_if:gubun,1'],
        ], [
            'period_type.required_if' => '정지 기간을 선택해주세요.',
            'cause.required_if' => '정지 사유를 입력해주세요.',
        ]);

        try {
            DB::beginTransaction();
            if ($request->gubun === '1') {
                $users = User::whereIn('id', explode(',', $request->target_user_ids))->where('status', 1)->get();
                foreach($users as $user) {
                    $insertData = [
                        'user_id' => $user->id,
                        'gubun' => $request->gubun,
                        'period_type' => $request->period_type,
                        'until_date' => $request->until_date,
                        'cause' => $request->cause,
                        'processed_by_user_id' => 1,
                        'processed_by_user_nickname' => 'admin',
                    ];
                    UserProhibit::create($insertData);

                    User::where('id', $user->id)->update([
                        'status' => 3,
                    ]);

                    Mail::to($user->email)->send(new ProhibitUserNotification($user->nickname, $request->period_type, $request->until_date, $request->cause));
                }
            } elseif ($request->gubun === '0') {
                $users = User::whereIn('id', explode(',', $request->target_user_ids))->where('status', 3)->get();
                foreach($users as $user){
                    $updateData = [
                        'gubun' => 0,
                    ];
                    UserProhibit::where('user_id', $user->id)->update($updateData);

                    User::where('id', $user->id)->update([
                        'status' => 1,
                    ]);
                }
            }
            DB::commit();

            return redirect()->back()->with([
                'status' => 'success',
                'message' => '정상적으로 상태가 변경되었습니다.',
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', '상태 변경에 실패하였습니다.');
        }
    }

    public function sendTempPassword(Request $request)
    {
        try{
            $user = User::select('id', 'nickname', 'email')->find($request->userId);
    
            $tempPassword = Str::random(13);
            User::where('id', $user->id)->update([
                'password' => Hash::make($tempPassword),
            ]);
    
            Mail::to($user->email)->send(new TempPassword($user->nickname, $tempPassword));
        } catch (Exception $e) {
            return redirect()->back()->with('error', '임시 비밀번호 발송에 실패하였습니다.');
        }
    }
}
