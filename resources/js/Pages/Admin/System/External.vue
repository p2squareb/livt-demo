<script setup lang="ts" xmlns:wire="http://www.w3.org/1999/xhtml">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import {useForm} from "@inertiajs/vue3";
import {notify} from "@/Components/Toastify";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import CheckboxToggle from "@/Components/CheckboxToggle.vue";

defineOptions({
    layout: DashboardLayout,
});

const props = defineProps<{
    data: SystemExternal;
}>();

const form = useForm({
    use_google_analytics: props.data.use_google_analytics,
    google_analytics_id: props.data.google_analytics_id,
    use_google_login: props.data.use_google_login,
    google_client_id: props.data.google_client_id,
    google_client_secret: props.data.google_client_secret,
    use_naver_login: props.data.use_naver_login,
    naver_client_id: props.data.naver_client_id,
    naver_client_secret: props.data.naver_client_secret,
    use_kakao_login: props.data.use_kakao_login,
    kakao_client_id: props.data.kakao_client_id,
    kakao_client_secret: props.data.kakao_client_secret,
    use_kakao_map: props.data.use_kakao_map,
    kakao_javascript_key: props.data.kakao_javascript_key,
});

const submit = () => {
    console.log(form.google_analytics_id);
    form.post(route('admin.system.external.update'), {
        preserveScroll: true,
        onSuccess: () => {
            notify('success', '외부서비스 설정이 변경되었습니다.');
        },
    });
};
</script>

<template>
    <div class="px-4 py-6 bg-white block sm:flex items-center justify-between dark:bg-gray-900">
        <div class="w-full mb-3">
            <Breadcrumb menu-name="시스템 설정" current-location="외부서비스 설정" />
            <form @submit.prevent="submit">
                <div class="p-3 sm:p-6 my-3 bg-white border border-gray-200 rounded-md shadow-sm col-span-full dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center mb-3">
                        <div class="flex flex-col mr-5">
                            <h3 class="text-base font-semibold dark:text-white">구글 애널리틱스</h3>
                        </div>
                        <CheckboxToggle v-model="form.use_google_analytics" size="xs"/>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel for="google_analytics_id" class="block mb-2">
                                측정 ID
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-3"><a href="https://analytics.google.com" target="_blank">(구글 애널리틱스 바로가기)</a></span>
                            </InputLabel>
                            <TextInput type="text" id="google_analytics_id" v-model="form.google_analytics_id" class="block w-full py-[6px]" />
                        </div>
                    </div>

                    <div class="flex items-center mt-7 mb-3">
                        <div class="flex flex-col mr-5">
                            <h3 class="text-base font-semibold dark:text-white">소셜 로그인</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="google_client_id" class="block mb-2">
                                <div class="flex items-center">
                                    구글 CLIENT ID
                                    <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-3"><a href="https://cloud.google.com" target="_blank">(구글 클라우드 바로가기)</a></span>
                                    <CheckboxToggle v-model="form.use_google_login" size="xs" class="ml-5"/>
                                </div>
                            </InputLabel>
                            <TextInput type="text" id="google_client_id" v-model="form.google_client_id" class="block w-full py-[6px]" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="google_client_secret" class="block mb-2" value="구글 CLIENT SECRET" />
                            <TextInput type="text" id="google_client_secret" v-model="form.google_client_secret" class="block w-full py-[6px]" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="naver_client_id" class="block mb-2">
                                <div class="flex items-center">
                                    네이버 CLIENT ID
                                    <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-3"><a href="https://developers.naver.com" target="_blank">(네이버 개발자 센터 바로가기)</a></span>
                                    <CheckboxToggle v-model="form.use_naver_login" size="xs" class="ml-5"/>
                                </div>
                            </InputLabel>
                            <TextInput type="text" id="naver_client_id" v-model="form.naver_client_id" class="block w-full py-[6px]" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="naver_client_secret" class="block mb-2" value="네이버 CLIENT SECRET" />
                            <TextInput type="text" id="naver_client_secret" v-model="form.naver_client_secret" class="block w-full py-[6px]" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="kakao_client_id" class="block mb-2">
                                <div class="flex items-center">
                                    카카오 CLIENT ID
                                    <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-3"><a href="https://developers.kakao.com" target="_blank">(카카오 개발자 센터 바로가기)</a></span>
                                    <CheckboxToggle v-model="form.use_kakao_login" size="xs" class="ml-5"/>
                                </div>
                            </InputLabel>
                            <TextInput type="text" id="kakao_client_id" v-model="form.kakao_client_id" class="block w-full py-[6px]" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="kakao_client_secret" class="block mb-2" value="카카오 CLIENT SECRET" />
                            <TextInput type="text" id="kakao_client_secret" v-model="form.kakao_client_secret" class="block w-full py-[6px]" />
                        </div>
                    </div>

                    <div class="flex items-center mt-7 mb-3">
                        <div class="flex flex-col mr-5">
                            <h3 class="text-base font-semibold dark:text-white">카카오 지도/로컬</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel for="kakao_javascript_key" class="block mb-2">
                                <div class="flex items-center">
                                    Javascript Key
                                    <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-3"><a href="https://developers.kakao.com" target="_blank">(카카오 개발자 센터 바로가기)</a></span>
                                    <CheckboxToggle v-model="form.use_kakao_map" size="xs" class="ml-5"/>
                                </div>
                            </InputLabel>
                            <TextInput type="text" id="kakao_javascript_key" v-model="form.kakao_javascript_key" class="block w-full py-[6px]" />
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-full text-right">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-md text-md px-5 py-[7px] text-center dark:bg-blue-600 dark:hover:bg-blue-700">설정 적용하기</button>
                </div>
            </form>
        </div>
    </div>
</template>