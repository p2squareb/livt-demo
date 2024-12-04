<script setup lang="ts">
import { useDashboardSideMenuStore } from "@/stores/dashboard-side-menu";
import { ref, watch, onMounted, computed } from "vue";
import { usePage } from '@inertiajs/vue3';
import { MenuItem } from "@/types/side-menu";

const page = usePage();
const formattedMenu = ref<MenuItem[]>([]);
const sideMenuStore = useDashboardSideMenuStore();
const isSidebarOpen = computed(() => sideMenuStore.isSidebarOpen);
const hoveredMenu = ref<number | null>(null);

const filterMenuByPermissions = (menu: MenuItem[]): MenuItem[] => {
    const permissions = (page.props.menu.permissions as string).split(',');
    
    return menu.filter(item => {
        if (!item.id) return true;
        
        if (item.subMenu?.length) {
            const filteredSubMenu = item.subMenu.filter(subItem => 
                !subItem.id || permissions.includes(subItem.id)
            );
            
            item.subMenu = filteredSubMenu;
            
            return filteredSubMenu.length > 0;
        }
        
        return permissions.includes(item.id);
    });
};

const setActiveMenu = (url: string) => {
    const cleanUrl = url.split('?')[0];

    sideMenuStore.menu = filterMenuByPermissions(sideMenuStore.menu);
    
    sideMenuStore.menu.forEach((item) => {
        if (item.subMenu?.length) {
            item.subMenu.forEach(subItem => {
                subItem.active = false;
            });
            
            const hasActiveSubmenu = item.subMenu.some(subItem => {
                if (subItem.link === cleanUrl) {
                    subItem.active = true;
                    return true;
                }
                return false;
            });

            item.active = false;
            item.activeDropdown = hasActiveSubmenu;
        } else if (item.link) {
            item.active = item.link === cleanUrl;
        }
    });
    
    formattedMenu.value = [...sideMenuStore.menu];
};

const toggleDropdown = (menuKey: number) => {
    formattedMenu.value[menuKey].activeDropdown = !formattedMenu.value[menuKey].activeDropdown;
};

const handleMenuHover = (menuKey: number | null) => {
    hoveredMenu.value = menuKey;
};

watch(() => page.url, setActiveMenu);

onMounted(() => setActiveMenu(page.url));
</script>

<template>
    <aside 
        :class="[
            'fixed top-0 left-0 z-20 h-full pt-16 transition-all duration-300 bg-white dark:bg-gray-800',
            isSidebarOpen ? 'w-64' : 'w-[74px]'
        ]"
        aria-label="Sidebar"
    >
        <div class="relative flex flex-col flex-1 min-h-0 pt-0 border-r border-gray-200 dark:border-gray-700 h-full">

            <div class="flex justify-end pr-4 pt-3">
                <button 
                    @click="sideMenuStore.toggleSidebar"
                    class="items-center p-2"
                >
                    <Icon icon="sort-horizontal" class="w-6 h-6 transition-transform duration-300 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"  :class="{ 'transform rotate-180': !isSidebarOpen }" />
                </button>
            </div>

            <div class="flex flex-col flex-1 pb-4 overflow-y-auto">
                <div class="flex-1 px-3 space-y-1 bg-white dark:bg-gray-800">
                    <ul class="pb-2 space-y-1">
                        <template v-for="(menu, menuKey) in formattedMenu" :key="menuKey">
                            <li 
                                class="relative"
                                @mouseenter="handleMenuHover(menuKey)"
                                @mouseleave="handleMenuHover(null)"
                            >
                                <template v-if="menu.subMenu?.length">
                                    <button 
                                        type="button" 
                                        @click="toggleDropdown(menuKey)" 
                                        class="flex items-center justify-start w-full p-3 text-base text-gray-900 transition-all duration-300 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900" 
                                        :class="{ 'bg-gray-300 dark:bg-gray-900': menu.active }"
                                    >
                                        <Icon :icon="menu.icon" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
                                        <span v-show="isSidebarOpen" class="ml-3 text-left flex-1 font-semibold">{{menu.title}}</span>
                                        <svg 
                                            v-show="isSidebarOpen"
                                            class="w-6 h-6 transition-transform duration-200" 
                                            :class="{ 'transform rotate-180': menu.activeDropdown }"
                                            fill="currentColor" 
                                            viewBox="0 0 20 20" 
                                        >
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>

                                    <transition
                                        enter-active-class="transition-all duration-500 ease-in-out"
                                        enter-from-class="max-h-0 opacity-0"
                                        enter-to-class="max-h-[500px] opacity-100"
                                        leave-active-class="transition-all duration-500 ease-in-out"
                                        leave-from-class="max-h-[500px] opacity-100"
                                        leave-to-class="max-h-0 opacity-0"
                                    >
                                        <ul v-if="isSidebarOpen && menu.activeDropdown" class="py-2 space-y-2 overflow-hidden">
                                            <li v-for="subMenu in menu.subMenu" :key="subMenu.link">
                                                <Link 
                                                    :href="subMenu.link" 
                                                    class="flex items-center p-2 pl-7 text-md text-gray-700 transition-colors duration-200 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-900"
                                                    :class="{ 'bg-gray-100 dark:bg-gray-900': subMenu.active }"
                                                >
                                                    <Icon icon="heart-beat" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
                                                    <span class="ml-3">{{ subMenu.title }}</span>
                                                </Link>
                                            </li>
                                        </ul>
                                    </transition>
                                </template>

                                <template v-else>
                                    <Link 
                                        :href="menu.link"
                                        class="flex items-center justify-start w-full p-3 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-900"
                                        :class="{ 'bg-gray-300 dark:bg-gray-900': menu.active }"
                                    >
                                        <Icon :icon="menu.icon" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
                                        <span v-show="isSidebarOpen" class="ml-3 text-left font-semibold">{{menu.title}}</span>
                                    </Link>
                                </template>
                            </li>
                        </template>
                        <li>
                            <div class="border-t border-gray-300 dark:border-gray-600 my-3"></div>
                        </li>
                        <li>
                            <a href="/horizon" target="_blank" 
                                class="flex items-center justify-start w-full p-3 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-900"
                            >
                                <Icon icon="horizon" class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
                                <span v-show="isSidebarOpen" class="ml-3 text-left">Laravel Horizon</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/p2squareb/livt-demo" target="_blank" 
                                class="flex items-center justify-start w-full p-3 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-900"
                            >
                                <Icon icon="github" class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" />
                                <span v-show="isSidebarOpen" class="ml-3 text-left">Github Repository</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.transition-all {
    transition-property: all;
}
</style>
