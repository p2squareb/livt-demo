<script setup lang="ts">
import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import { notify } from "@/Components/Toastify";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import TextInput from '@/Components/TextInput.vue';

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    nickname: '',
    email: '',
    password: '',
    password_confirmation: '',
    profile: null as File | null
});

const previewImage = ref<string | null>(null);

const submit = () => {
    form.post(route('admin.user.create'), {
        onSuccess: () => {
            closeModal(true);
        },
    });
};

const profileUpload = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        form.profile = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
}

const profileDelete = () => {
    form.profile = null;
    previewImage.value = null;
    const fileInput = document.getElementById('profile') as HTMLInputElement;
    if (fileInput) {
        fileInput.value = '';
    }
}
</script>

<template>
    <form @submit.prevent="submit">
        <div class="px-6 py-3 space-y-3">
            <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                <img v-if="previewImage" :src="previewImage" class="rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0" alt="Profile Preview">
                <label v-else for="profile" class="flex flex-col items-center justify-center w-28 h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-20 h-20 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                        </svg>
                    </div>
                </label>
                <div>
                    <h3 class="mb-3 text-xm font-bold text-gray-900 dark:text-white">프로필 이미지</h3>
                    <div class="mb-4 text-md text-gray-500 dark:text-gray-400">
                        JPG, GIF or PNG. Max size of 3MB
                    </div>
                    <div class="flex items-center space-x-4">
                        <ButtonColor v-if="previewImage" type="button" @click="profileDelete" class="inline-flex w-full px-3 py-[7px] text-md font-medium items-center justify-center sm:w-auto" :color="'gray'">
                            <svg class="w-3 h-3 mr-2 -ml-1 mb-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                            이미지 삭제
                        </ButtonColor>
                        <ButtonColor v-else type="button" onclick="document.getElementById('profile').click()" class="inline-flex w-full px-3 py-[7px] text-md font-medium items-center justify-center sm:w-auto" :color="'teal'">
                            <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
                                <path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path>
                            </svg>
                            이미지 업로드
                        </ButtonColor>
                        <input id="profile" accept="image/*" type="file" class="hidden" @change="profileUpload"/>
                    </div>
                    <InputError :message="form.errors.profile" />
                </div>
            </div>
        
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="email" value="이메일" class="block mb-2" />
                    <TextInput type="email" id="email" v-model="form.email" class="block w-full py-[6px]" required placeholder="이메일을 입력해주세요."/>
                    <InputError :message="form.errors.email" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="nickname" value="닉네임" class="block mb-2" />
                    <TextInput type="text" id="nickname" v-model="form.nickname" class="block w-full py-[6px]" required placeholder="영어|한글|숫자 (2~8자)" />
                    <InputError :message="form.errors.nickname" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="password" value="비밀번호" class="block mb-2" />
                    <TextInput type="password" id="password" v-model="form.password" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                    <InputError :message="form.errors.password" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="password_confirmation" value="비밀번호 확인" class="block mb-2" />
                    <TextInput type="password" id="password_confirmation" v-model="form.password_confirmation" class="block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>
        </div>
        <div class="p-6 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-3 py-[7px] text-md font-medium items-center justify-center sm:w-auto" @click="closeModal(false)" :color="'gray'">
                    <svg class="w-3 h-3 mr-2 -ml-1 mb-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                    닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-3 py-[7px] text-md font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <svg class="w-5 h-5 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    등록하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>
