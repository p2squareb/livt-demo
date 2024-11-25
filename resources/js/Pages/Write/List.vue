<script setup lang="ts">
import axios from 'axios';
import { ref, watch } from "vue";
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import SearchOption from "@/Pages/Write/Partials/SearchOption.vue";
import Card from "@/Pages/Write/Partials/Card.vue";
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import { Board, PaginatedWriteList } from "@/types/board";

defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    board: Board;
    writes: PaginatedWriteList;
    isWrite: boolean;
    request: Record<string, string>;
}>();

const category = ref(props.request.category || '');
const searchString = ref(props.request.searchString || '');
const searchTag = ref(props.request.searchTag || '');
const sortBy = ref(props.request.sortBy || 'new');

const updateSearch = () => {
    router.get(route('write.list', { tableId: props.board.table_id }), {
        category: category.value,
        searchString: searchString.value,
        searchTag: searchTag.value,
        sortBy: sortBy.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

watch([category, searchString, searchTag, sortBy], updateSearch);

const handleTagClick = (tag: string) => {
    searchTag.value = tag;
    updateSearch();
};

const listMore = async () => {
    try {
        const response = await axios.get(route('write.list.more', { tableId: props.board.table_id }), {
            params: {
                category: category.value,
                searchString: searchString.value,
                searchTag: searchTag.value,
                sortBy: sortBy.value,
                page: props.writes.current_page + 1,
            }
        });

        if (response.data.writes) {
            props.writes.data.push(...response.data.writes.data);
            props.writes.current_page = response.data.writes.current_page;
            props.writes.next_page_url = response.data.writes.next_page_url;
        }
    } catch (error) {
        console.error('데이터를 불러오는 중 오류가 발생했습니다:', error);
    }
}
</script>

<template>
    <div class="w-full max-w-4xl mx-auto text-md">
        <div class="pb-3">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                <Link :href="route('write.list', {tableId: props.board.table_id})" class="hover:text-blue-500">{{ props.board.table_name }}</Link>
            </h1>
        </div>
        <div class="board-wirte-list">
            <SearchOption 
                :boardUseCategory="props.board.use_category" 
                :boardCategory="props.board.category_list" 
                v-model:category="category"
                v-model:searchString="searchString"
                v-model:sortBy="sortBy"
            />
            <template v-if="props.board.skin === 'card'">
                <div class="space-y-3">
                    <Card 
                        v-for="(write, idx) in props.writes.data" 
                        :key="idx" 
                        :write="write" 
                        :board="props.board"
                        :searchTag="searchTag"
                        @tagClick="handleTagClick"
                    />
                </div>
            </template>
        </div>
        <div v-if="$page.props.auth.user && $page.props.auth.user.group_level >= props.board.write_level" class="sticky bottom-[23px] z-200">
            <div class="absolute bottom-0 flex flex-col justify-center items-end gap-[8px] right-0 xl:right-[-60px] w-max-content">
                <button @click="router.get(route('write.create', {tableId: props.board.table_id}))" class="flex items-center justify-center text-white hover:text-blue-500 rounded-full w-12 h-12 bg-blue-700 hover:bg-stone-900 border border-gray-900 hover:border-blue-500 focus:outline-none">
                    <Icon icon="pencil" class="w-5 h-5" />
                </button>
            </div>
        </div>
        <div v-if="props.writes.next_page_url" class="flex justify-center mt-3">
            <ButtonColor 
                @click="listMore" 
                class="inline-flex w-full px-5 py-[9px] text-md font-medium text-center justify-center" 
                :color="'alternative'"
            >
                더보기
            </ButtonColor>
        </div>
    </div>
</template>
