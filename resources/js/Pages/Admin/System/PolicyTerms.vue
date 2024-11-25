<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import {useForm} from "@inertiajs/vue3";
import {notify} from "@/Components/Toastify";
import {ref} from "vue";
import TextEditor from "@/Components/TextEditor.vue";
import ButtonColor from "@/Components/Include/ButtonColor.vue";


defineOptions({
    layout: DashboardLayout,
});

const props = defineProps<{
    terms: string;
    privacy: string;
}>();

const policyTermsTab = ref('terms');

const form = useForm({
    terms: props.terms,
    privacy: props.privacy,
});

const submit = () => {
    form.post(route('admin.system.policy-terms.update'), {
        preserveScroll: true,
        onSuccess: () => {
            notify('success', '서비스 약관 내용이 변경되었습니다.');
        },
    });
};

</script>

<template>
    <div class="px-4 py-6 bg-white block sm:flex items-center justify-between dark:bg-gray-900">
        <div class="w-full mb-3">
            <Breadcrumb menu-name="시스템 설정" current-location="서비스 약관" />
            <form @submit.prevent="submit">
                <div x-data="{ policyTermsTab: 'terms' }" class="p-3 my-3 bg-white border border-gray-200 rounded-md shadow-sm col-span-full dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-md font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px">
                            <li class="me-2">
                                <button
                                    @click.prevent="policyTermsTab = 'terms'"
                                    :class="{
                                        'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500': policyTermsTab === 'terms',
                                        'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': policyTermsTab !== 'terms'
                                    }"
                                    class="inline-block p-4 border-b-2 rounded-t-lg"
                                >이용약관</button>
                            </li>
                            <li class="me-2">
                                <button
                                    @click.prevent="policyTermsTab = 'privacy'"
                                    :class="{
                                        'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500': policyTermsTab === 'privacy',
                                        'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': policyTermsTab !== 'privacy'
                                    }"
                                    class="inline-block p-4 border-b-2 rounded-t-lg"
                                >개인정보처리방침</button>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div v-if="policyTermsTab === 'terms'" class="py-4 rounded-md bg-gray-50 dark:bg-gray-800">
                            <label for="terms" class="block mb-2 text-md font-medium text-gray-900 dark:text-white"></label>
                            <TextEditor v-model="form.terms" />
                        </div>
                        <div v-if="policyTermsTab === 'privacy'" class="py-4 rounded-md bg-gray-50 dark:bg-gray-800">
                            <label for="privacy" class="block mb-2 text-md font-medium text-gray-900 dark:text-white"></label>
                            <TextEditor v-model="form.privacy" />
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-full text-right pt-8">
                        <ButtonColor class="px-5 py-[7px] text-md font-medium text-center" :color="'blue'" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            서비스 약관 적용하기
                        </ButtonColor>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
