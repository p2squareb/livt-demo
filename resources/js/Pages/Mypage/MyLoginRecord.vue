<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import dayjs from "dayjs";
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { LoginRecord } from '@/types/user';

const props = defineProps<{
    loginRecords: LoginRecord[] | null;
}>();
</script>

<template>
    <div class="mt-3 xl:mt-0 p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <div class="items-center justify-between lg:flex">
            <div class="mb-2 lg:mb-0">
                <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">로그인 기록 (최근 7일)</h3>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="overflow-x-auto rounded-lg">
                <div class="inline-block min-w-full align-middle">
                    <div class="shadow sm:rounded-md divide-y divide-gray-200 dark:divide-gray-600 border border-gray-200 dark:border-gray-600">
                        <div 
                            v-for="(loginRecord, idx) in props.loginRecords" :key="idx" 
                            class="grid grid-cols-6 gap-1 p-3 font-semibold text-gray-900 dark:text-gray-300"
                        >
                            <div class="col-span-full sm:col-span-2">
                                <div class="flex items-center">
                                    Account type : {{ loginRecord.provider }}
                                </div>
                            </div>
                            <div class="col-span-full sm:col-span-2">
                                <div class="flex items-center">
                                    Access date : {{ dayjs(loginRecord.created_at).format('YYYY.MM.DD HH:mm') }} 
                                </div>
                            </div>
                            <div class="col-span-full sm:col-span-2">
                                <div class="flex items-center">
                                    IP : {{ loginRecord.ip_address }}
                                </div>
                            </div>
                            <div class="col-span-full">
                                <div class="flex items-center">
                                    {{ loginRecord.user_agent }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
