<script setup lang="ts">
import { ref, watch } from 'vue';
import debounce from 'lodash.debounce';

const props = defineProps<{
    modelValue: string;
    placeholder?: string;
    debounceTime?: number;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search'): void;
}>();

const inputValue = ref(props.modelValue);

watch(inputValue, (newValue) => {
    emit('update:modelValue', newValue);
});

const debouncedSearch = debounce(() => {
    emit('search');
}, props.debounceTime || 500);

const handleInput = () => {
    debouncedSearch();
};

const handleKeyUp = (event: KeyboardEvent) => {
    if (event.key === 'Enter') {
        emit('search');
    }
};
</script>

<template>
    <input
        type="search"
        v-model="inputValue"
        @input="handleInput"
        @keyup="handleKeyUp"
        class="block p-[6px] w-full md:w-[270px] text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
        :placeholder="placeholder"
    />
    <button
        type="button"
        @click="$emit('search')"
        class="absolute top-0 end-0 px-3 py-[7px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700"
    >
        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
        </svg>
        <span class="sr-only">검색</span>
    </button>
</template>
