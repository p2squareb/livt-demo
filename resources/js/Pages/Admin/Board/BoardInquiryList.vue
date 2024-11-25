<script setup lang="ts">
import { ref, watch } from "vue";
import { router, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import debounce from 'lodash.debounce';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Selectbox from "@/Components/Form/Selectbox.vue";
import PaginationLinks from "@/Components/PaginationLinks.vue";
import BoardInquiryRead from "@/Pages/Admin/Board/BoardInquiryRead.vue";
import Modal from "@/Components/Modal.vue";
import { Category, Inquiry,PaginatedInquiryList } from "@/types/board";
import { notify } from '@/Components/Toastify';

defineOptions({
    layout: DashboardLayout,
});
const page = usePage();

const props = defineProps<{
    inquiryCategories: Category[];
    inquiries: PaginatedInquiryList;
    request: Record<string, string>;
}>();

const pageRows = ref(props.request.pageRows || props.inquiries.per_page.toString());
const category = ref(props.request.category || '');
const is_answer = ref(props.request.is_answer || '');
const searchString = ref(props.request.searchString || '');
const updateSearch = () => {
    router.get(route('admin.board.inquiry.list'), {
        pageRows: pageRows.value,
        category: category.value,
        is_answer: is_answer.value,
        searchString: searchString.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
const debouncedUpdateSearch = debounce(updateSearch, 500);
watch([pageRows, category, is_answer], updateSearch);

const isShowModifyModal = ref(false);
const selectedInquiry = ref<Inquiry | null>(null);
const openModifyModal = (inquiry: Inquiry) => {
    selectedInquiry.value = inquiry;
    isShowModifyModal.value = true;
};
const handleModalClose = (value: boolean, refresh: boolean = false) => {
    isShowModifyModal.value = value;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
    }
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 block sm:flex items-center justify-between text-md">
        <div class="w-full">
            <Breadcrumb menu-name="게시판 관리" current-location="1:1 문의" />
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
                                v-model="category"
                                :placeholder="'문의 유형 선택'"
                                :options="props.inquiryCategories.map(cat => ({ value: cat.category, name: cat.category }))" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="is_answer" 
                                :placeholder="'답변상태'"  
                                :options="[{value: 'on', name: '답변완료'}, {value: 'off', name: '답변대기'}]" 
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
                                placeholder="제목+내용+문의자 입력해주세요."
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
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">문의유형</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">제목</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">문의자</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">문의일</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">답변상태</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">답변자</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">답변일</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(inquiry, widx) in inquiries.data" :key="widx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ inquiry.category }}</td>
                                <td class="p-3 text-left max-w-xl truncate whitespace-nowrap text-gray-900 dark:text-white">
                                    <span @click="openModifyModal(inquiry)" class="cursor-pointer">{{ inquiry.subject }}</span>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ inquiry.user.nickname }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ dayjs(inquiry.created_at).format('YYYY.MM.DD hh:mm') }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <span v-if="inquiry.status" class="text-gray-700 dark:text-gray-300">답변완료</span>
                                    <span v-else class="text-green-800 dark:text-green-400">답변대기</span>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <span>{{ inquiry.status ? inquiry.answer.nickname : '-' }}</span>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <span>{{ inquiry.status ? dayjs(inquiry.answered_at).format('YYYY.MM.DD hh:mm') : '-' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <PaginationLinks :paginator="inquiries" />

    <Modal v-if="isShowModifyModal" @handleModalClose="handleModalClose" :size="'2xl'" :position="'center'" :title="'1:1 문의 상세'">
        <BoardInquiryRead @handleModalClose="handleModalClose" :inquiry="selectedInquiry" />
    </Modal>
</template>
