<script setup lang="ts">
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    email_verified_at?: String;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    nickname: user.nickname,
    email: user.email,
});
</script>

<template>
    <div>
        <div class="flex items-center justify-center text-lg font-semibold dark:text-white mb-3">회원정보</div>
        <div class="w-full min-w-[330px] sm:w-[400px] mb-7 p-6 bg-white rounded-md shadow dark:bg-gray-800">
            <form @submit.prevent="form.patch(route('profile.update'))">
                <div class="-mt-2">
                    <InputLabel for="email" value="이메일" class="block mb-2" />
                    <div>{{ user.email }}</div>
                </div>
                <div class="mt-3">
                    <InputLabel for="nickname" value="닉네임" class="block mb-2" />
                    <TextInput type="text" id="nickname" v-model="form.nickname" class="block w-full py-[6px]" required placeholder="닉네임 입력해주세요." />
                    <InputError :message="form.errors.nickname" />
                </div>
                <div v-if="mustVerifyEmail && email_verified_at === null">
                    <p class="mt-2">
                        이메일이 인증되지 않았습니다.
                        <Link :href="route('verification.send')" method="post" as="button" class="rounded-md underline hover:text-gray-900 dark:hover:text-gray-300">
                            클릭하시면 인증 이메일이 재발송됩니다.
                        </Link>
                    </p>
                    <div v-show="status === 'verification-link-sent'" class="mt-2 font-medium text-green-600">이메일 주소로 새로운 인증 링크가 전송되었습니다.</div>
                </div>
                <ButtonColor class="w-full mt-3 px-5 py-[7px] font-medium text-center" :color="'blue'" :disabled="form.processing">회원정보 수정하기</ButtonColor>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="mt-2 font-medium text-green-600">회원정보가 수정되었습니다!.</p>
                </Transition>
            </form>
        </div>
    </div>
</template>
