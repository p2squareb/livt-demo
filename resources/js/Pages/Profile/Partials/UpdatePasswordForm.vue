<script setup lang="ts">
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <div>
        <div class="flex items-center justify-center text-lg font-semibold mb-3">비밀번호 변경</div>
        <div class="w-full min-w-[330px] sm:w-[400px] mb-7 p-6 bg-white rounded-md shadow dark:bg-gray-800">
            <form @submit.prevent="updatePassword">
                <div class="-mt-2">
                    <InputLabel for="current_password" value="현재 비밀번호" class="block mb-2" />
                    <TextInput type="password" id="current_password" ref="currentPasswordInput" v-model="form.current_password" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                    <InputError :message="form.errors.current_password" />
                </div>
                <div>
                    <InputLabel for="password" value="새 비밀번호" class="block mb-2" />
                    <TextInput type="password" id="password" ref="passwordInput" v-model="form.password" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                    <InputError :message="form.errors.password" />
                </div>
                <div>
                    <InputLabel for="password_confirmation" value="새 비밀번호 확인" class="block mb-2" />
                    <TextInput type="password" id="password_confirmation" v-model="form.password_confirmation" class="mb-2 block w-full py-[6px]" required placeholder="대문자+숫자 포함 (8~16자)" />
                </div>

                <ButtonColor class="w-full mt-3 px-5 py-[7px] font-medium text-center" :color="'blue'" :disabled="form.processing">비밀번호 변경하기</ButtonColor>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="mt-2 font-medium text-green-600">비밀번호가 변경되었습니다!.</p>
                </Transition>
            </form>
        </div>
    </div>
</template>
