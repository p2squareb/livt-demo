<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/Form/InputError.vue";

const page = usePage();

const profileForm = useForm({
    profile: null as File | null,
})

const profileUpload = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        profileForm.profile = file;
        profileForm.post(route('profile.image.upload'));
    }
}

const profileDelete = () => {
    profileForm.delete(route('profile.image.destroy'));
}
</script>

<template>
    <div class="items-center flex space-x-4 pb-[1px]">
        <img v-if="page.props.auth.user.profile_photo_path" :src="`/storage/profiles/${page.props.auth.user.profile_photo_path}`" class="rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0" :alt="`/storage/profiles/${page.props.auth.user.profile_photo_path}`">
        <label v-if="!page.props.auth.user.profile_photo_path" for="profile" class="flex flex-col items-center justify-center w-28 h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-20 h-20 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                </svg>
            </div>
        </label>
        <div class="-mt-1">
            <h3 class="flex items-center mb-1 text-base font-bold text-gray-900 dark:text-white">
                {{ $page.props.auth.user.email }}
                <Link :href="route('profile.edit')">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>
                    </svg>
                </Link>
            </h3>
            <div class="mb-1 text-md text-gray-700 dark:text-gray-300">
                {{ $page.props.auth.user.nickname }} | 포인트 {{ $page.props.auth.user.point }}<span class="text-xs ml-0.5">P</span>
            </div>
            <div class="mb-1 text-md text-gray-700 dark:text-gray-300">
                Member Since <span class="ml-1">{{ dayjs(page.props.auth.user.created_at).format('YYYY.MM.DD') }}</span>
            </div>
            <div class="flex items-center space-x-4">
                <button v-if="page.props.auth.user.profile_photo_path" @click="profileDelete" type="button" class="inline-flex items-center py-[7px] px-3 text-md font-medium text-gray-900 focus:outline-none bg-white rounded-md border border-gray-300 hover:bg-gray-100 hover:text-blue-700 focus:z-10 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
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
</template>