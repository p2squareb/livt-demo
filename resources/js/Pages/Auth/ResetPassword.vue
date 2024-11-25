<script setup lang="ts">
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/Include/ApplicationLogo.vue";

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="비밀번호 재설정" />
    <main>
        <div class="flex flex-col justify-center items-center px-6 pt-8 mx-auto md:h-screen pt:mt-0">
            <Link href="/" class="flex items-center justify-center mb-3 font-semibold lg:mb-5 dark:text-white"><ApplicationLogo /></Link>

            <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800">
                <div class="w-full p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">비밀번호 재설정</h2>
                    <form @submit.prevent="submit" class="mt-4 space-y-5">
                        <div>
                            <InputLabel for="email" value="이메일" class="block mb-2" />
                            <TextInput type="email" id="email" v-model="form.email" class="block w-full py-[6px]" required placeholder="이메일을 입력해주세요." />
                            <InputError :message="form.errors.email" />
                        </div>
                        <div>
                            <InputLabel for="password" value="새 비밀번호" class="block mb-2" />
                            <TextInput type="password" id="password" ref="passwordInput" v-model="form.password" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                            <InputLabel for="password_confirmation" value="새 비밀번호 확인" class="block mb-2" />
                            <TextInput type="password" id="password_confirmation" v-model="form.password_confirmation" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                            <InputError :message="form.errors.password" />
                        </div>
                        <ButtonColor class="w-full mt-3 px-5 py-[7px] font-medium text-center" :color="'blue'" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">비밀번호 재설정</ButtonColor>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>
