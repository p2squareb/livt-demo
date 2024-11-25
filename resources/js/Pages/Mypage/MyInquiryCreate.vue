<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { notify } from "@/Components/Toastify";
import InputError from '@/Components/Form/InputError.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import TextEditor from '@/Components/TextEditor.vue';
import { Category } from '@/types/board';

const props = defineProps<{
    categories: Category[];
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal () {
    emit('handleModalClose', false);
}

const form = useForm({
    subject: '',
    content: '',
    category: '',
});

const submit = () => {
    form.post(route('mypage.inquiry.create'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col h-full max-h-[90vh] md:max-h-[80vh]">
        <div class="px-6 py-3 space-y-3 overflow-y-auto flex-grow">
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <Selectbox 
                        class="py-[6px]"
                        v-model="form.category"
                        :placeholder="'문의 유형 선택'"
                        :options="props.categories.map(cat => ({ value: cat.category, name: cat.category }))" 
                    />
                    <InputError :message="form.errors.category" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <TextInput type="text" id="subject" v-model="form.subject" class="block w-full py-[6px]" required placeholder="제목을 입력해주세요."/>
                    <InputError :message="form.errors.subject" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <TextEditor v-model="form.content" :textareaMinHeight="'300'"/>
                    <InputError :message="form.errors.content" />
                </div>
            </div>
        </div>
        <div class="p-6 mt-2 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>등록하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>

