<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
const activeTab = ref('products')
const selectedPeriod = ref('Last 7 days')
const showDropdown = ref(false)

const periods = [
    { label: 'Yesterday', value: 'yesterday' },
    { label: 'Today', value: 'today' },
    { label: 'Last 7 days', value: 'last7days' },
    { label: 'Last 30 days', value: 'last30days' },
    { label: 'Last 90 days', value: 'last90days' },
    { label: 'Custom...', value: 'custom' }
]

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value
}

const selectPeriod = (period: string) => {
    selectedPeriod.value = period
    showDropdown.value = false
}

const handleClickOutside = (event: MouseEvent) => {
    const dropdown = document.getElementById('period-dropdown')
    const button = document.getElementById('period-button')
    
    if (dropdown && button && !dropdown.contains(event.target as Node) && !button.contains(event.target as Node)) {
        showDropdown.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">Statistics this month</h3>
        <div class="sm:hidden">
            <select v-model="activeTab" class="bg-gray-50 border-0 border-b border-gray-200 text-gray-900 text-md rounded-t-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="products">Top Products</option>
                <option value="customers">Top Customers</option>
            </select>
        </div>
        <ul class="hidden text-md font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg sm:flex dark:divide-gray-600 dark:text-gray-400">
            <li class="w-full">
                <button 
                    @click="activeTab = 'products'"
                    :class="[
                        'inline-block w-full p-4 rounded-tl-lg bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600',
                        activeTab === 'products' ? 'text-blue-600 dark:text-blue-500' : ''
                    ]"
                >
                    Top Products
                </button>
            </li>
            <li class="w-full">
                <button 
                    @click="activeTab = 'customers'"
                    :class="[
                        'inline-block w-full p-4 rounded-tr-lg bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600',
                        activeTab === 'customers' ? 'text-blue-600 dark:text-blue-500' : ''
                    ]"
                >
                    Top Customers
                </button>
            </li>
        </ul>
        <div class="border-t border-gray-200 dark:border-gray-600">
            <div v-show="activeTab === 'products'" class="pt-4">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <img class="flex-shrink-0 w-10 h-10" src="/images/sample/iphone.png" alt="imac image">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 truncate dark:text-white">
                                        iPhone 14 Pro
                                    </p>
                                    <div class="flex items-center justify-end flex-1 text-md text-green-500 dark:text-green-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                        </svg>
                                        2.5%
                                        <span class="ml-2 text-gray-500">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $445,467
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <img class="flex-shrink-0 w-10 h-10" src="/images/sample/imac.png" alt="imac image">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 truncate dark:text-white">
                                        Apple iMac 27"
                                    </p>
                                    <div class="flex items-center justify-end flex-1 text-md text-green-500 dark:text-green-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                        </svg>
                                        12.5%
                                        <span class="ml-2 text-gray-500">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $256,982
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <img class="flex-shrink-0 w-10 h-10" src="/images/sample/watch.png" alt="watch image">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 truncate dark:text-white">
                                        Apple Watch SE
                                    </p>
                                    <div class="flex items-center justify-end flex-1 text-md text-red-600 dark:text-red-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"></path>
                                        </svg>
                                        1.35%
                                        <span class="ml-2 text-gray-500">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $201,869
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <img class="flex-shrink-0 w-10 h-10" src="/images/sample/ipad.png" alt="ipad image">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 truncate dark:text-white">
                                        Apple iPad Air
                                    </p>
                                    <div class="flex items-center justify-end flex-1 text-md text-green-500 dark:text-green-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                        </svg>
                                        12.5%
                                        <span class="ml-2 text-gray-500">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $103,967
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center min-w-0">
                                <img class="flex-shrink-0 w-10 h-10" src="/images/sample/imac2.png" alt="imac image">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900 truncate dark:text-white">
                                        Apple iMac 24"
                                    </p>
                                    <div class="flex items-center justify-end flex-1 text-md text-red-600 dark:text-red-500">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z"></path>
                                        </svg>
                                        2%
                                        <span class="ml-2 text-gray-500">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $98,543
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-show="activeTab === 'customers'" class="pt-4">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="/images/sample/cat1.png" alt="Neil image">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                    Neil Sims
                                </p>
                                <p class="text-md text-gray-500 truncate dark:text-gray-400">
                                    email@flowbite.com
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $3320
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="/images/sample/cat2.png" alt="Neil image">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                    Bonnie Green
                                </p>
                                <p class="text-md text-gray-500 truncate dark:text-gray-400">
                                    email@flowbite.com
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $2467
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="/images/sample/cat3.png" alt="Neil image">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                    Michael Gough
                                </p>
                                <p class="text-md text-gray-500 truncate dark:text-gray-400">
                                    email@flowbite.com
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $2235
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="/images/sample/cat4.png" alt="Neil image">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                    Thomes Lean
                                </p>
                                <p class="text-md text-gray-500 truncate dark:text-gray-400">
                                    email@flowbite.com
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $1842
                            </div>
                        </div>
                    </li>
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="/images/sample/cat5.png" alt="Neil image">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate dark:text-white">
                                    Lana Byrd
                                </p>
                                <p class="text-md text-gray-500 truncate dark:text-gray-400">
                                    email@flowbite.com
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                $1044
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex items-center justify-between pt-3 mt-5 border-t border-gray-200 sm:pt-6 dark:border-gray-700">
            <div class="relative">
                <button 
                    id="period-button"
                    @click="toggleDropdown"
                    class="inline-flex items-center p-2 text-md font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" 
                    type="button"
                >
                    {{ selectedPeriod }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div 
                    id="period-dropdown"
                    v-if="showDropdown"
                    class="absolute left-0 z-50 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700 dark:divide-gray-600"
                >
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                            Sep 16, 2021 - Sep 22, 2021
                        </p>
                    </div>
                    <div class="py-1">
                        <template v-for="period in periods" :key="period.value">
                            <button
                                @click="selectPeriod(period.label)"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                {{ period.label }}
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0">
                <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-blue-700 sm:text-md hover:bg-gray-100 dark:text-blue-500 dark:hover:bg-gray-700">
                    Full Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
</template>
