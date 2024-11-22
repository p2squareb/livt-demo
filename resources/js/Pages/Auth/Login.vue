<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import TextInput from '@/Components/TextInput.vue';
import {router, useForm} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
    socialite: {
        use_google_login: boolean;
        use_naver_login: boolean;
        use_kakao_login: boolean;
    };
}>();

const form = useForm({
    email: 'admin@gmail.com',
    password: '1234',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="로그인" />
    <main>
        <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
            <Link href="/" class="flex items-center justify-center mb-3 font-semibold lg:mb-5 dark:text-white"><ApplicationLogo /></Link>
            <div class="w-full max-w-md p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-md shadow border border-gray-300 dark:border-gray-700">
                <div v-if="$page.props.flash.error" class="flex items-center p-3 mb-3 text-red-800 border-t-4 border-red-300 bg-red-50 dark:text-red-400 dark:bg-gray-800 dark:border-red-800" role="alert">
                    <Icon icon="info-circle" class="flex-shrink-0 w-5 h-5" />
                    <div class="ms-3 text-sm font-medium">{{ $page.props.flash.error }}</div>
                </div>
                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="이메일" class="block mb-2" />
                        <TextInput type="email" id="email" v-model="form.email" class="block w-full py-[6px]" required placeholder="이메일을 입력해주세요." />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="mt-5">
                        <InputLabel for="password" value="비밀번호" class="block mb-2" />
                        <TextInput type="password" id="password" v-model="form.password" class="block w-full py-[6px]" required autocomplete="current-password" />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div class="flex items-start mt-5">
                        <div class="flex items-center h-5">
                            <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                        </div>
                        <div class="ml-3 text-md">
                            <label for="remember" class="font-medium text-gray-900 dark:text-white">로그인 상태 유지</label>
                        </div>
                        <Link :href="route('password.request')" class="ml-auto text-md text-blue-700 hover:underline dark:text-blue-500">비밀번호 찾기</Link>
                    </div>
                    <ButtonColor class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'blue'" :disabled="form.processing">로그인하기</ButtonColor>
                </form>
                <ButtonColor @click="router.visit(route('register'));" class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'red'">회원가입</ButtonColor>
                <div v-if="socialite.use_google_login || socialite.use_naver_login || socialite.use_kakao_login" class="space-y-5">
                    <div class="flex items-center my-5">
                        <div class="border-t border-gray-700 dark:border-gray-300 grow mr-3" aria-hidden="true"></div>
                        <div class="text-gray-700 dark:text-gray-400 italic text-md">SNS 간편 로그인</div>
                        <div class="border-t border-gray-700 dark:border-gray-300 grow ml-3" aria-hidden="true"></div>
                    </div>
                    <div v-if="socialite.use_google_login" class="flex flex-wrap -mx-3">
                        <div class="w-full px-3">
                            <a href="/auth/google/redirect" class="w-full px-5 py-[7.1px] font-medium text-center text-md rounded-md text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-300 flex items-center justify-center">
                                <img alt="google" src="/images/icon/google-logo.png" class="w-5 h-5 mr-2">
                                Login with Google
                            </a>
                        </div>
                    </div>
                    <div v-if="socialite.use_naver_login" class="flex flex-wrap -mx-3">
                        <div class="w-full px-3">
                            <a href="/auth/naver/redirect" class="w-full px-5 py-[8.1px] font-medium text-center text-md rounded-md text-white flex items-center justify-center" style="background-color: rgb(30, 180, 0);">
                                <img alt="naver" src="/images/icon/naver-logo.png" class="w-5 h-5 mr-2">
                                Login with Naver
                            </a>
                        </div>
                    </div>
                    <div v-if="socialite.use_kakao_login" class="flex flex-wrap -mx-3">
                        <div class="w-full px-3">
                            <a href="/auth/kakao/redirect" class="w-full px-5 py-[8.1px] font-medium text-center text-md rounded-md text-gray-900 flex items-center justify-center" style="background-color: rgb(249, 224, 0);">
                                <img alt="kakao" src="/images/icon/kakao-logo.png" class="w-5 h-5 mr-2">
                                Login with Kakao
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>