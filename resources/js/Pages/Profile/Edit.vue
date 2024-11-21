<script setup lang="ts">
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import ProfileImage from './Partials/ProfileImage.vue';
import SpeedDial from '@/Components/SpeedDial.vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({
    layout: AppLayout,
});

defineProps<{
    mustVerifyEmail?: boolean;
    email_verified_at?: string;
    status?: string;
}>();
</script>

<template>
    <Head title="프로필" />
    <main class="w-full max-w-xl mx-auto text-md">
        <div class="flex flex-col items-center pt-3 mx-auto dark:bg-gray-900">
            <div class="flex items-center justify-center text-lg font-semibold dark:text-white mb-3">프로필 이미지</div>
            <div class="w-full min-w-[330px] sm:w-[400px] p-4 mb-7 bg-white border border-gray-200 rounded-md shadow-sm  dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <ProfileImage />
            </div>
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :email_verified_at="email_verified_at"
                :status="status"
                class="max-w-xl"
            />
            <UpdatePasswordForm class="max-w-xl" />
            <DeleteUserForm class="max-w-xl" />
        </div>
    </main>
</template>
