<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import { notify } from "@/Components/Toastify";
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import LoadingOverlay from "@/Components/Include/LoadingOverlay.vue";
import { User, UserProhibit, UserDormant } from "@/types/user";

const props = defineProps<{
    userInfo: User | null;
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

interface UserInfoExtra {
    userProhibit: UserProhibit | null;
    userDormant: UserDormant | null;
    userWriteCount: number;
    userCommentCount: number;
    userInquiryCount: number;
}
const userInfoExtra = ref<UserInfoExtra | null>(null);
onMounted(async () => {
    const response = await axios.get(route('admin.user.info-extra'), {params: {userId: props.userInfo?.id,}});
    userInfoExtra.value = response.data
});

const isLoading = ref(false);
const sendTempPassword = async () => {
    try {
        isLoading.value = true;
        await axios.post(route('admin.user.send-temp-password'), {userId: props.userInfo?.id});
        isLoading.value = false;
        notify('success', '임시 비밀번호가 발송되었습니다.');
    } catch (error) {
        notify('error', '임시 비밀번호 발송에 실패하였습니다.');
    }
}
</script>

<template>
    <div class="px-6 py-3 space-y-3">
        <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
            <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                <img v-if="props.userInfo?.profile_photo_path" :src="`/storage/profiles/${props.userInfo.profile_photo_path}`" class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0">
                <label v-else for="profile" class="flex flex-col items-center justify-center w-28 h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-20 h-20 text-gray-800 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                    </div>
                </label>
            </div>
            <div class="items-center sm:ml-8 text-gray-900 dark:text-white text-md">
                <div class="grid grid-cols-8 gap-0.5">
                    <div class="col-span-8 sm:col-span-8 font-semibold text-green-700 dark:text-green-600">
                        {{ props.userInfo?.nickname }} ({{ props.userInfo?.email }})
                    </div>
                    <div class="col-span-8 sm:col-span-5">
                        회원그룹 : {{ props.userInfo?.group.name }}
                    </div>
                    <div class="col-span-8 sm:col-span-3">
                        포인트 : {{ props.userInfo?.point.toLocaleString() }} pt
                    </div>
                    <div class="col-span-8 sm:col-span-5">
                        가입일 : {{ dayjs(props.userInfo?.created_at).format('YYYY.MM.DD') }}
                    </div>
                    <div class="col-span-8 sm:col-span-3">
                        가입유형 : {{ props.userInfo?.provider.toUpperCase() }}
                    </div>
                    <div class="col-span-8 sm:col-span-5">
                        접속일 : {{ dayjs(props.userInfo?.last_login_at).format('YYYY.MM.DD HH:mm')}}
                    </div>
                    <div class="col-span-8 sm:col-span-3">
                        IP : {{ props.userInfo?.login_ip || '미접속' }}
                    </div>
                    <div class="col-span-8 sm:col-span-8">
                        <span class="mr-1">상태 :</span>
                        <div 
                            :class="`h-2.5 w-2.5 rounded-full mr-1 ml-1 inline-block 
                                ${props.userInfo?.status === 1 ? 'bg-green-400' : 
                                props.userInfo?.status === 2 ? 'bg-red-500' : 
                                props.userInfo?.status === 3 ? 'bg-orange-400' : 
                                'bg-neutral-500'}`"
                        ></div>
                        {{ props.userInfo?.status === 1 ? '가입' : props.userInfo?.status === 2 ? '탈퇴' : props.userInfo?.status === 3 ? '정지' : '휴면' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-6 text-gray-900 dark:text-white text-md">
        <div class="grid grid-cols-6 gap-3">
            <div class="col-span-6 sm:col-span-6" v-if="props.userInfo?.status === 2">
                탈퇴일자 : {{ dayjs(props.userInfo?.leaved_at).format('YYYY.MM.DD HH:mm') }}
            </div>
            <div class="col-span-6 sm:col-span-6" v-else-if="props.userInfo?.status === 3">
                정지일자 : {{ dayjs(userInfoExtra?.userProhibit?.created_at).format('YYYY.MM.DD HH:mm') }} / 해제 예정일 : {{ dayjs(userInfoExtra?.userProhibit?.until_date).format('YYYY.MM.DD HH:mm') }}
            </div>
            <div class="col-span-6 sm:col-span-6" v-else-if="props.userInfo?.status === 4">
                휴면일자 : {{ dayjs(userInfoExtra?.userDormant?.created_at).format('YYYY.MM.DD HH:mm') }}
            </div>
            <div class="col-span-6 sm:col-span-6 items-center">
                <ButtonColor @click="sendTempPassword" class="inline-flex w-full px-3 py-[6px] text-md font-medium items-center justify-center sm:w-auto" :color="'alternative'">
                    <Icon icon="envelope" class="w-4 h-4 mr-2 -ml-1" />
                    임시 비밀번호 발급
                </ButtonColor>
                <span class="ml-2 text-sm text-yellow-700 dark:text-yellow-600">(회원 이메일로 임시 비밀번호가 발송됩니다.)</span>
            </div>
            <div class="col-span-6 sm:col-span-6 items-center justify-center space-x-2">
                <Link :href="`${route('admin.board.write.list')}?userId=${props.userInfo?.id}`" class="inline-flex w-full px-3 py-[6px] text-md font-medium items-center justify-center sm:w-auto rounded-md text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    게시글 ({{ userInfoExtra?.userWriteCount }}건)
                </Link>
                <Link :href="`${route('admin.board.comment.list')}?userId=${props.userInfo?.id}`" class="inline-flex w-full px-3 py-[6px] text-md font-medium items-center justify-center sm:w-auto rounded-md text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    댓글 ({{ userInfoExtra?.userCommentCount }}건)
                </Link>
                <Link :href="`${route('admin.board.inquiry.list')}?userId=${props.userInfo?.id}`" class="inline-flex w-full px-3 py-[6px] text-md font-medium items-center justify-center sm:w-auto rounded-md text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    1:1 문의 ({{ userInfoExtra?.userInquiryCount }}건)
                </Link>
                <Link :href="`${route('admin.point.list')}?userId=${props.userInfo?.id}`" class="inline-flex w-full px-3 py-[6px] text-md font-medium items-center justify-center sm:w-auto rounded-md text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    포인트 내역 ({{ props.userInfo?.point.toLocaleString() }}pt)
                </Link>
            </div>
        </div>
    </div>
    <div class="p-6 mt-3 rounded-b border-gray-200 border-t dark:border-gray-600">
        <div class="flex justify-end">
            <ButtonColor type="button" class="inline-flex w-full px-3 py-[8.1px] text-md font-medium items-center justify-center sm:w-auto" @click="closeModal(false)" :color="'gray'">
                <svg class="w-3 h-3 mr-2 -ml-1 mb-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>
                닫기
            </ButtonColor>
        </div>
    </div>
    <LoadingOverlay :isLoading="isLoading">인증 이메일을 발송 중입니다.</LoadingOverlay>
</template>
