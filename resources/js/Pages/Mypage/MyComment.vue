<script setup lang="ts">
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from "@/Layouts/AppLayout.vue";
import MyHeader from "@/Pages/Mypage/MyHeader.vue";
import ProfileIcon from "@/Components/ProfileIcon.vue";
import ButtonColor from '@/Components/ButtonColor.vue';
import SpeedDial from '@/Components/SpeedDial.vue';
import Modal from "@/Components/Modal.vue";
import Mypoint from "@/Pages/Mypage/Mypoint.vue";
import { PaginatedCommentList } from "@/types/board";
import { formatRelativeTime } from "@/utils/helper";

const page = usePage();

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    comments: PaginatedCommentList;
}>();

const commentMore = async () => {
    try {
        const response = await axios.get(route('write.comment.list.more', { tableId: 'none', writeId: 'none' }), {
            params: {
                page: props.comments.current_page + 1,
                userId: page.props.auth.user.id,
            }
        });
        console.log(response);
        if (response.data.comments) {
            props.comments.data.push(...response.data.comments.data);
            props.comments.current_page = response.data.comments.current_page;
            props.comments.next_page_url = response.data.comments.next_page_url;
        }
    } catch (error) {
        console.error('데이터를 불러오는 중 오류가 발생했습니다:', error);
    }
}

const isShowPointModal = ref(false);
const handleModalClose = (value: boolean) => {
    isShowPointModal.value = value;
};
</script>

<template>
    <Head title="마이페이지" />
    <main class="w-full max-w-4xl mx-auto text-md">
        <MyHeader :pageTab="'comment'" />
        <div class="board-comment-list">
            <div class="space-y-3">
                <article class="shadow-md">
                    <div class="pb-3 border-b-2 border-gray-300">
                        <h3 class="text-lg font-semibold"><span v-if="comments.total > 0">{{ comments.total }}</span> Comment</h3>
                    </div>
                    <div class="mt-4 pb-4 border-b border-gray-700">
                        <ul class="space-y-5">
                            <li v-for="(comment, cidx) in props.comments.data" :key="cidx" class="relative space-y-5 pl-9">
                                <div class="flex items-start">
                                    <div class="absolute top-0 left-0">
                                        <ProfileIcon :profilePhotoPath="comment.user?.profile_photo_path" />
                                    </div>
                                    <div class="w-full">
                                        <div @click="router.visit(route('write.read', { tableId: comment.write.table_id, writeId: comment.write.id }))" class="grow space-y-2 mb-2 cursor-pointer">
                                            <div class="flex items-center justify-start">
                                                <p>
                                                    {{ comment.writer }} 
                                                    <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                                                    <span class="text-gray-600 dark:text-gray-400 text-sm">{{ formatRelativeTime(comment.created_at) }}</span>
                                                </p>
                                            </div>   
                                            <p v-if="comment.is_delete" class="whitespace-pre-line text-gray-700 dark:text-gray-300">[삭제된 댓글입니다]</p>
                                            <p v-else class="whitespace-pre-line" v-html="comment.comment" />
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div v-if="comments.next_page_url" class="text-center mt-5">
                            <ButtonColor 
                                @click="commentMore" 
                                class="inline-flex w-full px-5 py-[9px] text-md font-medium text-center justify-center" 
                                :color="'alternative'"
                            >
                                댓글 더보기
                            </ButtonColor>
                        </div>
                    </div>
                </article>
            </div>
        </div>
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
