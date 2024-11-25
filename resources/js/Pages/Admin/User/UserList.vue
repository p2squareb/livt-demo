<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { router, usePage } from '@inertiajs/vue3';
import { notify } from "@/Components/Toastify";
import debounce from 'lodash.debounce';
import dayjs from "dayjs";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import Selectbox from "@/Components/Form/Selectbox.vue";
import Checkbox from "@/Components/Form/Checkbox.vue";
import ButtonColor from "@/Components/ButtonColor.vue";
import PaginationLinks from "@/Components/PaginationLinks.vue";
import UserCreate from "@/Pages/Admin/User/UserCreate.vue";
import PointCreate from "@/Pages/Admin/Point/PointCreate.vue";
import UserProhibitUpdate from "@/Pages/Admin/User/UserProhibitUpdate.vue";
import UserInfo from "@/Pages/Admin/User/UserInfo.vue";
import Modal from "@/Components/Modal.vue";
import { UserGroup, PaginatedUserList, User } from "@/types/user";
import { useAllCheckboxes } from '@/utils/helper';

defineOptions({
    layout: DashboardLayout,
});

const page = usePage();
const props = defineProps<{
    userList: PaginatedUserList;
    userGroupList: UserGroup[];
    request: Record<string, string>;
}>();

const { selectedItemIds: selectedUsers, allChecked, toggleAllCheckboxes, toggleCheckbox } = useAllCheckboxes(computed(() => props.userList.data));
const isShowModal = ref(false);
const isShowInfoModal = ref(false);
const isShowPointModal = ref(false);
const isShowProhibitModal = ref(false);
const selectedUser = ref<User | null>(null);

const handleModalClose = (value: boolean, refresh: boolean = false) => {
    isShowModal.value = value;
    isShowInfoModal.value = value;
    isShowPointModal.value = value;
    isShowProhibitModal.value = value;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
        selectedUsers.value = [];
    }
};

const openInfoModal = (user: User) => {
    selectedUser.value = user;
    isShowInfoModal.value = true;
};

