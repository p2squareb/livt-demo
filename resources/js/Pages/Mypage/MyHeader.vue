<script setup lang="ts">
import { ref } from 'vue';
import ProfileImage from "@/Pages/Profile/Partials/ProfileImage.vue";
import Modal from "@/Components/Modal.vue";
import Mypoint from "@/Pages/Mypage/Mypoint.vue";

defineProps<{
    pageTab: String;
}>();

const isShowPointModal = ref(false);
const handleModalClose = (value: boolean) => {
    isShowPointModal.value = value;
};
</script>

<template>
    <div class="grid grid-cols-1 pt-3 xl:grid-cols-6 xl:gap-3 dark:bg-gray-900">
        <div class="col-span-3">
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <ProfileImage />
            </div>
        </div>
        <div class="hidden lg:block col-span-3">
            <div class="flex flex-wrap gap-3 w-full text-md font-semibold dark:text-white">
                <Link :href="route('profile.edit')" class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border cursor-pointer"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'profile', 'border-blue-500': pageTab === 'profile'}" 
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="person" class="w-5 h-5 text-green-500 dark:text-green-500" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'profile'}">프로필 수정</span>
                    </div>
                </Link>

                <div class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border cursor-pointer"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'point', 'border-blue-500': pageTab === 'point'}"
                    @click="isShowPointModal = true"
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="point" class="w-5 h-5 text-yellow-500 dark:text-yellow-500" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'point'}">포인트</span>
                    </div>
                </div>

                <Link :href="route('mypage.inquiry.list')" class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border cursor-pointer"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'inquiry', 'border-blue-500': pageTab === 'inquiry'}"
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="inquiry" class="w-5 h-5" :class="{'text-gray-800 dark:text-gray-500': pageTab !== 'inquiry', 'text-blue-500': pageTab === 'inquiry'}" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'inquiry'}">1:1 문의</span>
                    </div>
                </Link>

                <Link :href="route('mypage.write')" class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'write', 'border-blue-500': pageTab === 'write'}"
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="clip-board" class="w-5 h-5" :class="{'text-gray-800 dark:text-gray-500': pageTab !== 'write', 'text-blue-500': pageTab === 'write'}" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'write'}">게시글</span>
                    </div>
                </Link>

                <Link :href="route('mypage.comment')" class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'comment', 'border-blue-500': pageTab === 'comment'}"
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="comment" class="w-5 h-5" :class="{'text-gray-800 dark:text-gray-500': pageTab !== 'comment', 'text-blue-500': pageTab === 'comment'}" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'comment'}">댓글</span>
                    </div>
                </Link>

                <Link :href="route('mypage.bookmark')" class="flex-1 basis-[30%] p-3 rounded-md shadow-sm dark:bg-gray-800 border"
                    :class="{'border-gray-300 dark:border-gray-700': pageTab !== 'bookmark', 'border-blue-500': pageTab === 'bookmark'}"
                >
                    <div class="flex flex-col items-center">
                        <Icon icon="bookmark" class="w-5 h-5" :class="{'text-gray-800 dark:text-gray-500': pageTab !== 'bookmark', 'text-blue-500': pageTab === 'bookmark'}" />
                        <span class="mt-2" :class="{'text-blue-500': pageTab === 'bookmark'}">북마크</span>
                    </div>
                </Link>
            </div>
        </div>
    </div>

    <Modal v-if="isShowPointModal" @handleModalClose="handleModalClose" :size="'lg'" :position="'center'" :title="'포인트 내역'">
        <Mypoint @handleModalClose="handleModalClose" />
    </Modal>
</template>
