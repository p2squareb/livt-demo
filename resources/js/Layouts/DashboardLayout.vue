<script setup lang="ts">
import { useDashboardSideMenuStore } from "@/stores/dashboard-side-menu";
import { computed } from 'vue';
import DashboardNavbar from '@/Layouts/partials/DashboardNavbar.vue';
import DashboardSidebar from '@/Layouts/partials/DashboardSidebar.vue';
import DashboardFooter from '@/Layouts/partials/DashboardFooter.vue';

const sideMenuStore = useDashboardSideMenuStore();
const isSidebarOpen = computed(() => sideMenuStore.isSidebarOpen);
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <DashboardNavbar />
        <DashboardSidebar />
        <main 
            class="main-content pt-16 bg-gray-50 dark:bg-gray-900"
            :class="{ 'sidebar-closed': !isSidebarOpen }"
        >
            <slot></slot>
            <DashboardFooter />
        </main>
    </div>
</template>

<style>
.main-content {
    transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
    margin-left: 16rem; /* 256px */
    width: calc(100% - 16rem);
}

.main-content.sidebar-closed {
    margin-left: 4.5rem;
    width: calc(100% - 4.5rem);
}
</style>
