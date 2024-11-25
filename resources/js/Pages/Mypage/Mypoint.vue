<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import dayjs from 'dayjs'
import ButtonColor from '@/Components/Include/ButtonColor.vue'
import { Point } from '@/types/user';

const points = ref<Point[]>([])
const sumPoint7days = ref(0)
const next_page_url = ref(null)
const currentPage = ref(1)

const loadPoints = async (page: number) => {
    const response = await axios.get(route('point.my-list'), {
        params: {
            page: currentPage.value,
        }
    });
    
    const newPointList = response.data.points.data
    if (page === 1) {
        points.value = newPointList
        sumPoint7days.value = response.data.sumPoint7days
    } else {
        points.value = [...points.value, ...newPointList]
    }

    next_page_url.value = response.data.points.next_page_url
}

const loadMore = () => {
    currentPage.value++
    loadPoints(currentPage.value)
}

onMounted(() => {
    loadPoints(currentPage.value)
})
</script>

<template>
    <div class="flex flex-col h-full max-h-[90vh] md:max-h-[80vh] text-md">
        <div class="px-6 pb-5 space-y-3 text-md text-gray-900 dark:text-gray-100 overflow-y-auto flex-grow">
            <div class="flex flex-col items-center justify-center mt-5 p-5 rounded-md bg-gray-100 dark:bg-gray-700">
                <span class="text-xl font-semibold mb-2">{{ $page.props.auth.user.point?.toLocaleString() ?? 0 }} points</span>
                <div class="flex items-center">
                    이번주 획득 포인트는
                    <Icon 
                        :icon="sumPoint7days >= 0 ? 'arrow-up' : 'arrow-down'" 
                        class="font-semibold" 
                        :class="sumPoint7days >= 0 ? 'text-green-500 w-5 h-5' : 'text-red-500 w-5 h-5'" 
                    />
                    <span 
                        class="font-semibold" 
                        :class="{'text-green-500': sumPoint7days >= 0, 'text-red-500': sumPoint7days < 0}"
                    >
                        {{ sumPoint7days.toLocaleString() ?? 0 }} point
                    </span>입니다.
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-600">
                <div v-for="(point, idx) in points" :key="idx" class="flex flex-col py-3 space-y-2">
                    <div class="flex items-center justify-start text-gray-500 dark:text-gray-400">
                        {{ dayjs(point.created_at).format('YYYY.MM.DD HH:mm') }}
                    </div>
                    <div class="flex items-center justify-between">
                        <span>{{ point.reason }}</span>
                        <span>{{ point.amount >= 0 ? '+' : '-' }}{{ point.amount }} points</span>
                    </div>
                </div>
            </div>

            <div v-if="next_page_url" class="flex justify-center mt-3">
            <ButtonColor 
                @click="loadMore" 
                class="inline-flex w-full px-5 py-[9px] text-md font-medium text-center justify-center" 
                :color="'gray'"
            >
                더보기
            </ButtonColor>
        </div>

        </div>
    </div>
</template>
