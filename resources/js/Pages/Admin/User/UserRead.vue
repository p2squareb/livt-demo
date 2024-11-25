<script setup lang="ts">
import axios from 'axios';
import { reactive } from "vue";
import { router } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import { notify } from "@/Components/Toastify";
import dayjs from "dayjs";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Selectbox from "@/Components/Form/Selectbox.vue";
import ButtonColor from "@/Components/Include/ButtonColor.vue";
import SearchInput from '@/Components/Form/SearchInput.vue';
import { User, UserProhibit, UserDormant } from "@/types/user";

defineOptions({
    layout: DashboardLayout,
});

const props = defineProps<{
    userInfo: User;
    userProhibit: UserProhibit;
    userDormant: UserDormant;
}>();

const handleSearch = () => searchUserSubmit();

const searchUserData = reactive({
    searchString: '',
    searchUserList: [] as User[],
    selectedUserId: '', 
});
const searchUserSubmit = async () => {
    if (searchUserData.searchString.length < 2) {
        searchUserData.searchUserList = [];
        return;
    }
    try {
        const response = await axios.get(route('admin.user.search'), {
            params: { searchString: searchUserData.searchString }
        });
        searchUserData.searchUserList = response.data;
    } catch (error) {
        console.error('Error searching users:', error);
        notify('error', '사용자 검색 중 오류가 발생했습니다.');
    }
};

const profileForm = useForm({
    profile: null as File | null
})
const profileUpload = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        profileForm.profile = file;
        profileForm.post(route('mypage.profile.upload'));
    }
}
const profileDelete = () => {
    profileForm.delete(route('mypage.profile.destroy'));
}
</script>

