<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import Radio from '@/Components/Form/Radio.vue';
import { calculateUntilDate, limitTextLengthCount } from '@/utils/helper';

const props = defineProps<{
    selectedUserIds: string;
    selectedUserNicknames: string;
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    target_user_ids: props.selectedUserIds,
    gubun: '1',
    period_type: '',
    until_date: '',
    cause: '',
});

const displayDate = ref('정지 기간을 선택해주세요.');
watch(() => form.period_type, (newValue) => {
    console.log('newValue', props.selectedUserIds);
    console.log('form', form.target_user_ids);
    const { displayDate: newDisplayDate, untilDate } = calculateUntilDate(newValue);
    displayDate.value = newDisplayDate;
    form.until_date = untilDate;
});

const charCount = ref(0);
watch(() => form.cause, (newValue) => {
    const { limitedText, count } = limitTextLengthCount(newValue);
    form.cause = limitedText;
    charCount.value = count;
});

watch(() => form.gubun, (newValue) => {
    if (newValue === '0') {
        form.period_type = '';
        form.until_date = '';
        form.cause = '';
        displayDate.value = '정지 기간을 선택해주세요.';
        charCount.value = 0;
    }
});

const submit = () => {
    form.post(route('admin.user.prohibit-update'), {
        onSuccess: () => {
            closeModal(true);
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="px-6 py-3 space-y-3 text-md">
            <div class="grid grid-cols-6 gap-3">
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="gubun" class="block mb-2">변경 대상 회원</InputLabel>
                    <span class="block p-2 bg-gray-50 border border-gray-300 rounded-md text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        {{ selectedUserNicknames }}
                    </span>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="gubun" class="block mb-2">이용정지 구분</InputLabel>
                    <Radio v-model="form.gubun" value="1" id="gubun-1" label="이용정지" />
                    <Radio v-model="form.gubun" value="0" id="gubun-0" label="이용정지 해제" addClass="ml-4" />
                    <span v-if="form.gubun === '1'" class="block ml-6 mt-1 text-sm text-yellow-700 dark:text-yellow-500">
                        회원 상태값이 정상인 경우의 회원에만 적용됩니다.
                    </span>
                    <span v-else class="block ml-6 mt-1 text-sm text-yellow-700 dark:text-yellow-500">
                        회원 상태값이 정지인 경우의 회원에만 적용됩니다.
                    </span>
                </div>
                <template v-if="form.gubun === '1'">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel for="period_type" value="정지 기간" class="block mb-2" />
                        <Selectbox 
                            class="py-[6px]"
                            v-model="form.period_type"
                            :placeholder="'기간 선택'"
                            :options="[{ value: '3', name: '3일' }, { value: '7', name: '7일' }, { value: '30', name: '30일' }, { value: '90', name: '90일' }, { value: '180', name: '180일' }, { value: 'eternity', name: '영구정지' }]"
                        />
                        <InputError :message="form.errors.period_type" />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel for="until_date" value="날짜(~까지)" class="block mb-2" />
                        <span class="block py-2 dark:text-white">{{ displayDate }}</span>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <label for="cause" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">
                            사유 ({{ charCount }}/200)
                        </label>
                        <textarea 
                            v-model="form.cause"
                            maxlength="200"
                            class="h-20 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:border-blue-500 block w-full p-[7px] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                        ></textarea>
                        <InputError :message="form.errors.cause" class="mt-1" />
                    </div>
                </template>
            </div>
        </div>
        <div class="p-6 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-3 py-[8.1px] text-md font-medium items-center justify-center sm:w-auto" @click="closeModal(false)" :color="'gray'">
                    <svg class="w-3 h-3 mr-2 -ml-1 mb-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                    </svg>
                    닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-3 py-[8.1px] text-md font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <svg class="w-5 h-5 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    등록하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>
