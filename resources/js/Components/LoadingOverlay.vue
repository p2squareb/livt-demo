<script setup lang="ts">
import { defineProps } from 'vue';

defineProps({
    isLoading: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="p-4 text-center rounded-md shadow-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 mr-2 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path d="M12 2a10 10 0 0110 10h-2a8 8 0 10-8 8v-2a6 6 0 110-12z" fill="currentColor"></path>
            </svg>
            <p class="text-md font-medium text-gray-900 dark:text-white pl-3"><slot /></p>
        </div>
    </div>
</template>
