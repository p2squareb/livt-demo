<script setup lang="ts">
import { ref, watch } from "vue";
import { router, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import debounce from 'lodash.debounce';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import ButtonColor from "@/Components/ButtonColor.vue";
import Selectbox from "@/Components/Form/Selectbox.vue";
import PaginationLinks from "@/Components/PaginationLinks.vue";
import { PaginatedCommentList, Board } from "@/types/board";
import BoardReportList from "@/Pages/Admin/Board/BoardReportList.vue";
import Modal from "@/Components/Modal.vue";
import { formatNumberWithK } from "@/utils/helper";
import { notify } from '@/Components/Toastify';

defineOptions({
    layout: DashboardLayout,
});
const page = usePage();

const props = defineProps<{
    boards: Board[];
    comments: PaginatedCommentList;
    request: Record<string, string>;
}>();

const pageRows = ref(props.request.pageRows || props.comments.per_page.toString());
const tableId = ref(props.request.tableId || '');
const is_delete = ref(props.request.is_delete || '');
const is_report = ref(props.request.is_report || '');
const searchString = ref(props.request.searchString || '');
const updateSearch = () => {
    router.get(route('admin.board.comment.list'), {
        pageRows: pageRows.value,
        tableId: tableId.value,
        is_delete: is_delete.value,
        is_report: is_report.value,
        searchString: searchString.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
const debouncedUpdateSearch = debounce(updateSearch, 500);
watch([pageRows, tableId, is_delete, is_report], updateSearch);

const isDeleteStatusUpdate = (commentId: number, operation: 'delete' | 'restore') => {
    router.put(route('admin.board.comment.delete-status-update'), {
        commentId: commentId,
        operation: operation,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            notify(page.props.flash.status, page.props.flash.message);
        }
    });
};

const isShowModal = ref(false);
const reportTargetId = ref<number | null>(null);

const handleModalClose = (value: boolean) => {
    isShowModal.value = value;
};

const openReportModal = (writeId: number) => {
    isShowModal.value = true;
    reportTargetId.value = writeId;
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 block sm:flex items-center justify-between text-md">
        <div class="w-full">
            <Breadcrumb menu-name="게시판 관리" current-location="댓글 관리" />
            <div class="sm:flex justify-between">
                <div class="items-center mr-0 sm:mr-2 mb-3 sm:flex sm:space-x-2 sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="pageRows" 
                                :options="[{value: '15', name: '15개'}, {value: '30', name: '30개'}, {value: '50', name: '50개'}, {value: '100', name: '100개'}]" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="tableId"
                                :placeholder="'게시판명'"
                                :options="boards.map(brd => ({ value: brd.table_id, name: brd.table_name }))"
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="is_delete" 
                                :placeholder="'게시상태'"  
                                :options="[{value: '1', name: '삭제'}]" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="is_report" 
                                :placeholder="'신고상태'"  
                                :options="[{value: '1', name: '신고'}]" 
                            />
                        </div>
                    </div>
                </div>
                <div class="sm:flex items-center sm:space-x-2">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <input 
                                v-model="searchString" 
                                @input="debouncedUpdateSearch"
                                @keyup.enter="updateSearch"
                                type="search" 
                                id="searchString" 
                                class="block p-[6px] pl-[13px] w-full md:w-[270px] text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white focus:ring-0" 
                                placeholder="검색어를 입력해주세요."
                            />
                            <button 
                                type="button" 
                                @click="updateSearch"
                                class="absolute top-0 end-0 px-3 py-[7px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-0"
                            >
                                <Icon icon="search" class="w-4 h-4" />
                                <span class="sr-only">검색</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inline-block min-w-full align-middle mt-3">
                <div class="overflow-hidden shadow-sm rounded-md">
                    <table class="min-w-full table-fixed divide-y divide-gray-300 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">게시판명</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">내용</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">추천</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">신고</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">작성자</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">작성일</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">-</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(comment, cidx) in comments.data" :key="cidx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ comment.write.board.table_name }}
                                </td>
                                <td 
                                    class="p-3 text-left max-w-xl truncate whitespace-nowrap" 
                                    :class="{ 
                                        'text-gray-400 dark:text-gray-500 line-through': comment.is_delete, 
                                        'text-gray-900 dark:text-white': !comment.is_delete,
                                    }"
                                >
                                    <a :href="route('write.read', { tableId: comment.write.board.table_id, writeId: comment.write_id })" target="_blank">
                                        <span v-if="comment.is_delete">[삭제됨]</span>{{ comment.comment }}
                                    </a>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ formatNumberWithK(comment.rate_up) }} / {{ formatNumberWithK(comment.rate_down) }}
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <span v-if="comment.report_count > 0">
                                        <ButtonColor 
                                            @click="openReportModal(comment.id)"
                                            class="inline-flex w-full px-2 py-[2px] text-md font-medium items-center justify-center sm:w-auto" 
                                            :color="'yellow'"
                                        >
                                            {{ formatNumberWithK(comment.report_count) }}
                                        </ButtonColor>
                                    </span>
                                    <span v-else>0</span>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ comment.writer }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ dayjs(comment.created_at).format('YYYY.MM.DD hh:mm') }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <ButtonColor 
                                        v-if="comment.is_delete" 
                                        class="inline-flex w-full px-3 py-[5px] text-md font-medium items-center justify-center sm:w-auto" 
                                        :color="'teal'"
                                        @click="isDeleteStatusUpdate(comment.id, 'restore')"
                                    >
                                        <Icon icon="forward" class="w-4 h-4 mr-1 -ml-1" />복원
                                    </ButtonColor>
                                    <ButtonColor 
                                        v-else 
                                        class="inline-flex w-full px-3 py-[5px] text-md font-medium items-center justify-center sm:w-auto" 
                                        :color="'red'"
                                        @click="isDeleteStatusUpdate(comment.id, 'delete')"
                                    >
                                        <Icon icon="delete" class="w-4 h-4 mr-1" />삭제
                                    </ButtonColor>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <PaginationLinks :paginator="comments" />

    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'md'" :position="'center'" :title="'신고 내역'">
        <BoardReportList @handleModalClose="handleModalClose" :targetTable="'comments'" :targetId="reportTargetId" />
    </Modal>
</template>