<template>
    <div class="px-4 py-6 bg-white block sm:flex items-center justify-between dark:bg-gray-900 text-md">
        <div class="w-full mb-3">
            <Breadcrumb menu-name="회원 관리" current-location="회원정보" />
            <div class="sm:flex justify-between mb-3">
                <div class="text-lg font-semibold text-gray-900 dark:text-white mt-3">
                    {{ props.userInfo.nickname }} ({{ props.userInfo.email }})
                </div>
                <div class="sm:flex items-center  sm:space-x-2">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <SearchInput
                                v-model="searchUserData.searchString" 
                                placeholder="이메일 or 닉네임을 입력해주세요." 
                                @search="handleSearch"
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="searchUserData.selectedUserId"
                                :placeholder="'회원을 선택해주세요.'"
                                :options="searchUserData.searchUserList.map(u => ({ value: u.id.toString(), name: u.nickname + `(${u.email})` }))"
                            />
                        </div>
                    </div>
                    <div class="hidden md:flex items-center mb-3 md:mb-0 dark:text-gray-700">|</div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor @click="router.visit(route('admin.user.list'));" class="inline-flex w-full px-3 py-[8.1px] items-center justify-center sm:w-auto" :color="'blue'">회원 목록으로 이동</ButtonColor>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:flex p-3 bg-white border border-gray-200 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                    <img v-if="props.userInfo.profile_photo_path" :src="`/storage/profiles/${props.userInfo.profile_photo_path}`" class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0">
                    <label v-else for="profile" class="flex flex-col items-center justify-center w-28 h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-20 h-20 text-gray-800 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                            </svg>
                        </div>
                    </label>
                    <div>
                        <h3 class="mb-1 text-xm font-bold text-gray-900 dark:text-white">프로필 이미지</h3>
                        <div class="mb-1 text-md text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF</div>
                        <div class="mb-1 text-md text-gray-500 dark:text-gray-400">Max. 128x128px</div>
                        <div class="flex items-center space-x-4">
                            <button v-if="props.userInfo.profile_photo_path" @click="profileDelete" type="button" class="inline-flex items-center py-[7px] px-3 text-md font-medium text-gray-900 focus:outline-none bg-white rounded-md border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:z-10 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                이미지 삭제
                            </button>
                            <button v-else type="button" onclick="document.getElementById('profile').click()" class="inline-flex items-center px-3 py-[7px] text-md font-medium text-center text-white rounded-md bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path><path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path></svg>
                                이미지 업로드
                            </button>
                            <input id="profile" accept="image/*" type="file" class="hidden" @change="profileUpload"/>
                        </div>
                        <InputError :message="profileForm.errors.profile" />
                    </div>
                </div>
                <div class="items-center sm:ml-8">
                    <div class="flex flex-col text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            이메일 : {{ props.userInfo.email }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            닉네임 : {{ props.userInfo.nickname }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            회원그룹 : {{ props.userInfo.group.name }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            포인트 : {{ props.userInfo.point.toLocaleString() }} pt
                        </div>
                    </div>
                </div>
                <div class="items-start sm:ml-8">
                    <div class="flex flex-col text-gray-900 dark:text-white">
                        <div class="flex items-center">
                            가입유형 : {{ props.userInfo.provider.toUpperCase() }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            가입일 : {{ dayjs(props.userInfo.created_at).format('YYYY.MM.DD') }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            최근 로그인 : {{ dayjs(props.userInfo.last_login_at).format('YYYY.MM.DD HH:mm') }} / {{ props.userInfo.login_ip }}
                        </div>
                        <div class="flex items-center mt-1.5">
                            <span class="mr-2">상태 :</span>
                            <template v-if="props.userInfo.status === 1">
                                <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2 ml-1"></div>가입
                            </template>
                            <template v-else-if="props.userInfo.status === 2">
                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2 ml-1"></div>탈퇴
                            </template>
                            <template v-else-if="props.userInfo.status === 3">
                                <div class="h-2.5 w-2.5 rounded-full bg-orange-400 mr-2 ml-1"></div>정지
                            </template>
                            <template v-else-if="props.userInfo.status === 4">
                                <div class="h-2.5 w-2.5 rounded-full bg-neutral-500 mr-2 ml-1"></div>휴면
                            </template>
                        </div>
                    </div>
                </div>
                <div class="items-center sm:ml-8">
                    <div class="flex flex-col text-gray-900 dark:text-white">
                        <div class="flex items-center mt-1">
                            <template v-if="props.userInfo.status === 2">
                                탈퇴일자 : {{ dayjs(props.userInfo.leaved_at).format('YYYY.MM.DD HH:mm') }}
                            </template>
                            <template v-else-if="props.userInfo.status === 3">
                                정지일자 : {{ dayjs(props.userProhibit.created_at).format('YYYY.MM.DD HH:mm') }} / 해제 예정일 : {{ dayjs(props.userProhibit.until_date).format('YYYY.MM.DD HH:mm') }}
                            </template>
                            <template v-else-if="props.userInfo.status === 4">
                                휴면일자 : {{ dayjs(props.userDormant.created_at).format('YYYY.MM.DD HH:mm') }}
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-7">
                <div class="">
                    <ul class="flex flex-wrap -mb-px text-base font-semibold text-center text-gray-500 border-b border-gray-200 dark:border-gray-600 dark:text-gray-400">
                        <li class="me-2">
                            <a href="#" aria-current="page" class="inline-block py-2 px-6 text-blue-600 bg-gray-100 rounded-t-md active dark:bg-blue-600 dark:text-white">게시글</a>
                        </li>
                        <li class="me-2">
                            <a href="#" class="inline-block py-2 px-6 rounded-t-md hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">댓글</a>
                        </li>
                        <li class="me-2">
                            <a href="#" class="inline-block py-2 px-6 rounded-t-md hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">1:1 문의</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block py-2 px-6 rounded-t-md hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">포인트 내역</a>
                        </li>
                        <li>
                            <a href="#" class="inline-block py-2 px-6 rounded-t-md hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">알림 내역</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
