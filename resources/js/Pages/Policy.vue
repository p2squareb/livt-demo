<script setup lang="ts">
defineProps<{
    policyId: string;
    privacy: string;
    terms: string;
}>();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <main>
        <section class="py-8 bg-white lg:py-24 dark:bg-gray-900">
            <div class="max-w-4xl px-4 mx-auto lg:px-4 format dark:format-invert text-gray-600 dark:text-gray-400">
                <div v-if="policyId === 'terms'">
                    <div class="flex items-start justify-between">
                        <h1 class="mb-6 text-3xl font-bold text-gray-900 lg:text-4xl dark:text-white">이용약관</h1>
                        <button @click="goBack" class="focus:outline-none ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-800 dark:text-white"  fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                        </button>
                    </div>
                    <div class="w-full break-words" v-html="terms">
                    </div>
                </div>
                <div v-else-if="policyId === 'privacy'">
                    <div class="flex items-start justify-between">
                        <h1 class="mb-6 text-3xl font-bold text-gray-900 lg:text-4xl dark:text-white">개인정보처리방침</h1>
                        <button @click="goBack" class="focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-800 dark:text-white"  fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18 17.94 6M18 18 6.06 6"/>
                            </svg>
                        </button>
                    </div>
                    <div class="w-full break-words" v-html="privacy">
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>
