<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { notify } from "@/Components/Toastify";
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextEditor from '@/Components/TextEditor.vue';
import { Inquiry } from "@/types/board";

const props = defineProps<{
    inquiry: Inquiry | null;
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    inquiry_id: props.inquiry?.id,
    answer_content: props.inquiry?.answer_content || '',
    process: 'temp',
});

const submit = (process: string) => {
    form.process = process;
    form.put(route('admin.board.inquiry.answer'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            if (process === 'proc') {
                closeModal(true);
            }else{
                notify(page.props.flash.status, page.props.flash.message);
            }
        }
    });
};
</script>

<template>
    <form @submit.prevent class="flex flex-col h-full max-h-[90vh] md:max-h-[80vh]">
        <div class="px-6 py-3 space-y-3 text-md text-gray-900 dark:text-gray-100 overflow-y-auto flex-grow">        
            <div class="grid grid-cols-6 gap-3 divide-y divide-gray-200 dark:divide-gray-600">
                <div class="col-span-6 sm:col-span-6">
                    카테고리 : {{ props.inquiry?.category }}
                </div>
                <div class="col-span-6 sm:col-span-6 font-semibold pt-3">
                    제목 : {{ props.inquiry?.subject }}
                </div>
                <div class="col-span-6 sm:col-span-6 pt-3">
                    <span v-html="props.inquiry?.content" class="[&>img]:my-[7px]"></span>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <TextEditor v-model="form.answer_content" :textareaMinHeight="'300'"/>
                </div>
            </div>
        </div>
        <div class="p-6 mt-auto rounded-b border-gray-200 border-t dark:border-gray-600 shrink-0">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <div>
                    <ButtonColor type="button" @click="submit('temp')" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto mr-2" :color="'gray'">임시저장</ButtonColor>
                    <ButtonColor type="button" @click="submit('proc')" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">답변완료</ButtonColor>
                </div>
            </div>
        </div>
    </form>
</template>
