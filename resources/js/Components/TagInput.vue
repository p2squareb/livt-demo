<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue: string;
}>();

const tags = ref(props.modelValue ? props.modelValue.split(',') : []);
const inputValue = ref('');

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>();

watch(tags.value, (newTags) => {
    emit('update:modelValue', newTags.join(','));
});

const addTag = (val: string) => {
    if (val.length > 0 && !tags.value.includes(val)) {
        tags.value.push(val);
        inputValue.value = '';
    }
};

const removeTag = (index: number) => {
    tags.value.splice(index, 1);
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' || event.key === ',') {
        event.preventDefault();
        addTag((event.target as HTMLInputElement).value);
        inputValue.value = '';
    } else if (event.key === 'Backspace' && inputValue.value === '' && tags.value.length > 0) {
        removeTag(tags.value.length - 1);
    }
};
</script>

<template>
    <div class="flex flex-wrap justify-center items-center rounded-md bg-gray-50 dark:bg-gray-700 p-2">
        <span v-for='(tag, index) in tags' :key='tag' class="inline-flex items-center px-2 py-1 me-2 text-md text-blue-800 bg-blue-100 rounded dark:bg-blue-200 dark:text-blue-800">
            {{ tag }}
            <button type="button" @click='removeTag(index)' class="inline-flex items-center p-1 ms-2 text-md">
                <svg class="w-2 h-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </span>
        <input
            v-model="inputValue"
            type='text'
            placeholder="항목 입력 후 엔터"
            class="flex-grow px-3 border-none outline-none text-md rounded-md bg-gray-50 text-gray-900 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
            @keydown="handleKeydown"
        />
   </div>
</template>
