<script setup lang="ts">
interface Paginator {
    links: {
        active: boolean;
        label: string;
        url: string;
    }[];
    from: number;
    to: number;
    total: number;
}

defineProps<{
    paginator: Paginator;
}>();

const makeLabel = (label: string) => {
    if (label.includes('previous')) return `<span class="sr-only">Previous</span>
        <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
        </svg>`;
    if (label.includes('next')) return `<span class="sr-only">Next</span>
        <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>`;
    return label;
}
</script>

<template>
    <div class="sticky bottom-0 right-0 rounded-md shadow-sm items-center w-full px-4 sm:flex sm:justify-between">
        <nav>
            <ul class="inline-flex -space-x-px text-base h-10">
                <li v-for="(link, i) in $props.paginator.links" :key="i">
                    <component 
                        :is="link.url ? 'Link' : 'span'"
                        :href="link.url || undefined" 
                        v-html="makeLabel(link.label)"
                        class="flex items-center justify-center px-4 h-10 leading-tight" 
                        :class="{ 
                            'text-blue-600  border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': link.active, 
                            'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
                            'cursor-not-allowed opacity-50': !link.url
                        }"
                    />
                </li>
            </ul>
        </nav>
        <span class="text-md font-normal text-gray-500 dark:text-gray-400">
            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $props.paginator.from }}-{{ $props.paginator.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $props.paginator.total }}</span> results
        </span>
    </div>
</template>