<script setup lang="ts">
import axios from 'axios';
import dayjs from "dayjs";
import { onMounted, ref } from 'vue';
import { Report } from '@/types/board';

const props = defineProps<{
    targetTable: string;
    targetId: number | null;
}>();

const emit = defineEmits(['handleModalClose'])

const reports = ref<Report[]>([]);

onMounted(async () => {
    const response = await axios.get(`/admin/board/report/${props.targetTable}/${props.targetId}`);
    reports.value = response.data;
});
</script>

<template>
    <div class="px-4 inline-block min-w-full align-middle pt-5 pb-7 dark:text-white text-md">
        <div class="overflow-hidden shadow-sm rounded-md border border-gray-300 dark:border-gray-600">
            <table class="min-w-full table-fixed divide-y divide-gray-300 dark:divide-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-800 font-semibold">
                    <tr>
                        <th scope="col" class="p-[17px] text-center uppercase">번호</th>
                        <th scope="col" class="p-[17px] text-center uppercase">신고자</th>
                        <th scope="col" class="p-[17px] text-center uppercase">신고일시</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 dark:divide-gray-600">
                    <tr v-for="(report, ridx) in reports" :key="report.id" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-center">{{ ridx + 1 }}</td>
                        <td class="px-6 py-3 text-center">{{ report.user.nickname }}</td>
                        <td class="px-6 py-3 text-center">{{ dayjs(report.created_at).format('YYYY.MM.DD hh:mm') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
