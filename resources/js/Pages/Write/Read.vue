<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileIcon from "@/Components/ProfileIcon.vue";
import StatOption from "@/Pages/Write/Partials/StatOption.vue";
import Comment from "@/Pages/Write/Comment.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DialogModal from "@/Components/DialogModal.vue";
import ButtonColor from "@/Components/ButtonColor.vue";
import Report from "@/Pages/Write/Partials/Report.vue";
import { Board, Write, PaginatedCommentList } from "@/types/board";
import { formatRelativeTime, goBack } from "@/utils/helper";

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    board: Board;
    write: {
        data: Write;
        threeDotView: boolean;
    };
    comments: PaginatedCommentList; 
    request: Record<string, string>;
}>();

const confirmingWriteDeletion = ref(false);

const confirmWriteDeletion = () => {
    confirmingWriteDeletion.value = true;
};

const closeWriteDeletionModal = () => {
    confirmingWriteDeletion.value = false;
};

const deleteWrite = () => {
    router.put(route('write.delete', {tableId: props.board.table_id, writeId: props.write.data.id}), {}, {
        onSuccess: () => {
            router.visit(route('write.list', {tableId: props.board.table_id}));
        }
    });
};
</script>

<template>
    <div class="w-full max-w-4xl mx-auto text-md">
        <div class="pb-3 border-b border-1 border-gray-300 dark:border-gray-700">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                <Link :href="route('write.list', {tableId: props.board.table_id})" class="hover:text-blue-500">{{ props.board.table_name }}</Link>
            </h1>
        </div>
        <div class="board-write-read px-1 md:px-3">
            <article class="w-full py-6 mx-auto format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 not-format">
                    <div class="mb-2 text-xl font-extrabold leading-tight text-gray-900 lg:mb-6 dark:text-white flex items-center justify-between">
                        <p>{{ props.write.data.subject }}</p>
                        <Dropdown :align="'right'" :width="'24'" class="text-sm" v-if="$page.props.auth.user">
                            <template #trigger>
                                <button class="-mt-1 cursor-pointer">
                                    <Icon icon="dots-vertical" :class="'w-5 h-5 text-gray-700 dark:text-gray-300'"/>
                                </button>
                            </template>
                            <template #content>
                                <div class="flex flex-col px-4 py-2 space-y-2">
                                    <Report :writerId="props.write.data.user_id" :targetId="props.write.data.id" targetType="write" />
                                    <p v-if="props.write.data.user_id === $page.props.auth.user?.id">
                                        <Link :href="route('write.edit', {tableId: props.board.table_id, writeId: props.write.data.id})" class="flex items-center">
                                            <Icon icon="edit" class="w-5 h-5 mb-0.5 text-gray-700 dark:text-gray-300" />
                                            <span class="ml-1 dark:text-gray-100">수정</span>
                                        </Link>
                                    </p>
                                    <p v-if="props.write.data.user_id === $page.props.auth.user?.id" @click="confirmWriteDeletion" class="flex items-center cursor-pointer">
                                        <Icon icon="delete" class="w-5 h-5 text-gray-700 dark:text-gray-300" />
                                        <span class="ml-1 dark:text-gray-100">삭제</span>
                                    </p>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                    <div v-if="board.use_tags && write.data.tags" class="flex flex-wrap mb-4">
                        <span 
                            v-for="(tag, idx) in write.data.tags.split(',')" 
                            :key="idx" 
                            class="bg-blue-100 text-blue-800 text-md font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 hover:bg-blue-200 dark:hover:bg-blue-300 dark:text-blue-800 mb-2"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                    <div class="text-md lg:text-base inline-flex items-center">
                        <ProfileIcon :profilePhotoPath="write.data.user?.profile_photo_path" />
                        <span class="font-medium dark:text-white ml-2 text-md">{{ write.data.writer }}</span>
                        <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                        <span class="text-gray-600 dark:text-gray-400 text-md">{{ formatRelativeTime(write.data.created_at) }}</span>
                        <template v-if="board.use_category">
                            <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                            <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-[3px] rounded dark:bg-gray-800 dark:text-gray-300 border border-gray-500">
                                {{ write.data.categories }}
                            </span>
                        </template>
                    </div>
                </header>
                <div class="text-md lg:text-base">
                    <div v-html="write.data.content" class="[&>img]:my-[7px]"></div>
                </div>
                <div class="flex flex-wrap items-center font-medium mt-3 text-blue-600 dark:text-blue-500 space-x-3 justify-end">
                    <StatOption :write="write.data" :board="board" />
                </div>
                <section v-if="props.board.use_comment" class="comment-section mt-5">
                    <Comment :comments="props.comments" :useRate="props.board.use_rate" :tableId="props.board.table_id" :writeId="Number(write.data.id)" :writerId="write.data.user_id" />
                </section>
            </article>
        </div>
        <div class="sticky bottom-[23px] z-200">
            <div class="absolute bottom-0 flex flex-col justify-center items-end gap-[8px] right-0 xl:right-[-60px] w-max-content">
                <button @click="goBack" class="flex items-center justify-center text-white hover:text-blue-500 rounded-full w-12 h-12 bg-blue-700 hover:bg-stone-900 border border-gray-900 hover:border-blue-500 focus:outline-none">
                    <Icon icon="list" class="w-8 h-8" />
                </button>
            </div>
        </div>
    </div>

    <DialogModal :show="confirmingWriteDeletion" :max-width="'sm'" @close="closeWriteDeletionModal">
        <div class="p-6 bg-white dark:bg-gray-800 flex flex-col items-center">
            <h2 class="text-lg font-medium">게시글을 삭제하시겠습니까?</h2>
            <div class="mt-3 flex justify-center">
                <ButtonColor @click="closeWriteDeletionModal" class="mt-2 mr-3 px-5 py-[7px] text-md font-medium text-center" :color="'gray'">취소하기</ButtonColor>
                <ButtonColor @click="deleteWrite" class="mt-2 px-5 py-[7px] text-md font-medium text-center" :color="'blue'">삭제하기</ButtonColor>
            </div>
        </div>
    </DialogModal>
</template>