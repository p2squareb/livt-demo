<script setup lang="ts">
defineProps<{
    menuName: string;
    currentLocation: string;
}>();
</script>

<template>
    <div class="mb-3">
        <nav class="flex mb-3">
            <ol class="inline-flex items-center space-x-1 text-md font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <span class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <Icon icon="home" class="w-5 h-5 mr-2.5" />Home
                    </span>
                </li>
                <li>
                    <div class="flex items-center">
                        <Icon icon="arrow-right-bc" class="w-6 h-6 text-gray-400" />
                        <span class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">{{ menuName }}</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <Icon icon="arrow-right-bc" class="w-6 h-6 text-gray-400" />
                        <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-500" aria-current="page">{{ currentLocation }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-base font-semibold text-gray-900 sm:text-xl dark:text-white">{{ currentLocation }}</h1>
    </div>
</template>
