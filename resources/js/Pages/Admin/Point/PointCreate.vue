<script setup lang="ts">
import debounce from 'lodash.debounce';
import axios from 'axios';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import Radio from "@/Components/Form/Radio.vue";
import ButtonColor from '@/Components/ButtonColor.vue';
import { refInputClasses } from "@/utils/helper";
import { User, UserGroup } from "@/types/user";

const props = defineProps<{
    userGroups: UserGroup[];
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const form = useForm({
    point_type: 'P',
    amount: 0,
    target_user: 'g',
    group_level: '',
    selected_user_ids: [],
    reason: '',
});

const searchString = ref('');
const searchUsers = ref<User[]>([]);
const searchUser = async () => {
    if (searchString.value.length < 1) return;
    const response = await axios.get(route('admin.user.search'), {
        params: { 
            searchString: searchString.value,
            fromWhere: 'point'
        }
    });
    searchUsers.value = response.data;
};
const debouncedSearch = debounce(searchUser, 300);

const submit = () => {
    form.post(route('admin.point.create'), {
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
                    <InputLabel for="point_type" class="block mb-2">지급/차감</InputLabel>
                    <Radio v-model="form.point_type" value="P" id="point_type-1" label="지급" />
                    <Radio v-model="form.point_type" value="M" id="point_type-0" label="차감" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="amount" value="지급 금액" class="block mb-2" />
                    <div class="relative flex items-center max-w-[10rem]">
                        <div :class="refInputClasses('gray-6') + 'inline-flex rounded-s-md w-[34px] text-sm p-[6px] -ml-1'">
                            <Icon v-if="form.point_type === 'P'" icon="plus" class="w-5 h-5 text-gray-700 dark:text-gray-300" />
                            <Icon v-else icon="minus" class="w-5 h-5 text-gray-700 dark:text-gray-300" />
                        </div>
                        <input 
                            type="number" 
                            v-model="form.amount" 
                            id="amount" 
                            @keydown.enter.prevent
                            :class="refInputClasses('gray-7') + 'text-sm w-[50px] sm:w-[80px] p-[6px] focus:ring-0'"
                        />
                        <div :class="refInputClasses('gray-6') + 'inline-flex rounded-e-md w-[34px] text-sm p-[6px] -ml-1'">PT</div>
                    </div>
                    <InputError :message="form.errors.amount" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="target_user" class="block mb-2">대상 회원</InputLabel>
                    <Radio v-model="form.target_user" value="g" id="target_user-1" label="회원그룹" />
                    <Radio v-model="form.target_user" value="p" id="target_user-0" label="회원검색" addClass="ml-4" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <div v-if="form.target_user === 'g'">
                        <Selectbox 
                            class="py-[6px]"
                            v-model="form.group_level"
                            :placeholder="'회원그룹 선택'"
                            :options="props.userGroups.map(group => ({ value: group.level.toString(), name: group.name + ' (' + group.users_count + '명)' }))"
                        />
                        <InputError :message="form.errors.group_level" />
                    </div>
                    <div v-else-if="form.target_user === 'p'" class="relative w-full">
                        <input 
                            v-model="searchString" 
                            @input="debouncedSearch"
                            @keydown.enter.prevent
                            type="text" 
                            id="searchString" 
                            class="block py-[6px] pr-[30px] w-full text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                            placeholder="닉네임 or 이메일"
                        />
                        <button 
                            type="button" 
                            @click="searchUser"
                            class="absolute top-0 end-0 px-3 py-[7px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700"
                        >
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">검색</span>
                        </button>
                        <InputError :message="form.errors.selected_user_ids" />
                    </div>
                    <div v-if="searchUsers.length > 0">
                        <div class="rounded max-h-60 overflow-auto">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                <li v-for="(user, uidx) in searchUsers" :key="uidx" class="flex items-center text-sm text-gray-900 dark:text-white p-2">
                                    <input v-model="form.selected_user_ids" :value="user.id" :id="`checkbox-${user.id}`" type="checkbox" class="w-4 h-4 mr-2 border-gray-300 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                    <label :for="`checkbox-${user.id}`" class="mr-2"></label>{{ user.nickname }} ({{ user.email }})
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-1 text-md text-yellow-700 dark:text-yellow-500">
                        선택 후 대상 회원 중 일부가 휴면회원 및 탈퇴회원으로 전환된 경우 해당 회원은 지급/차감 대상에서 제외됩니다.
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <textarea 
                        v-model="form.reason"
                        class="h-20 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:border-blue-500 block w-full p-[7px] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                    ></textarea>
                </div>
            </div>
        </div>
        <div class="p-6 mt-2 rounded-b border-gray-200 border-t dark:border-gray-600">
            <div class="flex justify-between">
                <ButtonColor type="button" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal(false)" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>등록하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>
