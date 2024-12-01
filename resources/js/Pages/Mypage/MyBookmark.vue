<script setup lang="ts">
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import MyHeader from "@/Pages/Mypage/MyHeader.vue";
import ProfileIcon from "@/Components/Include/ProfileIcon.vue";
import StatOption from "@/Pages/Write/Partials/StatOption.vue";
import SpeedDial from '@/Components/SpeedDial.vue';
import Modal from "@/Components/Modal.vue";
import Mypoint from "@/Pages/Mypage/Mypoint.vue";
import { Bookmark } from "@/types/board";
import { formatRelativeTime } from "@/utils/helper";

const page = usePage();

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    bookmarks: Bookmark[];
}>();

const isShowPointModal = ref(false);
const handleModalClose = (value: boolean) => {
    isShowPointModal.value = value;
};
</script>

<template>
    <Head title="마이페이지" />
    <main class="w-full max-w-4xl mx-auto text-md">
        <MyHeader :pageTab="'bookmark'" />
        <div class="board-wirte-list">
            <div class="space-y-3">
                <article v-for="(bookmark, idx) in props.bookmarks" class="py-6 px-3 md:px-5 border border-1 border-gray-300 dark:border-gray-700 rounded-md">
                    <div v-if="bookmark.write.board.use_tags && bookmark.write.tags" class="flex items-center flex-wrap">
                        <span 
                            v-for="(tag, idx) in bookmark.write.tags.split(',')" 
                            :key="idx" 
                            class="bg-blue-100 text-blue-800 text-md font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 hover:bg-blue-200 dark:hover:bg-blue-300 dark:text-blue-800 mb-2 cursor-pointer"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                    <h2 class="mb-3 text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white hover:underline">
                        <Link 
                            :href="route('write.read', { tableId: bookmark.write.table_id, writeId: bookmark.write.id })"
                            preserve-scroll
                        >
                            {{ bookmark.write.subject }}
                        </Link>
                    </h2>
                    <p class="mb-3 text-gray-500 dark:text-gray-400 line-clamp-3 sm:text-md text-sm">
                        <Link 
                            :href="route('write.read', { tableId: bookmark.write.table_id, writeId: bookmark.write.id })"
                            preserve-scroll
                        >
                            {{ bookmark.write.content.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim() }}
                        </Link>
                    </p>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                            <ProfileIcon :profilePhotoPath="bookmark.write.user?.profile_photo_path" />
                            <span class="font-medium dark:text-white">{{ bookmark.write.writer }}</span>
                            <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ formatRelativeTime(bookmark.write.created_at) }}</span>
                            <template v-if="bookmark.write.board.use_category">
                                <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                                <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 rounded dark:bg-gray-800 dark:text-gray-300 border border-gray-500">
                                    {{ bookmark.write.categories }}
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-wrap items-center font-medium text-blue-600 dark:text-blue-500 space-x-3 justify-end sm:justify-start">
                            <StatOption :board="bookmark.write.board" :write="bookmark.write" />
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
