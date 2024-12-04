<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import Selectbox from '@/Components/Form/Selectbox.vue';
import ButtonColor from '@/Components/Include/ButtonColor.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { useDashboardSideMenuStore } from "@/stores/dashboard-side-menu";
import { MenuItem } from "@/types/side-menu";
import { UserGroup } from "@/types/user";

const props = defineProps<{
    userGroup: UserGroup | null;
}>();

const emit = defineEmits(['handleModalClose'])
function closeModal (refresh: boolean = false) {
    emit('handleModalClose', false, refresh);
}

const sideMenuStore = useDashboardSideMenuStore();
const menuOptions = ref<{
    id: string;
    title: string; 
    subMenus?: {id: string; title: string}[];
}[]>([]);

const allSubMenuIds: string[] = [];

sideMenuStore.menu.forEach((item: MenuItem) => {
    if (item.icon !== 'dashboard' && item.subMenu) {
        const menuOption = {
            id: item.id,
            title: item.title,
            subMenus: item.subMenu?.map(sub => ({
                id: sub.id,
                title: sub.title
            }))
        };
        menuOptions.value.push(menuOption);
        
        if (menuOption.subMenus) {
            allSubMenuIds.push(...menuOption.subMenus.map(sub => sub.id));
        }
    }
});

const checkedMenuIds = ref<string[]>([]);
if (props.userGroup?.accessible_menus) {
    checkedMenuIds.value = props.userGroup.accessible_menus.split(',').filter(id => id.trim());
}

const form = useForm({
    id: props.userGroup?.id.toString() || '',
    name: props.userGroup?.name || '',
    level: props.userGroup?.level.toString() || '',
    comment: props.userGroup?.comment || '',
    accessibleMenus: checkedMenuIds.value,
});

const submit = () => {
    form.put(route('admin.user.group-update'), {
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
                        :options="Array.from({length: 30}, (_, i) => ({
                            value: String(i + 1),
                            name: String(i + 1)
                        }))"
                    />
                    <InputError :message="form.errors.level" />
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="accessible-menus" value="접근 가능한 메뉴" class="block mb-2" />
                    <div id="accessible-menus" class="space-y-4 p-4 border border-gray-400 dark:border-gray-600 rounded-md">
                        <div v-for="menuItem in menuOptions" :key="menuItem.id" class="space-y-2">
                            <div class="border-b border-gray-400 dark:border-gray-600">
                                <span class="font-medium">
                                    <InputLabel :for="menuItem.id" :value="menuItem.title" class="block mb-2" />
                                </span>
                            </div>
                            <div v-if="menuItem.subMenus" class="grid grid-cols-3 gap-2 pl-6">
                                <div v-for="subItem in menuItem.subMenus" :key="subItem.id" class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        :id="subItem.id"
                                        :value="subItem.id"
                                        v-model="form.accessibleMenus"
                                        class="w-4 h-4 rounded focus:ring-0 focus:ring-offset-0"
                                    />
                                    <label :for="subItem.id" class="ml-2 text-sm text-gray-900 dark:text-gray-100">
                                        {{ subItem.title }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <InputError :message="form.errors.accessibleMenus" />
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
                <ButtonColor type="button" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" @click="closeModal()" :color="'gray'">
                    <Icon icon="close" class="w-4 h-4 mr-1 -ml-1"/>닫기
                </ButtonColor>
                <ButtonColor @submit.prevent="submit" class="inline-flex w-full px-4 py-[8.1px] font-medium items-center justify-center sm:w-auto" :color="'blue'">
                    <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>수정하기
                </ButtonColor>
            </div>
        </div>
    </form>
</template>

