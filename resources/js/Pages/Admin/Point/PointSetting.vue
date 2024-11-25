<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/Form/InputLabel.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Radio from "@/Components/Form/Radio.vue";
import { notify } from "@/Components/Toastify";
import { refInputClasses } from "@/utils/helper";

defineOptions({
    layout: DashboardLayout,
});

const props = defineProps<{
    data: SystemPoint;
}>();

const form = useForm({
    use_point_join: props.data.point.use_point_join,
    use_point_join_amt: props.data.point.use_point_join_amt,
    use_point_login: props.data.point.use_point_login,
    use_point_login_amt: props.data.point.use_point_login_amt,
    use_point_write: props.data.point.use_point_write,
    use_point_write_amt: props.data.point.use_point_write_amt,
    use_point_write_comment: props.data.point.use_point_write_comment,
    use_point_write_comment_amt: props.data.point.use_point_write_comment_amt,
    use_point_write_up: props.data.point.use_point_write_up,
    use_point_write_up_amt: props.data.point.use_point_write_up_amt,
    use_point_comment: props.data.point.use_point_comment,
    use_point_comment_amt: props.data.point.use_point_comment_amt,
    use_point_comment_up: props.data.point.use_point_comment_up,
    use_point_comment_up_amt: props.data.point.use_point_comment_up_amt,
});

const submit = () => {
    form.post(route('admin.point.setting.update'), {
        preserveScroll: true,
        onSuccess: () => {
            notify('success', '포인트 설정이 변경되었습니다.');
        },
    });
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 text-md bg-white block sm:flex items-center justify-between dark:bg-gray-900">
        <div class="w-full">
            <Breadcrumb menu-name="포인트 관리" current-location="포인트 설정" />
            <form @submit.prevent="submit">
                <div class="p-3 sm:p-6 my-3 bg-white border border-gray-200 rounded-md shadow-sm col-span-full dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center mb-5">
                        <div class="flex flex-col">
                            <h3 class="text-base font-semibold dark:text-white">기본 정보</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_join" value="회원가입시" class="block mb-2" />
                            <Radio v-model="form.use_point_join" value="1" id="use_point_join-1" label="사용함" />
                            <Radio v-model="form.use_point_join" value="0" id="use_point_join-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_join_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_join_amt" id="use_point_join_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_login" value="로그인시 (1일 1회 기준)" class="block mb-2" />
                            <Radio v-model="form.use_point_login" value="1" id="use_point_login-1" label="사용함" />
                            <Radio v-model="form.use_point_login" value="0" id="use_point_login-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_login_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_login_amt" id="use_point_login_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_write" value="글 작성시" class="block mb-2" />
                            <Radio v-model="form.use_point_write" value="1" id="use_point_write-1" label="사용함" />
                            <Radio v-model="form.use_point_write" value="0" id="use_point_write-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_write_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_write_amt" id="use_point_write_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_comment" value="댓글 작성시" class="block mb-2" />
                            <Radio v-model="form.use_point_comment" value="1" id="use_point_comment-1" label="사용함" />
                            <Radio v-model="form.use_point_comment" value="0" id="use_point_comment-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_comment_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_comment_amt" id="use_point_comment_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_write_comment" value="본인의 작성글에 타 유저가 댓글 작성시" class="block mb-2" />
                            <Radio v-model="form.use_point_write_comment" value="1" id="use_point_write_comment-1" label="사용함" />
                            <Radio v-model="form.use_point_write_comment" value="0" id="use_point_write_comment-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_write_comment_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_write_comment_amt" id="use_point_write_comment_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_write_up" value="본인의 작성글에 타 유저가 추천시" class="block mb-2" />
                            <Radio v-model="form.use_point_write_up" value="1" id="use_point_write_up-1" label="사용함" />
                            <Radio v-model="form.use_point_write_up" value="0" id="use_point_write_up-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_write_up_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_write_up_amt" id="use_point_write_up_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                        </div>
                        <div class="col-span-6 sm:col-span-2">
                            <InputLabel for="use_point_comment_up" value="본인의 댓글에 타 유저가 추천시" class="block mb-2" />
                            <Radio v-model="form.use_point_comment_up" value="1" id="use_point_comment_up-1" label="사용함" />
                            <Radio v-model="form.use_point_comment_up" value="0" id="use_point_comment_up-0" label="사용안함" addClass="ml-4" />
                            <span class="mx-3 text-gray-500 dark:text-gray-500">|</span>
                            <InputLabel for="use_point_comment_up_amt" value="지급 포인트" class="mr-2" />
                            <input type="number" v-model="form.use_point_comment_up_amt" id="use_point_comment_up_amt" 
                                :class="refInputClasses('gray-7') + 'text-sm rounded-s-md w-[50px] sm:w-[80px] p-[6px]'" />
                            <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
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
