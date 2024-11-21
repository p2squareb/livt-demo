<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import MyHeader from "@/Pages/Mypage/MyHeader.vue";
import MyLoginRecord from "@/Pages/Mypage/MyLoginRecord.vue";
import SpeedDial from '@/Components/SpeedDial.vue';
import Modal from "@/Components/Modal.vue";
import Mypoint from "@/Pages/Mypage/Mypoint.vue";
import { LoginRecord } from "@/types/user";

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    loginRecords: LoginRecord[] | null;
}>();

const isShowPointModal = ref(false);
const handleModalClose = (value: boolean) => {
    isShowPointModal.value = value;
};
</script>

<template>
    <Head title="마이페이지" />
    <main class="w-full max-w-4xl mx-auto text-md">
        <MyHeader :pageTab="'overview'" />
        <MyLoginRecord :loginRecords="props.loginRecords" />
        <div class="fixed right-3 bottom-[23px] z-200 lg:hidden">
            <div class="absolute bottom-0 flex flex-col justify-center items-end gap-[8px] right-0 xl:right-[-60px] w-max-content">
                <SpeedDial :purpose="'mypage'" @handleModalClose="handleModalClose" />
            </div>
        </div>
    </main>
    <Modal v-if="isShowPointModal" @handleModalClose="handleModalClose" :size="'lg'" :position="'center'" :title="'포인트 내역'">
        <Mypoint @handleModalClose="handleModalClose" />
    </Modal>
</template>
