<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {ref} from "vue";
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import ApplicationLogo from "@/Components/Include/ApplicationLogo.vue";
import Checkbox from "@/Components/Form/Checkbox.vue";
import LoadingOverlay from "@/Components/Include/LoadingOverlay.vue";

const isLoading = ref(false);

const form = useForm({
    nickname: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
    privacy: false,
});

const submit = () => {
    isLoading.value = true;
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <Head title="회원가입" />
    <main>
        <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
            <Link href="/" class="flex items-center justify-center mb-3 font-semibold lg:mb-5 dark:text-white"><ApplicationLogo /></Link>
            <div class="w-full max-w-md p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-md shadow border border-gray-300 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">딱 이것만 체크하면 가입완료!</h2>
                <form class="mt-5 space-y-5" @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="이메일" class="block mb-2" />
                        <TextInput type="email" id="email" v-model="form.email" class="block w-full py-[6px]" required placeholder="이메일을 입력해주세요." />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="nickname" value="닉네임" class="block mb-2" />
                        <TextInput type="text" id="nickname" v-model="form.nickname" class="block w-full py-[6px]" required placeholder="영어|한글|숫자 (2~8자)" />
                        <InputError :message="form.errors.nickname" />
                    </div>
                    <div>
                        <InputLabel for="password" value="비밀번호" class="block mb-2" />
                        <TextInput type="password" id="password" v-model="form.password" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                        <TextInput type="password" id="password_confirmation" v-model="form.password_confirmation" class="block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                        <InputError :message="form.errors.password" />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                    <div>
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <Checkbox id="terms" name="terms" v-model:checked="form.terms" />
                            </div>
                            <div class="ml-3 text-md">
                                <label for="terms" class="font-medium text-gray-900 dark:text-white">
                                    <Link :href="route('site-policy', {id: 'terms'})" class="text-blue-700 hover:underline dark:text-blue-500">서비스 이용약관</Link>에 동의합니다.
                                </label>
                            </div>
                        </div>
                        <div class="flex items-start mt-2">
                            <div class="flex items-center h-5">
                                <Checkbox id="privacy" name="privacy" v-model:checked="form.privacy" />
                            </div>
                            <div class="ml-3 text-md">
                                <label for="privacy" class="font-medium text-gray-900 dark:text-white">
                                    <Link :href="route('site-policy', {id: 'privacy'})" class="text-blue-700 hover:underline dark:text-blue-500">개인정보 수집/이용</Link>에 동의합니다.{{ form.errors.terms }}
                                </label>
                            </div>
                        </div>
                        <InputError :message="form.errors.terms" />
                        <InputError :message="form.errors.privacy" />
                    </div>
                    <ButtonColor class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'blue'" :disabled="form.processing">회원가입</ButtonColor>
                    <div class="text-md font-medium text-gray-500 dark:text-gray-400">
                        이미 계정을 가지고 계신가요? <Link :href="route('login')" class="text-blue-700 hover:underline dark:text-blue-500">로그인하러 가기</Link>
                    </div>
                </form>
            </div>
        </div>
        <LoadingOverlay :isLoading="isLoading">인증 이메일을 발송 중입니다.</LoadingOverlay>
    </main>
</template>
