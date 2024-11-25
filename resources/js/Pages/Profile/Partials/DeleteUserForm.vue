<script setup lang="ts">
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import ButtonColor from "@/Components/ButtonColor.vue";
import DialogModal from "@/Components/DialogModal.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div>
        <div class="flex items-center justify-center text-lg font-semibold mb-3">회원탈퇴</div>
        <div class="w-full min-w-[330px] sm:w-[400px] mb-7 p-6 bg-white rounded-md shadow dark:bg-gray-800">
            <div class="-mt-2">
                <label for="email" class="block font-medium">회원탈퇴시 모든 리소스와 데이터에 접근이 불가능합니다.</label>
            </div>
            <ButtonColor @click="confirmUserDeletion" class="w-full mt-5 px-5 py-[7px] text-base font-medium text-center" :color="'red'" :disabled="form.processing">회원 탈퇴하기</ButtonColor>
            <DialogModal :show="confirmingUserDeletion" :max-width="'md'" @close="closeModal">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <h2 class="text-lg font-medium">계정을 삭제하시겠습니까?</h2>
                    <p class="mt-1 dark:text-gray-300">
                        계정이 삭제되면 모든 리소스와 데이터가 삭제됩니다
                        영구적으로 삭제됩니다. 비밀번호를 입력하세요
                        계정을 영구적으로 삭제할지 확인합니다.
                    </p>
                    <div class="mt-6">
                        <InputLabel for="password" value="비밀번호" class="block" />
                        <TextInput type="password" id="password" ref="passwordInput" v-model="form.password" @keyup.enter="deleteUser" class="mt-2 block w-full py-[6px]" required />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                    <div class="mt-6 flex justify-end">
                        <ButtonColor @click="closeModal" class="mt-2 mr-2 px-5 py-[7px] text-md font-medium text-center" :color="'red'" :disabled="form.processing">취소하기</ButtonColor>
                        <ButtonColor @click="deleteUser" class="mt-2 px-5 py-[7px] text-md font-medium text-center" :color="'blue'" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">삭제하기</ButtonColor>
                    </div>
                </div>
            </DialogModal>
        </div>
    </div>
</template>
