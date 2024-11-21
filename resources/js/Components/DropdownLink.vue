<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href: string;
}>();
</script>

<template>
    <Link
        :href="href"
        class="flex w-full px-4 py-2 text-start text-md leading-5 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition duration-150 ease-in-out hover:bg-gray-300 dark:hover:bg-gray-900 focus:outline-none"
    >
        <slot />
    </Link>
</template>
