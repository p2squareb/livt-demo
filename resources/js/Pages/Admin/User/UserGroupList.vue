<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { notify } from "@/Components/Toastify";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Modal from "@/Components/Modal.vue";
import ButtonColor from "@/Components/Include/ButtonColor.vue";
import UserGroupCreate from "@/Pages/Admin/User/UserGroupCreate.vue";
import UserGroupUpdate from "@/Pages/Admin/User/UserGroupUpdate.vue";
import { UserGroup } from "@/types/user";

defineOptions({
    layout: DashboardLayout,
});
const page = usePage();

const props = defineProps<{
    userGroups: UserGroup[];
}>();

const isShowModal = ref(false);
const isShowModifyModal = ref(false);
const selectedUserGroup = ref<UserGroup | null>(null);

const handleModalClose = (value: boolean, refresh: boolean = false) => {
    isShowModal.value = value;
    isShowModifyModal.value = value;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
    }
};

const openModifyModal = (userGroup: UserGroup, refresh: boolean = false) => {
    selectedUserGroup.value = userGroup;
    isShowModifyModal.value = true;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
    }
};

const deleteUserGroup = (groupId: number, usersCount: number) => {
    if (usersCount > 0) {
        notify('error', '회원이 존재하는 그룹은 삭제할 수 없습니다.');
        return;
    }
    router.delete(route('admin.user.group-delete'), {
        data: {
            groupId: groupId
        },
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            notify(page.props.flash.status, page.props.flash.message);
        },
    });
};
</script>

<template>
    <div class="px-4 py-6 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700 text-md">
        <div class="w-full">
            <Breadcrumb menu-name="회원 관리" current-location="회원 그룹" />
            <div class="sm:flex justify-end">
                <div class="sm:flex items-center  sm:space-x-2">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor 
                                @click="isShowModal = true"
                                class="inline-flex w-full px-3 py-[8.1px] text-md font-medium items-center justify-center sm:w-auto" 
                                :color="'blue'"
                            >
                                <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>
                                그룹 등록
                            </ButtonColor>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inline-block min-w-full align-middle mt-3">
                <div class="overflow-hidden shadow-sm rounded-md">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">그룹명</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">그룹 레벨</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">회원수</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">그룹 설명</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">-</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(group, gidx) in userGroups" :key="gidx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ group.name }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ group.level }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ group.users_count }}명</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ group.comment }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white space-x-2">
                                    <ButtonColor 
                                        @click="openModifyModal(group)"
                                        class="inline-flex w-full px-3 py-[5px] text-md font-medium items-center justify-center sm:w-auto" 
                                        :color="'teal'"
                                    >
                                        <Icon icon="edit" class="w-4 h-4 mr-1 -ml-1" />수정
                                    </ButtonColor>
                                    <ButtonColor 
                                        @click="deleteUserGroup(group.id, group.users_count)"
                                        class="inline-flex w-full px-3 py-[5px] text-md font-medium items-center justify-center sm:w-auto" 
                                        :color="'red'"
                                    >
                                        <Icon icon="delete" class="w-4 h-4 mr-1" />삭제
                                    </ButtonColor>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'회원 그룹 등록'">
        <UserGroupCreate @handleModalClose="handleModalClose" />
    </Modal>

    <Modal v-if="isShowModifyModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'회원 그룹 수정'">
        <UserGroupUpdate @handleModalClose="handleModalClose" :userGroup="selectedUserGroup" />
    </Modal>
</template>
