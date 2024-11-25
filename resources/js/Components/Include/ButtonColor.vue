<script setup lang="ts">
import {computed} from "vue";

const props = withDefaults(
    defineProps<{
        color?: 'blue' | 'gray' | 'red' | 'indigo' | 'green' | 'yellow' | 'purple' | 'teal' | 'alternative' | 'black' | 'white';
    }>(),
    {
        color: 'blue',
    }
)

const buttonClass = computed(() => {
    return {
        'text-md rounded-md text-white bg-blue-700 hover:bg-blue-800 dark:bg-blue-700 dark:hover:bg-blue-800': props.color === 'blue',
        'text-md rounded-md text-white bg-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600': props.color === 'gray',
        'text-md rounded-md text-white bg-red-700 hover:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700': props.color === 'red',
        'text-md rounded-md text-white bg-indigo-600 hover:bg-indigo-800 dark:bg-indigo-600 dark:hover:bg-indigo-800': props.color === 'indigo',
        'text-md rounded-md text-white bg-green-800 hover:bg-green-900': props.color === 'green',
        'text-md rounded-md text-gray-900 bg-yellow-500 hover:bg-yellow-600': props.color === 'yellow',
        'text-md rounded-md text-white bg-purple-700 hover:bg-purple-800 dark:bg-purple-600 dark:hover:bg-purple-700': props.color === 'purple',
        'text-md rounded-md text-white bg-teal-800 hover:bg-teal-700': props.color === 'teal',
        'text-md rounded-md text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-800': props.color === 'alternative',
        'text-md rounded-md text-white bg-black hover:bg-stone-900 dark:bg-black dark:hover:bg-stone-900': props.color === 'black',
        'text-md rounded-md text-gray-900 bg-gray-100 hover:bg-gray-200 border border-gray-300': props.color === 'white',
    }
});
</script>

<template>
    <button :class="buttonClass"><slot /></button>
</template>
