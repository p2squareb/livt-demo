<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import { notify } from '@/Components/Toastify';

const props = defineProps<{
    writerId: number;
    targetId: number;
    targetType: string;
}>();

const page = usePage();

const confirmingReport = ref(false);

const confirmReport = () => {
    confirmingReport.value = true;
};

const closeReportModal = () => {
    confirmingReport.value = false;
};

const reportSubmit = () => {
    router.post(route('write.report.create'), {
        targetId: props.targetId,
        targetType: props.targetType,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeReportModal();
            notify(page.props.flash.status, page.props.flash.message);
        }
    });
};
</script>

<template>
    <p 
        v-if="writerId !== $page.props.auth.user?.id && $page.props.auth.user" 
        @click="confirmReport"
        class="flex items-center cursor-pointer"
    >
        <Icon icon="report" :class="'w-5 h-5 text-gray-700 dark:text-gray-300'"/>
        <span class="ml-1 dark:text-gray-100">신고</span>
    </p>

    <DialogModal :show="confirmingReport" :max-width="'sm'" @close="closeReportModal">
        <div class="p-6 bg-white dark:bg-gray-800 flex flex-col items-center">
            <h2 class="text-base font-medium">해당 글을 신고하시겠습니까?</h2>
            <div class="mt-3 flex justify-center">
                <ButtonColor @click="closeReportModal" class="mt-2 mr-3 px-5 py-[7px] text-md font-medium text-center" :color="'gray'">취소하기</ButtonColor>
                <ButtonColor @click="reportSubmit" class="mt-2 px-5 py-[7px] text-md font-medium text-center" :color="'blue'">신고하기</ButtonColor>
            </div>
        </div>
    </DialogModal>
</template>
