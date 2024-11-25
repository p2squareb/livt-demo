<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ProfileIcon from "@/Components/Include/ProfileIcon.vue";
import StatOption from "./StatOption.vue";
import { formatRelativeTime } from "@/utils/helper";
import { Write, Board } from "@/types/board";

const props = defineProps<{
    write: Write;
    board: Board;
    searchTag: string;
}>();

const emit = defineEmits(['tagClick']);

const handleTagClick = (tag: string) => {
    emit('tagClick', tag);
};
</script>

<template>
    <article class="py-6 px-3 md:px-5 border border-1 border-gray-300 dark:border-gray-700 rounded-md">
        <div v-if="board.use_tags && write.tags" class="flex items-center flex-wrap">
            <span 
                v-for="(tag, idx) in write.tags.split(',')" 
                :key="idx" 
                @click="handleTagClick(tag)"
                class="bg-blue-100 text-blue-800 text-md font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 hover:bg-blue-200 dark:hover:bg-blue-300 dark:text-blue-800 mb-2 cursor-pointer"
                :class="{ 'bg-yellow-200 hover:bg-yellow-300 dark:bg-yellow-300 dark:hover:bg-yellow-400': tag === searchTag }"
            >
                #{{ tag }}
            </span>
        </div>
        <h2 class="mb-3 text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white hover:underline">
            <Link 
                :href="route('write.read', { tableId: board.table_id, writeId: write.id })"
                preserve-scroll
            >
                {{ write.subject }}
            </Link>
        </h2>
        <p class="mb-3 text-gray-500 dark:text-gray-400 line-clamp-3 sm:text-md text-sm">
            <Link 
                :href="route('write.read', { tableId: board.table_id, writeId: write.id })"
                preserve-scroll
            >
                {{ write.content.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim() }}
            </Link>
        </p>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                <ProfileIcon :profilePhotoPath="write.user?.profile_photo_path" />
                <span class="font-medium dark:text-white">{{ write.writer }}</span>
                <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                <span class="text-gray-600 dark:text-gray-400">{{ formatRelativeTime(write.created_at) }}</span>
                <template v-if="board.use_category">
                    <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                    <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 rounded dark:bg-gray-800 dark:text-gray-300 border border-gray-500">
                        {{ write.categories }}
                    </span>
                </template>
            </div>
            <div class="flex flex-wrap items-center font-medium text-blue-600 dark:text-blue-500 space-x-3 justify-end sm:justify-start">
                <StatOption :board="board" :write="write" />
            </div>
        </div>
    </article>
</template>
