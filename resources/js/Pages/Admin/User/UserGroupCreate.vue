<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    name: '',
    level: '',
    comment: '',
});

const submit = () => {
    form.post(route('admin.user.group-create'), {
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
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="name" value="그룹명" class="block mb-2" />
                    <TextInput type="text" id="name" v-model="form.name" class="block w-full py-[6px]" required placeholder="그룹명을 입력하세요."/>
                    <InputError :message="form.errors.name" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="level" value="레벨" class="block mb-2" />
                    <Selectbox 
                        class="py-[6px]"
                        v-model="form.level"
                        :placeholder="'레벨 선택'"
                        :options="[
                            {value: '1', name: '1'}, {value: '2', name: '2'}, {value: '3', name: '3'}, {value: '4', name: '4'}, {value: '5', name: '5'}, {value: '6', name: '6'}
                            , {value: '7', name: '7'}, {value: '8', name: '8'}, {value: '9', name: '9'}, {value: '10', name: '10'}, {value: '11', name: '11'}
                        ]" 
                    />
                    <InputError :message="form.errors.level" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="comment" value="그룹 설명" class="block mb-2" />
                    <TextInput type="text" id="comment" v-model="form.comment" class="block w-full py-[6px]" placeholder=""/>
                    <InputError :message="form.errors.comment" />
                </div>
            </div>
        </div>
        <div class="p-6 mt-2 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>등록하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>

