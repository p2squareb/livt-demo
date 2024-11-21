<script setup lang="ts">
import AppNavbar from "@/Layouts/partials/AppNavbar.vue";
</script>

<template>
    <div class="flex flex-col bg-white dark:bg-gray-900 text-md text-gray-900 dark:text-white">
        <AppNavbar />
        <main class="grow">
            <main class="pt-3 pb-7 bg-white lg:pb-16 dark:bg-gray-900">
                <div class="flex justify-between px-4 mx-auto max-w-8xl">
                    <div class="hidden mb-6 xl:block lg:w-50"></div>
                    <slot />
                    <aside class="hidden lg:block lg:w-50" aria-labelledby="sidebar-label"></aside>
                </div>
            </main>
        </main>
    </div>
</template>
