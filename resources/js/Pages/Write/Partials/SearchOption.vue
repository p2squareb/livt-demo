<script setup lang="ts">
import { ref, watch } from "vue";
import debounce from 'lodash.debounce';
import Selectbox from '@/Components/Selectbox.vue';

const props = defineProps<{
    boardUseCategory: boolean;
    boardCategory: string;
    category: string;
    searchString: string;
    sortBy: string;
}>();

const emit = defineEmits(['update:category', 'update:searchString', 'update:sortBy']);

const localCategory = ref(props.category);
const localSearchString = ref(props.searchString);
const localSortBy = ref(props.sortBy);

const categoryOptions = [
    { value: '', name: '전체' },
    ...props.boardCategory.split(',').map(category => ({ value: category, name: category }))
];

watch(localCategory, (newValue) => {
    emit('update:category', newValue);
});

const debouncedEmit = debounce((value: string) => {
    emit('update:searchString', value);
}, 500);

watch(localSearchString, (newValue) => {
    debouncedEmit(newValue);
});

const handleSearch = () => {
    emit('update:searchString', localSearchString.value);
};

const setSortBy = (sort: string) => {
    localSortBy.value = sort;
    emit('update:sortBy', sort);
};
</script>

<template>
    <div class="items-center justify-between py-4 border-b border-gray-200 dark:border-gray-700 not-format block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 text-md">
        <div class="flex items-center mb-4 sm:mb-0">
            <div v-if="boardUseCategory" class="flex sm:pr-3 w-full">
                <Selectbox 
                    class="py-[6px]"
                    v-model="localCategory"
                    :options="categoryOptions"
                />
            </div>
            <div class="flex items-center w-full sm:justify-start">
                <div class="flex space-x-2">
                    <button 
                        @click="setSortBy('new')" 
                        :class="[
                            'inline-flex justify-center p-1 text-base font-semibold rounded cursor-pointer',
                            localSortBy === 'new' 
                                ? 'text-green-600 dark:text-green-500' 
                                : 'text-gray-500 dark:text-gray-400'
                        ]"
                    >
                        <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13 2l.018 .001l.016 .001l.083 .005l.011 .002h.011l.038 .009l.052 .008l.016 .006l.011 .001l.029 .011l.052 .014l.019 .009l.015 .004l.028 .014l.04 .017l.021 .012l.022 .01l.023 .015l.031 .017l.034 .024l.018 .011l.013 .012l.024 .017l.038 .034l.022 .017l.008 .01l.014 .012l.036 .041l.026 .027l.006 .009c.12 .147 .196 .322 .218 .513l.001 .012l.002 .041l.004 .064v6h5a1 1 0 0 1 .868 1.497l-.06 .091l-8 11c-.568 .783 -1.808 .38 -1.808 -.588v-6h-5a1 1 0 0 1 -.868 -1.497l.06 -.091l8 -11l.01 -.013l.018 -.024l.033 -.038l.018 -.022l.009 -.008l.013 -.014l.04 -.036l.028 -.026l.008 -.006a1 1 0 0 1 .402 -.199l.011 -.001l.027 -.005l.074 -.013l.011 -.001l.041 -.002z"></path>
                        </svg>NEW
                    </button>
                    <button 
                        @click="setSortBy('hot')" 
                        :class="[
                            'inline-flex justify-center p-1 text-base font-semibold rounded cursor-pointer',
                            localSortBy === 'hot' 
                                ? 'text-red-600 dark:text-red-500' 
                                : 'text-gray-500 dark:text-gray-400'
                        ]"
                    >
                        <svg class="w-5 h-5 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.597 3.2A1 1 0 0 0 7.04 4.289a3.49 3.49 0 0 1 .057 1.795 3.448 3.448 0 0 1-.84 1.575.999.999 0 0 0-.077.094c-.596.817-3.96 5.6-.941 10.762l.03.049a7.73 7.73 0 0 0 2.917 2.602 7.617 7.617 0 0 0 3.772.829 8.06 8.06 0 0 0 3.986-.975 8.185 8.185 0 0 0 3.04-2.864c1.301-2.2 1.184-4.556.588-6.441-.583-1.848-1.68-3.414-2.607-4.102a1 1 0 0 0-1.594.757c-.067 1.431-.363 2.551-.794 3.431-.222-2.407-1.127-4.196-2.224-5.524-1.147-1.39-2.564-2.3-3.323-2.788a8.487 8.487 0 0 1-.432-.287Z"/>
                        </svg>HOT
                    </button>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <div class="relative w-full">
                <input 
                    type="search" 
                    v-model="localSearchString"
                    @keyup.enter="handleSearch"
                    class="block py-[6px] px-3 w-full md:w-[270px] text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                    placeholder="제목+내용+작성자 검색"
                />
                <button type="button" @click="handleSearch" class="absolute top-0 end-0 px-3 py-[6px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
    </div>
</template>
