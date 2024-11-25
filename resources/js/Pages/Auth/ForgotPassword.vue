<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/Include/ApplicationLogo.vue";

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <main>
        <div class="flex flex-col items-center justify-center px-6 pt-8 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
            <Link href="/" class="flex items-center justify-center mb-3 font-semibold lg:mb-5 dark:text-white"><ApplicationLogo /></Link>

            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800">
                <div class="w-full p-6 sm:p-8">
                    <h2 class="mb-3 text-xl font-bold text-gray-900 dark:text-white">비밀번호 찾기</h2>
                    <p class="text-md font-normal text-gray-700 dark:text-gray-300">
                        가입 시 등록하신 이메일 주소를 입력해 주세요. 비밀번호 재설정 링크를 보내드립니다.
                    </p>
                    <form @submit.prevent="submit">
                        <div class="mt-4">
                            <InputLabel for="email" value="이메일" class="block mb-2" />
                            <TextInput type="email" id="email" v-model="form.email" class="block w-full py-[6px]" autofocus required placeholder="이메일을 입력해주세요." />
                            <InputError :message="form.errors.email" />
                        </div>
                        <ButtonColor class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'blue'" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            비밀번호 재설정 링크 받기
                        </ButtonColor>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>
