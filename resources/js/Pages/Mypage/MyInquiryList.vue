<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from "@/Layouts/AppLayout.vue";
import MyHeader from "@/Pages/Mypage/MyHeader.vue";
import ButtonColor from '@/Components/ButtonColor.vue';
import Modal from '@/Components/Modal.vue';
import MyInquiryCreate from '@/Pages/Mypage/MyInquiryCreate.vue';
import SpeedDial from '@/Components/SpeedDial.vue';
import Mypoint from "@/Pages/Mypage/Mypoint.vue";
import { PaginatedInquiryList, Category } from "@/types/board";
import { formatRelativeTime } from "@/utils/helper";

const page = usePage();

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    inquiries: PaginatedInquiryList;
    inquiryCategories: Category[];
}>();

const listMore = async () => {
    try {
        const response = await axios.get(route('mypage.inquiry.list.more'), {
            params: {
                page: props.inquiries.current_page + 1,
            }
        });
        if (response.data.inquiries) {
            props.inquiries.data.push(...response.data.inquiries.data);
            props.inquiries.current_page = response.data.inquiries.current_page;
            props.inquiries.next_page_url = response.data.inquiries.next_page_url;
        }
    } catch (error) {
        console.error('데이터를 불러오는 중 오류가 발생했습니다:', error);
    }
}

const isShowModal = ref(false);
const isShowPointModal = ref(false);
const handleModalClose = (value: boolean) => {
    isShowModal.value = value;
    isShowPointModal.value = value;
};

const openInquiryId = ref<number | null>(null);

const toggleInquiry = (inquiryId: number) => {
    openInquiryId.value = openInquiryId.value === inquiryId ? null : inquiryId;
};
</script>

<template>
    <Head title="마이페이지" />
    <main class="w-full max-w-4xl mx-auto text-md">
        <MyHeader :pageTab="'inquiry'" />
        <div class="board-wirte-list">
            <div class="space-y-3">
                <div class="flex justify-end">
                    <ButtonColor @click="isShowModal = true" class="inline-flex w-full px-3 py-[8.1px] items-center justify-center sm:w-auto" :color="'blue'">
                        <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>1:1 문의 등록
                    </ButtonColor>
                </div>
                <div class="border border-gray-200 rounded-lg dark:border-gray-700">
                    <template v-for="(inquiry, index) in props.inquiries.data" :key="inquiry.id">
                        <div class="border-b last:border-b-0 dark:border-gray-700">
                            <button 
                                @click="toggleInquiry(inquiry.id)"
                                class="flex items-center justify-between w-full p-5 text-left transition-colors hover:bg-gray-50 dark:hover:bg-gray-800"
                            >
                                <div class="flex flex-col items-start gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 rounded dark:bg-gray-800 dark:text-gray-300 border border-gray-500">
                                            {{ inquiry.category }}
                                        </span>
                                        <span v-if="inquiry.status" class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 rounded dark:bg-gray-800 dark:text-gray-300 border border-gray-500">답변완료</span>
                                        <span v-else class="px-2.5 text-sm font-medium text-center text-blue-700 bg-blue-100 border border-blue-400 rounded 
                                        dark:bg-gray-700 dark:text-blue-400">미답변</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ formatRelativeTime(inquiry.created_at) }}</span>
                                    </div>
                                    <span class="text-gray-900 dark:text-gray-300 text-base font-semibold">
                                        {{ inquiry.subject }}
                                    </span>
                                </div>
                                <Icon icon="chevron-up" class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': openInquiryId === inquiry.id }" />
                            </button>
                            <div v-show="openInquiryId === inquiry.id" class="px-5 py-3 bg-gray-50 dark:bg-gray-800">
                                <div class="text-gray-500 dark:text-white whitespace-pre-wrap">
                                    <span v-html="inquiry.content" class="[&>img]:my-[7px]"></span>
                                </div>
                                <template v-if="inquiry.status">
                                    <div class="mt-7">
                                        <p class="flex items-center space-x-2 mb-2 font-medium text-gray-900 dark:text-white">
                                            <span class="font-medium text-red-500 dark:text-red-600 bg-gray-700 dark:bg-gray-300 rounded-full">
                                                <Icon icon="circle-letter-a" class="w-5 h-5" />
                                            </span>
                                            <span class="font-medium">{{ inquiry.answer.nickname }}</span>
                                            <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                                            <span class="text-gray-600 dark:text-gray-400">{{ formatRelativeTime(inquiry.answered_at) }}</span>
                                        </p>
                                        <p class="text-gray-500 dark:text-white whitespace-pre-wrap">
                                            <span v-html="inquiry.answer_content" class="[&>img]:my-[7px]"></span>
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div v-if="props.inquiries.next_page_url" class="flex justify-center mt-3">
            <ButtonColor 
                @click="listMore" 
                class="inline-flex w-full px-5 py-[9px] text-md font-medium text-center justify-center" 
                :color="'alternative'"
            >
                더보기
            </ButtonColor>
        </div>
        <div class="fixed right-3 bottom-[23px] z-200 lg:hidden">
            <div class="absolute bottom-0 flex flex-col justify-center items-end gap-[8px] right-0 xl:right-[-60px] w-max-content">
                <SpeedDial :purpose="'mypage'" @handleModalClose="handleModalClose" />
            </div>
        </div>
    </main>
    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'1:1 문의 등록'">
        <MyInquiryCreate @handleModalClose="handleModalClose" :categories="props.inquiryCategories" />
    </Modal>
    <Modal v-if="isShowPointModal" @handleModalClose="handleModalClose" :size="'lg'" :position="'center'" :title="'포인트 내역'">
        <Mypoint @handleModalClose="handleModalClose" />
    </Modal>
</template>
