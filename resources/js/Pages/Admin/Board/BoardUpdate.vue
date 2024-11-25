<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import Radio from "@/Components/Form/Radio.vue";
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { Board } from "@/types/board";

const props = defineProps<{
    board: Board | null;
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    id: props.board?.id.toString() || '',
    status: props.board?.status || true,
    table_id: props.board?.table_id || '',
    table_name: props.board?.table_name || '',
    skin: props.board?.skin || '',
    use_category: props.board?.use_category ? '1' : '0',
    category_list: props.board?.category_list || '',
    use_comment: props.board?.use_comment ? '1' : '0',
    use_rate: props.board?.use_rate ? '1' : '0',
    use_report: props.board?.use_report ? '1' : '0',
    use_tags: props.board?.use_tags ? '1' : '0',
    write_level: props.board?.write_level.toString() || '1',
});

const submit = () => {
    form.put(route('admin.board.update'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeModal(true);
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="px-6 py-3 space-y-3">
            <div class="flex items-center">
                <div class="flex flex-col">
                    <h1 class="text-base font-semibold dark:text-white">게시판 사용 여부 </h1>
                </div>
                <label for="status" class="flex cursor-pointer mr-4">
                    <input type="checkbox" v-model="form.status" id="status" class="sr-only peer" value="1" >
                    <div class="relative w-9 h-5 ml-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
                <InputError :message="form.errors.status" />
            </div>
        
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="table_id" value="게시판 ID" class="block mb-2" />
                    <TextInput type="text" id="table_id" v-model="form.table_id" class="block w-full py-[6px]" readonly disabled placeholder="게시판 ID는 2~8자 영문자만 입력 가능합니다."/>
                    <InputError :message="form.errors.table_id" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="table_name" value="게시판명" class="block mb-2" />
                    <TextInput type="text" id="table_name" v-model="form.table_name" class="block w-full py-[6px]" required placeholder="게시판명을 입력해주세요."/>
                    <InputError :message="form.errors.table_name" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="skin" value="스킨" class="block mb-2" />
                    <Selectbox 
                        class="py-[6px]"
                        v-model="form.skin"
                        :placeholder="'스킨 선택'"
                        :options="[{value: 'card', name: '카드형'}]" 
                    />
                    <InputError :message="form.errors.skin" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="use_category" class="block mb-2">카테고리 기능</InputLabel>
                    <Radio v-model="form.use_category" value="1" id="use_category-1" label="사용함" />
                    <Radio v-model="form.use_category" value="0" id="use_category-0" label="사용안함" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="category_list" value="카테고리 항목" class="block mb-2" />
                    <TextInput type="text" id="category_list" v-model="form.category_list" class="block w-full py-[6px]" placeholder="카테고리명1,카테고리명2,카테고리명3..."/>
                    <InputError :message="form.errors.category_list" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="use_comment" class="block mb-2">댓글 기능</InputLabel>
                    <Radio v-model="form.use_comment" value="1" id="use_comment-1" label="사용함" />
                    <Radio v-model="form.use_comment" value="0" id="use_comment-0" label="사용안함" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="use_rate" class="block mb-2">추천 기능</InputLabel>
                    <Radio v-model="form.use_rate" value="1" id="use_rate-1" label="사용함" />
                    <Radio v-model="form.use_rate" value="0" id="use_rate-0" label="사용안함" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="use_report" class="block mb-2">신고 기능</InputLabel>
                    <Radio v-model="form.use_report" value="1" id="use_report-1" label="사용함" />
                    <Radio v-model="form.use_report" value="0" id="use_report-0" label="사용안함" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="use_tags" class="block mb-2">태그 기능</InputLabel>
                    <Radio v-model="form.use_tags" value="1" id="use_tags-1" label="사용함" />
                    <Radio v-model="form.use_tags" value="0" id="use_tags-0" label="사용안함" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="write_level" class="block mb-2">글쓰기 등급</InputLabel>
                    <Radio v-model="form.write_level" value="1" id="write_level-1" label="회원" />
                    <Radio v-model="form.write_level" value="11" id="write_level-11" label="관리자" addClass="ml-4" />
                </div>
            </div>
        </div>
        <div class="p-6 mt-2 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>수정하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>
