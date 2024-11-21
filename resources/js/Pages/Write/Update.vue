<script setup lang="ts">
import { onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Selectbox from "@/Components/Selectbox.vue";
import TextEditor from "@/Components/TextEditor.vue";
import TagInput from "@/Components/TagInput.vue";
import ButtonColor from '@/Components/ButtonColor.vue';
import { Board, Write } from "@/types/board";
import { goBack } from "@/utils/helper";
defineOptions({
    layout: AppLayout,
});

const props = defineProps<{
    board: Board;
    write: {
        data: Write;
    };
}>();

const categoryOptions = [
    { value: '', name: '카테고리 선택' },
    ...props.board.category_list.split(',').map(category => ({ value: category, name: category }))
];

const form = useForm({
    category: props.write.data.categories,
    subject: props.write.data.subject,
    content: props.write.data.content,
    tags: props.write.data.tags ?? '',
});

const submit = () => {
    form.put(route('write.update', { tableId: props.board.table_id, writeId: props.write.data.id }), {
        onSuccess: () => {
            router.visit(route('write.read', { tableId: props.board.table_id, writeId: props.write.data.id }));
        },
    });
};

const cancelModify = () => {
    router.visit(route('write.read', { tableId: props.board.table_id, writeId: props.write.data.id }));
};
</script>

<template>
    <div class="w-full max-w-4xl mx-auto text-md">
        <div class="pb-3">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                <Link :href="route('write.list', {tableId: props.board.table_id})" class="hover:text-blue-500">{{ props.board.table_name }}</Link>
            </h1>
        </div>
        <form @submit.prevent="submit">
            <div class="px-1 space-y-6">
                <div class="grid grid-cols-6 gap-6 mt-6">
                    <div class="col-span-6 sm:col-span-6">
                        <Selectbox 
                            class="py-[10px]"
                            v-model="form.category"
                            :options="categoryOptions"
                        />
                        <InputError :message="form.errors.category" />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <TextInput type="text" id="nickname" v-model="form.subject" class="block w-full py-[10px]" required placeholder="제목을 입력해주세요." />
                        <InputError :message="form.errors.subject" />
                    </div>
                    
                    <div class="col-span-6 sm:col-span-6">
                        <TextEditor v-model="form.content" :textareaMinHeight="'300'"/>
                        <InputError :message="form.errors.content" />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <TagInput v-model="form.tags" />
                    </div>
                </div> 
                <div class="flex justify-between items-center pb-6">
                    <ButtonColor 
                        type="button"
                        @click="cancelModify"  
                        class="inline-flex w-full px-5 py-[12px] text-md font-medium text-center items-center justify-center sm:w-auto" 
                        :color="'gray'"
                    >
                        <Icon icon="close" class="w-5 h-5 -ml-1 mr-1 text-gray-700 dark:text-gray-300" />취소하기
                    </ButtonColor>
                    <ButtonColor 
                        @submit.prevent="submit"
                        class="inline-flex w-full px-5 py-[12px] text-md font-medium text-center justify-center sm:w-auto" 
                        :color="'blue'"
                    >
                        <Icon icon="edit" class="w-5 h-5 -ml-1 mr-1 text-gray-700 dark:text-gray-300"/>수정하기
                    </ButtonColor>
                </div>
            </div>
        </form>
    </div>
</template>
