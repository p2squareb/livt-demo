<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
const appName: any = usePage().props.appName;
</script>

<template>
    <img src="/images/livt-logo.png" class="h-7 mr-2" :alt="appName" />
    <span class="self-center text-2xl font-bold whitespace-nowrap dark:text-white tracking-widest">{{ appName }}</span>
</template>
