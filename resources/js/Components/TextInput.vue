<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        class="px-3 text-md focus:ring-0 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 text-gray-900 dark:bg-gray-700 dark:text-white dark:placeholder-gray-300"
        v-model="model"
        ref="input"
    />
</template>
