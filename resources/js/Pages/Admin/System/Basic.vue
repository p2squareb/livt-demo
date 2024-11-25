<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import InputError from "@/Components/Form/InputError.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Radio from "@/Components/Form/Radio.vue";
import Tooltip from "@/Components/Tooltip.vue";
import {notify} from "@/Components/Toastify";

defineOptions({
    layout: DashboardLayout,
});

const props = defineProps<{
    data: SystemBasic;
}>();

const form = useForm({
    site_name: props.data.basic.site_name,
    site_description: props.data.basic.site_description,
    domain_name: props.data.basic.domain_name,
    sq_email: props.data.basic.sq_email,
    use_dormant: props.data.basic.use_dormant,
    use_external_email: props.data.basic.use_external_email,
    use_smtp: props.data.basic.use_smtp,
    image_resize: props.data.image.image_resize,
    image_width_max: props.data.image.image_width_max,
});

const submit = () => {
    form.post(route('admin.system.basic.update'), {
        preserveScroll: true,
        onSuccess: () => {
            notify('success', '기본 환경설정이 변경되었습니다.');
        },
    });
};
</script>

<template>
    <div class="px-4 py-6 block sm:flex items-center justify-between bg-white dark:bg-gray-900 ">
        <div class="w-full">
            <Breadcrumb menu-name="시스템 설정" current-location="기본 환경설정" />
            <form @submit.prevent="submit">
                <div class="p-3 sm:p-6 my-3 bg-white border border-gray-200 rounded-md shadow-sm col-span-full dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center mb-5">
                        <div class="flex flex-col">
                            <h3 class="text-base font-semibold dark:text-white">기본 정보</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="site_name" class="block mb-2">
                                사이트명
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-2">(metadata : title, author)</span>
                            </InputLabel>
                            <TextInput type="text" id="site_name" v-model="form.site_name" class="block w-full py-[6px]" required placeholder="사이트명을 입력해주세요." />
                            <InputError :message="form.errors.site_name" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="site_description" class="block mb-2">
                                사이트 설명
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-2">(metadata : description)</span>
                            </InputLabel>
                            <TextInput type="text" id="site_description" v-model="form.site_description" class="block w-full py-[6px]" required placeholder="사이트 설명을 입력해주세요." />
                            <InputError :message="form.errors.site_description" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="domain_name" value="도메인" class="block mb-2" />
                            <TextInput type="text" id="domain_name" v-model="form.domain_name" class="block w-full py-[6px]" required placeholder="도메인을 입력해주세요." />
                            <InputError :message="form.errors.domain_name" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="sq_email" value="대표 이메일" class="block mb-2" />
                            <TextInput type="email" id="sq_email" v-model="form.sq_email" class="block w-full py-[6px]" required placeholder="대표 이메일을 입력해주세요." />
                            <InputError :message="form.errors.sq_email" />
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_smtp" class="block mb-2">
                                SMTP 사용
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-2">(선행조건 : .env 설정 필요)</span>
                            </InputLabel>
                            <Radio v-model="form.use_smtp" value="1" id="use_smtp-1" label="사용함" />
                            <Radio v-model="form.use_smtp" value="0" id="use_smtp-0" label="사용안함" addClass="ml-4" />
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_external_email" class="block mb-2">
                                외부 이메일 서비스 사용
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-2">(시스템설정 > 외부서비스 설정)</span>
                            </InputLabel>
                            <Radio v-model="form.use_external_email" value="1" id="use_external_email-1" label="사용함" />
                            <Radio v-model="form.use_external_email" value="0" id="use_external_email-0" label="사용안함" addClass="ml-4" />
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <label for="use_dormant" class="flex mb-2 font-medium text-gray-900 dark:text-white">
                                <span class="mr-3">휴면회원 사용여부 설정</span>
                                <Tooltip placement="bottom">
                                    <template #trigger>
                                        <button type="button" class="mt-[4px]">
                                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Show information</span>
                                        </button>
                                    </template>
                                    <template #content>
                                        <div class="p-3 space-y-2 text-md text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700">
                                            <h3 class="font-semibold">개인정보 유효기간제</h3>
                                            <p>• 2023년9월15일 개인정보보호법 개정에 따라 '개인정보 유효기간제'가 폐지되었습니다.</p>
                                            <p>• 업체 운영방침에 따라 자율적으로 휴면회원 배치 사용여부를 설정할 수 있습니다.</p>
                                            <p>• 1년 이상 미접속자는 휴면회원으로 전화되며, 휴면회원은 SMS/알림톡/이메일 알림을 받지 않습니다.</p>
                                            <p>• 기능을 사용하시려면 서버에 스케줄러를 등록해주세요.</p>
                                        </div>
                                    </template>
                                </Tooltip>
                            </label>
                            <input v-model="form.use_dormant" id="use_dormant-1" type="radio" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <label for="use_dormant-1" class="ms-2 text-md font-medium text-gray-900 dark:text-gray-300 ml-1">사용함</label>
                            <input v-model="form.use_dormant" id="use_dormant-0" type="radio" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600 ml-4">
                            <label for="use_dormant-0" class="ms-2 text-md font-medium text-gray-900 dark:text-gray-300 ml-1 mr-4">사용안함</label>
                            <div
                                 class="absolute z-100 inline-block text-sm font-light text-gray-700 transition-opacity duration-300 bg-white border border-gray-200 rounded-md shadow-sm w-80 sm:w-[520px] dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 opacity-0 invisible"
                                 style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-263px, 81px, 0px);"
                            >
                                <div class="p-3 space-y-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">개인정보 유효기간제</h3>
                                    <p>• 2023년9월15일 개인정보보호법 개정에 따라 '개인정보 유효기간제'가 폐지되었습니다.</p>
                                    <p>• 업체 운영방침에 따라 자율적으로 휴면회원 배치 사용여부를 설정할 수 있습니다.</p>
                                    <p>• 1년 이상 미접속자는 휴면회원으로 전화되며, 휴면회원은 SMS/알림톡/이메일 알림을 받지 않습니다.</p>
                                    <p>• 기능을 사용하시려면 서버에 스케줄러를 등록해주세요.</p>
                                </div>
                                <div style="position: absolute; left: 0px; transform: translate3d(271px, 0px, 0px);"></div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-6 my-3 bg-white border border-gray-200 rounded-md shadow-sm col-span-full dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center mb-5">
                        <div class="flex flex-col">
                            <h3 class="text-base font-semibold dark:text-white">이미지 파일</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="image_resize_id" class="block mb-2 text-md font-medium text-gray-900 dark:text-white items-center">
                                이미지 resize
                                <span class="text-sm text-yellow-700 dark:text-yellow-500 ml-2">
                                    <a href="https://www.php.net/imagick" target="_blank" class="hover:underline">(선행조건 : ImageMagick 모듈 설치)</a>
                                </span>
                            </label>
                            <input v-model="form.image_resize" id="image_resize-1" type="radio" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <label for="image_resize-1" class="ms-1 text-md font-medium text-gray-900 dark:text-gray-300 ml-1">사용함</label>
                            <input v-model="form.image_resize" id="image_resize-0" type="radio" value="0" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600 ml-3">
                            <label for="image_resize-0" class="ms-1 text-md font-medium text-gray-900 dark:text-gray-300 ml-1 mr-3">사용안함</label>
                            <label for="image_width_max" class="text-md font-medium text-gray-900 dark:text-white mr-1">가로 최대</label>
                            <input type="number" v-model="form.image_width_max" id="image_width_max" class="rounded-s-md bg-gray-50 border text-gray-900 focus:border-blue-500 w-[56px] sm:w-[80px] text-md border-gray-300 p-[6px] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="1280">
                            <div class="inline-flex rounded-e-md bg-gray-200 border text-gray-900 focus:border-blue-500 w-[34px] text-md border-gray-300 p-[7px] -ml-1 dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500">px</div>
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
