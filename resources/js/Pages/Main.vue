<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Welcome" />
    <main class="grow">
        <section class="py-4 bg-white dark:bg-gray-900 lg:pt-12 lg:pb-16">
            <div class="px-4 mx-auto max-w-8xl lg:px-4 lg:text-center">
                <a class="inline-flex items-center justify-between px-1 py-1 pr-4 mb-5 text-md text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-white hover:bg-gray-200 hover:dark:bg-gray-700" href="https://livt.it" target="_blank">
                    <span class="text-xs bg-blue-700 dark:bg-blue-600 rounded-full text-white px-4 py-1.5 mr-3">Notice</span>
                    <span class="mr-2 text-md font-medium">오류, 기능 추가, 건의, 기타 문의사항은 클릭</span>
                    <svg class="w-2.5 h-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"></path>
                    </svg>
                </a>
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-gray-900 lg:font-extrabold lg:text-6xl lg:leading-none dark:text-white lg:text-center xl:px-36 lg:mb-7">
                    Laravel, Inertia, Vue, Tailwind</h1>
                <p class="mb-7 text-base font-normal text-gray-700 dark:text-gray-300 lg:text-center xl:px-60">
                    위의 스크립트로 개발된 Boilerplate입니다. <br>
                    반응형으로 개발되었으며 UI는 Flowbite를 참고하였습니다.<br>
                    소스코드는 우측 상단 깃헙 아이콘을 클릭하세요!
                </p>
                <p class="mb-7 text-md font-normal text-gray-700 dark:text-gray-300 lg:text-center xl:px-60">
                    Laravel 11 (PHP 8.2 이상), Node.js(22.9.0), Inertia, Vue3, Tailwind, PostgreSql(14.13), Redis<br>
                    (각 버전은 개발 상황에 따라 변경될 수 있습니다.)
                </p>
            </div>
        </section>
    </main>
</template>