const pageRows = ref(props.request.pageRows || props.userList.per_page.toString());
const group = ref(props.request.group || '');
const status = ref(props.request.status || '');
const searchString = ref(props.request.searchString || '');
const updateSearch = () => {
    router.get(route('admin.user.list'), {
        pageRows: pageRows.value,
        group: group.value,
        status: status.value,
        searchString: searchString.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
const debouncedUpdateSearch = debounce(updateSearch, 500);
watch([pageRows, group, status], updateSearch);

const newGroup = ref('');
const changeUserGroup = () => {
    if (selectedUsers.value.length === 0 || !newGroup.value) {
        notify('warning', '회원을 선택하고 변경할 그룹을 선택해주세요.');
        return false;
    }
    router.post(route('admin.user.changeGroup'), {
        newGroup: newGroup.value,
        selectedUsersIds: selectedUsers.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            notify(page.props.flash.status, page.props.flash.message);
            selectedUsers.value = [];
            newGroup.value = '';
            updateSearch();
        }
    });
};

const makeUserProhibit = () => {
    if (selectedUsers.value.length === 0) {
        notify('warning', '이용정지할 회원을 선택해주세요.');
        return false;
    }
    isShowProhibitModal.value = true;
};

const getSelectedUserNicknames = () => {
    return props.userList.data
        .filter(user => selectedUsers.value.includes(user.id))
        .map(user => user.nickname)
        .join(', ');
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 block sm:flex items-center justify-between text-md">
        <div class="w-full">
            <Breadcrumb menu-name="회원 관리" current-location="회원 리스트" />
            <div class="sm:flex justify-between">
                <div class="items-center mr-0 sm:mr-2 mb-3 sm:flex sm:space-x-2 sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="pageRows" 
                                :options="[{value: '7', name: '15개'}, {value: '30', name: '30개'}, {value: '50', name: '50개'}, {value: '100', name: '100개'}]" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="group"
                                :placeholder="'그룹명'"
                                :options="userGroupList.map(g => ({ value: g.level.toString(), name: g.name }))"
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="status" 
                                :placeholder="'회원상태'"  
                                :options="[{value: '1', name: '정상'}, {value: '2', name: '탈퇴'}, {value: '3', name: '정지'}, {value: '4', name: '휴면'}]" 
                            />
                        </div>
                    </div>
                </div>
                <div class="sm:flex items-center sm:space-x-2">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <input 
                                v-model="searchString" 
                                @input="debouncedUpdateSearch"
                                @keyup.enter="updateSearch"
                                type="search" 
                                id="searchString" 
                                class="block p-[6px] w-full md:w-[270px] text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                placeholder="이메일+닉네임을 입력해주세요."
                            />
                            <button 
                                type="button" 
                                @click="updateSearch"
                                class="absolute top-0 end-0 px-3 py-[7px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <span class="sr-only">검색</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor 
                                @click="isShowModal = true"
                                class="inline-flex w-full px-3 py-[7px] text-md font-medium items-center justify-center sm:w-auto" 
                                :color="'blue'"
                            >
                                <svg class="w-6 h-6 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                                회원 등록
                            </ButtonColor>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:flex mt-3">
                <div class="sm:flex items-center sm:space-x-2">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="newGroup"
                                :placeholder="'변경할 그룹 선택'"
                                :options="userGroupList.map(g => ({ value: g.level.toString(), name: g.name }))"
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor 
                                @click="changeUserGroup"  
                                class="inline-flex w-full px-3 py-[8.1px] text-md font-medium text-center justify-center sm:w-auto" 
                                :color="'indigo'"
                            >그룹 변경</ButtonColor>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center mb-3 md:mb-0 dark:text-gray-700">|</div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor 
                                @click="makeUserProhibit"  
                                class="inline-flex w-full px-3 py-[8.1px] text-md font-medium text-center justify-center sm:w-auto" 
                                :color="'indigo'"
                            >이용정지</ButtonColor>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center mb-3 md:mb-0 dark:text-gray-700">|</div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor 
                                @click="isShowPointModal = true"  
                                class="inline-flex w-full px-3 py-[8.1px] text-md font-medium text-center justify-center sm:w-auto" 
                                :color="'indigo'"
                            >포인트 지급/차감</ButtonColor>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inline-block min-w-full align-middle mt-3">
                <div class="overflow-hidden shadow-sm rounded-md">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="p-3">
                                    <div class="flex items-center">
                                        <Checkbox id="checkbox-all" @change="toggleAllCheckboxes" :checked="allChecked" />
                                    </div>
                                </th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">회원</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">그룹</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white min-w-[100px]">가입유형</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white min-w-[80px]">포인트</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white min-w-[100px]">가입일</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">상태</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">최근 로그인 / 접속 IP</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">-</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(user, uidx) in userList.data" :key="uidx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="w-4 p-3">
                                    <div class="flex items-center">
                                        <Checkbox :id="`checkbox-${user.id}`" @change="toggleCheckbox(user.id)" :checked="selectedUsers.includes(user.id)" />
                                    </div>
                                </td>
                                <td class="flex items-center p-3 space-x-3 whitespace-nowrap">
                                    <img v-if="user.profile_photo_path" class="w-8 h-8 rounded-full" :src="`/storage/profiles/${user.profile_photo_path}`" :alt="`${user.nickname}의 아바타`">
                                    <svg v-else class="w-8 h-8 text-gray-800 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                                    </svg>
                                    <div class="text-md font-normal text-gray-500 dark:text-gray-400">
                                        <div class="text-md font-semibold text-gray-900 dark:text-white">{{ user.nickname }}</div>
                                        <div class="text-md font-normal text-gray-500 dark:text-gray-400">{{ user.email }}</div>
                                    </div>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ user.group.name }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ user.provider }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ user.point }}pt</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ dayjs(user.created_at).format('YYYY.MM.DD') }}</td> 
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="flex flex-row items-center justify-center">
                                        <div v-if="user.status === 1" class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div>
                                        <div v-else-if="user.status === 2" class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                                        <div v-else-if="user.status === 3" class="h-2.5 w-2.5 rounded-full bg-orange-400 mr-2"></div>
                                        <div v-else-if="user.status === 4" class="h-2.5 w-2.5 rounded-full bg-neutral-500 mr-2"></div>
                                        {{ user.status === 1 ? '정상' : user.status === 2 ? '탈퇴' : user.status === 3 ? '정지' : user.status === 4 ? '휴면' : '' }} 
                                    </div>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ dayjs(user.last_login_at).format('YYYY.MM.DD HH:mm') }}<br>{{ user.login_ip }}
                                </td>
                                <td class="p-3 text-center whitespace-nowrap">
                                    <ButtonColor @click="openInfoModal(user)" class="inline-flex w-full px-3 py-[5px] text-md font-medium items-center justify-center sm:w-auto" :color="'teal'">
                                        <Icon icon="edit" class="w-4 h-4 mr-1 -ml-1" />상세
                                    </ButtonColor>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <PaginationLinks :paginator="userList" />
    
    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'2xl'" :position="'center'" :title="'회원 등록'">
        <UserCreate @handleModalClose="handleModalClose" />
    </Modal>

    <Modal v-if="isShowInfoModal" @handleModalClose="handleModalClose" :size="'xl'" :position="'center'" :title="'회원 정보'">
        <UserInfo @handleModalClose="handleModalClose" :userInfo="selectedUser" />
    </Modal>

    <Modal v-if="isShowPointModal" @handleModalClose="handleModalClose" :size="'md'" :position="'center'" :title="'포인트 지급/차감 등록'">
        <PointCreate @handleModalClose="handleModalClose" :userGroups="props.userGroupList" />
    </Modal>

    <Modal v-if="isShowProhibitModal" @handleModalClose="handleModalClose" :size="'md'" :position="'center'" :title="'이용정지 등록'">
        <UserProhibitUpdate 
            @handleModalClose="handleModalClose"
            :selectedUserIds="selectedUsers.join(',')"
            :selectedUserNicknames="getSelectedUserNicknames()"
        />
    </Modal>

</template>

