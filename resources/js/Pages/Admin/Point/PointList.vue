<script setup lang="ts">
import { ref, watch } from "vue";
import { router, usePage } from '@inertiajs/vue3';
import dayjs from "dayjs";
import debounce from 'lodash.debounce';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumb from "@/Pages/Admin/Partials/Breadcrumb.vue";
import ButtonColor from "@/Components/ButtonColor.vue";
import Selectbox from "@/Components/Form/Selectbox.vue";
import PaginationLinks from "@/Components/PaginationLinks.vue";
import Modal from "@/Components/Modal.vue";
import PointCreate from "@/Pages/Admin/Point/PointCreate.vue";
import { PaginatedPointList, UserGroup } from "@/types/user";
import { formatNumberWithK } from "@/utils/helper";
import { notify } from '@/Components/Toastify';

defineOptions({
    layout: DashboardLayout,
});
const page = usePage();

const props = defineProps<{
    userGroups: UserGroup[];
    points: PaginatedPointList;
    request: Record<string, string>;
}>();

const pageRows = ref(props.request.pageRows || props.points.per_page.toString());
const pointType = ref(props.request.pointType || '');
const pagePeriod = ref(props.request.pagePeriod || '');
const searchString = ref(props.request.searchString || '');
const updateSearch = () => {
    router.get(route('admin.point.list'), {
        pageRows: pageRows.value,
        pointType: pointType.value,
        pagePeriod: pagePeriod.value,
        searchString: searchString.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
const debouncedUpdateSearch = debounce(updateSearch, 500);
watch([pageRows, pointType, pagePeriod], updateSearch);

const isShowModal = ref(false);

const handleModalClose = (value: boolean, refresh: boolean = false) => {
    isShowModal.value = value;
    if (refresh) {
        notify(page.props.flash.status, page.props.flash.message);
    }
};
</script>

<template>
    <div class="px-4 pt-6 pb-3 block sm:flex items-center justify-between text-md">
        <div class="w-full">
            <Breadcrumb menu-name="포인트 관리" current-location="포인트 내역" />
            <div class="sm:flex justify-between">
                <div class="items-center mr-0 sm:mr-2 mb-3 sm:flex sm:space-x-2 sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="pageRows" 
                                :options="[{value: '15', name: '15개'}, {value: '30', name: '30개'}, {value: '50', name: '50개'}, {value: '100', name: '100개'}]" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="pointType"
                                :placeholder="'지급/차감'"
                                :options="[{value: 'P', name: '지급'}, {value: 'M', name: '차감'}]"
                            />
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <Selectbox 
                                class="py-[6px]"
                                v-model="pagePeriod" 
                                :placeholder="'기간'"  
                                :options="[{value: '7', name: '7일'}, {value: '30', name: '30일'}, {value: '3m', name: '3개월'}, {value: '6m', name: '6개월'}, {value: '1y', name: '1년'}]" 
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
                                class="block p-[6px] pl-[13px] w-full md:w-[270px] text-md text-gray-900 bg-gray-50 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white focus:ring-0" 
                                placeholder="검색어를 입력해주세요."
                            />
                            <button 
                                type="button" 
                                @click="updateSearch"
                                class="absolute top-0 end-0 px-3 py-[7px] text-md font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-0"
                            >
                                <Icon icon="search" class="w-4 h-4" />
                                <span class="sr-only">검색</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center mb-3 md:mb-0">
                        <div class="relative w-full">
                            <ButtonColor @click="isShowModal = true" class="inline-flex w-full px-3 py-[8.1px] items-center justify-center sm:w-auto" :color="'blue'">
                                <Icon icon="plus" class="w-5 h-5 mr-1 -ml-1"/>지급/차감 등록
                            </ButtonColor>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inline-block min-w-full align-middle mt-3">
                <div class="overflow-hidden shadow-sm rounded-md">
                    <table class="min-w-full table-fixed divide-y divide-gray-300 dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">회원</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">구분</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">지급/차감</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">금액</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">지금/차감일</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">사유</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">주체</th>
                                <th scope="col" class="p-4 text-center text-gray-900 uppercase dark:text-white">등록자</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr v-for="(point, pidx) in points.data" :key="pidx" class="bg-gray-100 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700">
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ point.user.nickname }} ({{ point.user.email }})
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ 
                                        point.point_item === 'join' ? '회원가입' :
                                        point.point_item === 'login' ? '로그인' :
                                        point.point_item === 'write' ? '게시글 작성' :
                                        point.point_item === 'write_comment' ? '댓글 추가' :
                                        point.point_item === 'write_up' ? '게시글 추천' :
                                        point.point_item === 'comment' ? '댓글 작성' :
                                        point.point_item === 'comment_up' ? '댓글 추천' :
                                        point.point_item === 'admin' ? '관리자 지급/차감' : '' 
                                    }}
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <span v-if="point.point_type === 'P'" class="px-3 py-1 text-sm font-semibold rounded-md text-green-800 dark:text-green-500">지급</span>
                                    <span v-else class="px-3 py-1 text-sm font-semibold rounded-md text-pink-800 dark:text-pink-500">차감</span>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ formatNumberWithK(point.amount) }}pt</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ dayjs(point.created_at).format('YYYY.MM.DD hh:mm') }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ point.reason }}</td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <template v-if="point.to_user_id === point.from_user_id">
                                        본인
                                    </template>
                                    <template v-else>
                                        {{ point.sender.nickname }} ({{ point.sender.email }})
                                    </template>
                                </td>
                                <td class="p-3 text-center text-gray-900 whitespace-nowrap dark:text-white">{{ point.processed_by }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <PaginationLinks :paginator="points" />

    <Modal v-if="isShowModal" @handleModalClose="handleModalClose" :size="'md'" :position="'center'" :title="'포인트 지급/차감 등록'">
        <PointCreate @handleModalClose="handleModalClose" :userGroups="props.userGroups" />
    </Modal>
</template>
