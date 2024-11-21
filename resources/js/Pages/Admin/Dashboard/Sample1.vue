<script setup lang="ts">
import { ref } from 'vue';

const selectedPeriod = ref('Last 7 days');
const periods = [
    'Yesterday',
    'Today',
    'Last 7 days',
    'Last 30 days',
    'Last 90 days',
    'Custom...'
];

const selectPeriod = (period: string) => {
    selectedPeriod.value = period;
};
</script>

<template>
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-shrink-0">
                <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">$45,385</span>
                <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Sales this week</h3>
            </div>
            <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500 dark:text-green-400">
                12.5%
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                            d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
        <div id="main-chart"></div>
        <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-200 sm:pt-6 dark:border-gray-700">
            <div class="relative">
                <button 
                    class="inline-flex items-center p-2 text-md font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" 
                    type="button"
                    @click="$refs.dropdown.classList.toggle('hidden')"
                >
                    {{ selectedPeriod }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div 
                    ref="dropdown"
                    class="absolute left-0 z-50 hidden w-44 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                >
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                        <li v-for="period in periods" :key="period">
                            <a 
                                href="#"
                                @click.prevent="selectPeriod(period)"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                {{ period }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex-shrink-0">
                <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-blue-700 sm:text-md hover:bg-gray-100 dark:text-blue-500 dark:hover:bg-gray-700">
                    Sales Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
</template>