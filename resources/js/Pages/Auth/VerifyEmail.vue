<script setup lang="ts">
import {ref} from "vue";
import {useForm} from '@inertiajs/vue3';
import ButtonColor from '@/Components/ButtonColor.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import LoadingOverlay from "@/Components/LoadingOverlay.vue";

const isLoading = ref(false);
const form = useForm({});

const submit = () => {
    isLoading.value = true;
    form.post(route('verification.send'), {
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <Head title="이메일 인증" />
    <main>
        <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen dark:bg-gray-900">
            <Link href="/" class="flex items-center justify-center mb-3 font-semibold lg:mb-5 dark:text-white"><ApplicationLogo /></Link>
            <div class="w-full max-w-md mb-7 px-3 py-6 sm:p-6 bg-white rounded-md shadow dark:bg-gray-800">
                <div v-if="$page.props.auth.user.status === 4" class="flex items-center mb-3 justify-center text-lg font-semibold dark:text-white">휴면회원 이메일 인증</div>
                <div v-else class="flex items-center mb-3 justify-center text-lg font-semibold dark:text-white">회원 이메일 인증</div>
                <form @submit.prevent="submit">
                    <div>
                        <label v-if="$page.props.auth.user.status === 4" class="block text-md font-medium text-gray-900 dark:text-white leading-6">
                            오랫동안 미접속으로 인해 휴면회원으로 전환되셨습니다.<br>이메일 인증을 통해 휴면 상태를 변경해주세요.
                        </label>
                        <label v-else class="block text-md font-medium text-gray-900 dark:text-white leading-6">
                            가입시 등록하신 이메일로 인증 메일이 발송되었습니다.<br>이메일을 받지 못하셨다면 재발송 버튼을 클릭해주세요.
                        </label>
                    </div>
                    <ButtonColor class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'blue'" :disabled="form.processing">인증 이메일 재발송</ButtonColor>
                </form>
                <Link :href="route('logout')" method="post" as="button" class="w-full mt-3 px-5 py-[7px] text-base font-medium text-center text-white bg-red-700 rounded-md hover:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700">로그아웃</Link>
            </div>
        </div>
        <LoadingOverlay :isLoading="isLoading">인증 이메일을 발송 중입니다.</LoadingOverlay>
    </main>
</template>
