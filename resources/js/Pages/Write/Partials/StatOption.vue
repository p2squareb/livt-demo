<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import { formatNumberWithK } from "@/utils/helper";
import { Write, Board } from "@/types/board";

const props = defineProps<{
    write: Write;
    board: Board;
}>();

const page = usePage();

const handleStatUpdate = (option: boolean) => {
    if (!page.props.auth.user) return;
    if (Number(page.props.auth.user.id) === Number(props.write.user_id)) return;

    router.post(
        route('write.rate.update'), 
        {
            targetType: 'writes', 
            targetId: props.write.id, 
            option 
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const handleBookmark = () => {
    if (!page.props.auth.user) return;
    router.post(route('write.bookmark.create'), 
        {
            targetId: props.write.id,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <p 
        v-if="board.use_rate" 
        class="inline-flex items-center text-gray-600 dark:text-gray-400 cursor-pointer" 
        :class="{ 'text-green-500 dark:text-green-500': write.user_rate_up, }"
        @click="handleStatUpdate(true)"
    >
        <svg class="w-5 h-5 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M15.03 9.684h3.965c.322 0 .64.08.925.232.286.153.532.374.717.645a2.109 2.109 0 0 1 .242 1.883l-2.36 7.201c-.288.814-.48 1.355-1.884 1.355-2.072 0-4.276-.677-6.157-1.256-.472-.145-.924-.284-1.348-.404h-.115V9.478a25.485 25.485 0 0 0 4.238-5.514 1.8 1.8 0 0 1 .901-.83 1.74 1.74 0 0 1 1.21-.048c.396.13.736.397.96.757.225.36.32.788.269 1.211l-1.562 4.63ZM4.177 10H7v8a2 2 0 1 1-4 0v-6.823C3 10.527 3.527 10 4.176 10Z" clip-rule="evenodd"/>
        </svg>{{ formatNumberWithK(write.rate_up) }}
    </p>
    <p 
        v-if="board.use_rate" 
        class="inline-flex items-center text-gray-600 dark:text-gray-400 cursor-pointer"
        :class="{ 'text-red-500 dark:text-red-500': write.user_rate_down }"
        @click="handleStatUpdate(false)"
    >
        <svg class="w-5 h-5 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M8.97 14.316H5.004c-.322 0-.64-.08-.925-.232a2.022 2.022 0 0 1-.717-.645 2.108 2.108 0 0 1-.242-1.883l2.36-7.201C5.769 3.54 5.96 3 7.365 3c2.072 0 4.276.678 6.156 1.256.473.145.925.284 1.35.404h.114v9.862a25.485 25.485 0 0 0-4.238 5.514c-.197.376-.516.67-.901.83a1.74 1.74 0 0 1-1.21.048 1.79 1.79 0 0 1-.96-.757 1.867 1.867 0 0 1-.269-1.211l1.562-4.63ZM19.822 14H17V6a2 2 0 1 1 4 0v6.823c0 .65-.527 1.177-1.177 1.177Z" clip-rule="evenodd"/>
        </svg>{{ formatNumberWithK(write.rate_down) }}
    </p>
    <p v-if="board.use_comment" class="inline-flex items-center text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M3 5.983C3 4.888 3.895 4 5 4h14c1.105 0 2 .888 2 1.983v8.923a1.992 1.992 0 0 1-2 1.983h-6.6l-2.867 2.7c-.955.899-2.533.228-2.533-1.08v-1.62H5c-1.105 0-2-.888-2-1.983V5.983Zm5.706 3.809a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Zm2.585.002a1 1 0 1 1 .003 1.414 1 1 0 0 1-.003-1.414Zm5.415-.002a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Z" clip-rule="evenodd"/>
        </svg>{{ formatNumberWithK(write.comment_count) }}
    </p>
    <p class="inline-flex items-center text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M4.998 7.78C6.729 6.345 9.198 5 12 5c2.802 0 5.27 1.345 7.002 2.78a12.713 12.713 0 0 1 2.096 2.183c.253.344.465.682.618.997.14.286.284.658.284 1.04s-.145.754-.284 1.04a6.6 6.6 0 0 1-.618.997 12.712 12.712 0 0 1-2.096 2.183C17.271 17.655 14.802 19 12 19c-2.802 0-5.27-1.345-7.002-2.78a12.712 12.712 0 0 1-2.096-2.183 6.6 6.6 0 0 1-.618-.997C2.144 12.754 2 12.382 2 12s.145-.754.284-1.04c.153-.315.365-.653.618-.997A12.714 12.714 0 0 1 4.998 7.78ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
        </svg>{{ formatNumberWithK(write.hit) }}
    </p>
    <p 
        @click="handleBookmark"
        class="inline-flex items-center cursor-pointer"
        :class="{ 'text-blue-500 dark:text-blue-500': write.user_bookmark, 'text-gray-400 dark:text-gray-400': !write.user_bookmark }"
    >
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7.833 2c-.507 0-.98.216-1.318.576A1.92 1.92 0 0 0 6 3.89V21a1 1 0 0 0 1.625.78L12 18.28l4.375 3.5A1 1 0 0 0 18 21V3.889c0-.481-.178-.954-.515-1.313A1.808 1.808 0 0 0 16.167 2H7.833Z"/>
        </svg>
    </p>
</template>
