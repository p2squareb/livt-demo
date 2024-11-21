<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
    purpose: 'mypage' | 'write';
}>();

const emit = defineEmits(['handleModalClose']);

const isOpen = ref(false);
const speedDialRef = ref<HTMLElement | null>(null);

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const handleClickOutside = (event: MouseEvent) => {
    if (speedDialRef.value && !speedDialRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

const handlePointModalOpen = () => {
    emit('handleModalClose', true);
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="speedDialRef" class="relative flex flex-col items-end">
        <div class="absolute bottom-[calc(100%+0.5rem)] right-0 flex flex-col items-end space-y-2" :class="{ 'hidden': !isOpen }">
            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <Link :href="route('mypage.inquiry.list')"
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="inquiry" class="w-6 h-6" />
                </Link>
            </div>

            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <Link :href="route('mypage.bookmark')" 
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="bookmark" class="w-6 h-6" />
                </Link>
            </div>

            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <Link :href="route('mypage.comment')" 
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="comment" class="w-6 h-6" />
                </Link>
            </div>

            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <Link :href="route('mypage.write')"
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="clip-board" class="w-6 h-6" />
                </Link>
            </div>

            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <button type="button" 
                    @click="handlePointModalOpen"
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="point" class="w-8 h-8" />
                </button>
            </div>

            <div v-if="props.purpose === 'mypage'" class="flex items-center gap-2">
                <Link :href="route('mypage.overview')" 
                    class="flex justify-center items-center w-[48px] h-[48px] text-gray-500 hover:text-gray-900 bg-white rounded-full border border-gray-200 dark:border-gray-600 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none"
                >
                    <Icon icon="home" class="w-6 h-6" />
                </Link>
            </div>
        </div>

        <button type="button" 
            @click="toggleMenu"
            class="flex items-center justify-center text-white bg-blue-700 rounded-full w-12 h-12 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700"
        >
            <Icon :icon="'grid-plus'" class="w-6 h-6 transition-transform" :class="{ 'rotate-45': isOpen }" />

        </button>
    </div>
</template>