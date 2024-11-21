<script setup lang="ts">
import { onMounted } from 'vue';
import ApexCharts from 'apexcharts';
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Sample1 from './Dashboard/Sample1.vue';
import Sample2 from './Dashboard/Sample2.vue';
import Sample3 from './Dashboard/Sample3.vue';
import Sample5 from './Dashboard/Sample5.vue';
import Sample6 from './Dashboard/Sample6.vue';
import { 
    getMainChartOptions,
    getNewProductsChartOptions,
    getSignupsChartOptions,
    getTrafficChannelsChartOptions 
} from '@/utils/chart';

defineOptions({
    layout: DashboardLayout,
});

onMounted(() => {
    const mainChart = new ApexCharts(document.querySelector("#main-chart"), getMainChartOptions());
    mainChart.render();

    const newProductsChart = new ApexCharts(document.querySelector("#new-products-chart"), getNewProductsChartOptions());
    newProductsChart.render();

    const signupsChart = new ApexCharts(document.querySelector("#week-signups-chart"), getSignupsChartOptions());
    signupsChart.render();

    const trafficChart = new ApexCharts(document.querySelector("#traffic-by-device"), getTrafficChannelsChartOptions());
    trafficChart.render();
});

</script>

<template>
    <div class="px-4 pt-6">
        <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
            <Sample1 />
            <Sample2 />            
        </div>
        <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-2 2xl:grid-cols-3">
            <Sample3 />
        </div>
        <div class="grid grid-cols-1 my-4 xl:grid-cols-2 xl:gap-4">
            <Sample6 />
            <Sample5 />
        </div>
    </div>
</template>
<style scoped>

</style>