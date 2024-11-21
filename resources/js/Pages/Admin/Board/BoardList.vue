<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { notify } from "@/Components/Toastify";
import { router } from '@inertiajs/vue3';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import ButtonColor from "@/Components/ButtonColor.vue";
import BoardCreate from "@/Pages/Admin/Board/BoardCreate.vue";
import BoardUpdate from "@/Pages/Admin/Board/BoardUpdate.vue";
import Modal from "@/Components/Modal.vue";
import { Board } from "@/types/board";

defineOptions({
    layout: DashboardLayout,
});

const page = usePage();
const props = defineProps<{
    boards: Board[];
}>();

const isShowModal = ref(false);
const isShowModifyModal = ref(false);
const selectedBoard = ref<Board | null>(null);

const handleModalClose = (value: boolean, refresh: boolean = false) => {
    isShowModal.value = value;
    isShowModifyModal.value = value;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
    }
};

const openModifyModal = (board: Board) => {
    selectedBoard.value = board;
    isShowModifyModal.value = true;
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 block sm:flex items-center justify-between bg-white dark:bg-gray-900 text-md">
        <div class="w-full">
            <Breadcrumb menu-name="게시판 관리" current-location="게시판 리스트" />
            <div class="sm:flex">
                <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                    <ButtonColor @click="isShowModal = true" class="inline-flex w-full px-3 py-[8.1px] items-center justify-center sm:w-auto" :color="'blue'">
                        <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>게시판 생성
                    </ButtonColor>
                </div>
            </div>
            <div class="inline-block min-w-full align-middle mt-3">
                <div class="overflow-hidden shadow-sm rounded-md">
                    <table class="min-w-full table-fixed divide-y divide-gray-300 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">게시판명</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">게시판ID</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">스킨</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">카테고리</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">댓글</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">추천</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">신고</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">태그</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">쓰기</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">사용여부</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">게시글수</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">댓글수</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">-</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(board, bidx) in boards" :key="bidx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.table_name }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.table_id }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.skin }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.use_category === true ? '사용' : '사용안함' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.use_comment === true ? '사용' : '사용안함' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.use_rate === true ? '사용' : '사용안함' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.use_report === true ? '사용' : '사용안함' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.use_tags === true ? '사용' : '사용안함' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.write_level >= 11 ? '관리자' : '회원' }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div v-if="board.status" class="flex items-center justify-center"><div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>Active</div>
                                    <div v-else class="flex items-center justify-center"><div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>Inactive</div>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.article_count.toLocaleString() }}개</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ board.comment_count.toLocaleString() }}개</td>
                                <td class="p-3 text-center space-x-2 whitespace-nowrap">
                                    <a :href="`/bbs/${board.table_id}/list`" target="_blank" class="inline-flex text-white bg-green-800 hover:bg-green-900 font-medium rounded-md px-3 py-[5px] text-center dark:bg-green-800 dark:hover:bg-green-900 items-center">
                                        <Icon icon="forward" class="w-4 h-4 mr-1 -ml-1"/>이동
                                    </a>
                                    <ButtonColor @click="openModifyModal(board)" class="inline-flex w-full px-3 py-[5px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                                        <Icon icon="edit" class="w-4 h-4 mr-1 -ml-1"/>수정
                                    </ButtonColor>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'게시판 등록'">
        <BoardCreate @handleModalClose="handleModalClose" />
    </Modal>

    <Modal v-if="isShowModifyModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'게시판 수정'">
        <BoardUpdate @handleModalClose="handleModalClose" :board="selectedBoard" />
    </Modal>

</template>
