import { ref } from 'vue';
import { getTheme } from "@/theme";

interface ChartColors {
    borderColor: string;
    labelColor: string;
    opacityFrom: number;
    opacityTo: number;
}

interface TrafficChannelsChartColors {
    strokeColor: string;
}

const mainChartColors = ref<ChartColors>({
    borderColor: '',
    labelColor: '',
    opacityFrom: 0,
    opacityTo: 0
});

export const getMainChartOptions = () => {
    if (getTheme() === 'dark') {
        mainChartColors.value = {
            borderColor: '#374151',
            labelColor: '#9CA3AF',
            opacityFrom: 0,
            opacityTo: 0.15,
        };
    } else {
        mainChartColors.value = {
            borderColor: '#F3F4F6',
            labelColor: '#6B7280',
            opacityFrom: 0.45,
            opacityTo: 0,
        }
    }

    return {
        chart: {
            height: 420,
            type: 'area',
            fontFamily: 'Inter, sans-serif',
            foreColor: mainChartColors.value.labelColor,
            toolbar: {
                show: false
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                enabled: true,
                opacityFrom: mainChartColors.value.opacityFrom,
                opacityTo: mainChartColors.value.opacityTo
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif',
            },
        },
        grid: {
            show: true,
            borderColor: mainChartColors.value.borderColor,
            strokeDashArray: 1,
            padding: {
                left: 35,
                bottom: 15
            }
        },
        series: [
            {
                name: 'Revenue',
                data: [6356, 6218, 6156, 6526, 6356, 6256, 6056],
                color: '#1A56DB'
            },
            {
                name: 'Revenue (previous period)',
                data: [6556, 6725, 6424, 6356, 6586, 6756, 6616],
                color: '#FDBA8C'
            }
        ],
        markers: {
            size: 5,
            strokeColors: '#ffffff',
            hover: {
                size: undefined,
                sizeOffset: 3
            }
        },
        xaxis: {
            categories: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
            labels: {
                style: {
                    colors: [mainChartColors.value.labelColor],
                    fontSize: '14px',
                    fontWeight: 500,
                },
            },
            axisBorder: {
                color: mainChartColors.value.borderColor,
            },
            axisTicks: {
                color: mainChartColors.value.borderColor,
            },
            crosshairs: {
                show: true,
                position: 'back',
                stroke: {
                    color: mainChartColors.value.borderColor,
                    width: 1,
                    dashArray: 10,
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: [mainChartColors.value.labelColor],
                    fontSize: '14px',
                    fontWeight: 500,
                },
                formatter: function (value: number): string { 
                    return '$' + value;
                }
            },
        },
        legend: {
            fontSize: '16px',
            fontWeight: 500,
            labels: {
                colors: [mainChartColors.value.labelColor],
            },
            itemMargin: {
                horizontal: 10,
            }
        },
        responsive: [
            {
                breakpoint: 1024,
                options: {
                    xaxis: {
                        labels: {
                            show: false
                        }
                    }
                }
            }
        ]
    };
}

export const getNewProductsChartOptions = () => {
    return {
        colors: ['#1A56DB', '#FDBA8C'],
        series: [
            {
                name: 'Quantity',
                color: '#1A56DB', 
                data: [
                    { x: '01 Feb', y: 170 },
                    { x: '02 Feb', y: 180 },
                    { x: '03 Feb', y: 164 },
                    { x: '04 Feb', y: 145 },
                    { x: '05 Feb', y: 194 },
                    { x: '06 Feb', y: 170 },
                    { x: '07 Feb', y: 155 },
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: 140,
            fontFamily: 'Inter, sans-serif',
            foreColor: '#4B5563',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '90%',
                borderRadius: 3
            }
        },
        tooltip: {
            shared: false,
            intersect: false,
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif'
            },
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 1
                }
            }
        },
        stroke: {
            show: true,
            width: 5,
            colors: ['transparent']
        },
        grid: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            floating: false,
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        yaxis: {
            show: false
        },
        fill: {
            opacity: 1
        }
    }
}

export const getSalesByCategoryChartOptions = () => {
    return {
        colors: ['#1A56DB', '#FDBA8C'],
        series: [
            {
                name: 'Desktop PC',
                color: '#1A56DB',
                data: [
                    { x: '01 Feb', y: 170 },
                    { x: '02 Feb', y: 180 },
                    { x: '03 Feb', y: 164 },
                    { x: '04 Feb', y: 145 },
                    { x: '05 Feb', y: 194 },
                    { x: '06 Feb', y: 170 },
                    { x: '07 Feb', y: 155 },
                ]
            },
            {
                name: 'Phones',
                color: '#FDBA8C', 
                data: [
                    { x: '01 Feb', y: 120 },
                    { x: '02 Feb', y: 294 },
                    { x: '03 Feb', y: 167 },
                    { x: '04 Feb', y: 179 },
                    { x: '05 Feb', y: 245 },
                    { x: '06 Feb', y: 182 },
                    { x: '07 Feb', y: 143 }
                ]
            },
            {
                name: 'Gaming/Console',
                color: '#17B0BD',
                data: [
                    { x: '01 Feb', y: 220 },
                    { x: '02 Feb', y: 194 },
                    { x: '03 Feb', y: 217 },
                    { x: '04 Feb', y: 279 },
                    { x: '05 Feb', y: 215 },
                    { x: '06 Feb', y: 263 },
                    { x: '07 Feb', y: 183 }
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: 420,
            fontFamily: 'Inter, sans-serif',
            foreColor: '#4B5563',
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '90%',
                borderRadius: 3
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif'
            },
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 1
                }
            }
        },
        stroke: {
            show: true,
            width: 5,
            colors: ['transparent']
        },
        grid: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            floating: false,
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        yaxis: {
            show: false
        },
        fill: {
            opacity: 1
        }
    }
}

export const getSignupsChartOptions = () => {
    const signupsChartColors = {
        backgroundBarColors: getTheme() === 'dark' 
            ? ['#374151', '#374151', '#374151', '#374151', '#374151', '#374151', '#374151']
            : ['#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB']
    };

    return {
        series: [{
            name: 'Users',
            data: [1334, 2435, 1753, 1328, 1155, 1632, 1336]
        }],
        labels: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
        chart: {
            type: 'bar',
            height: 140,
            foreColor: '#4B5563',
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            }
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#1A56DB'
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '25%',
                borderRadius: 3,
                colors: {
                    backgroundBarColors: signupsChartColors.backgroundBarColors,
                    backgroundBarRadius: 3
                },
            },
            dataLabels: {
                hideOverflowingLabels: false
            }
        },
        xaxis: {
            floating: false,
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif'
            }
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 0.8
                }
            }
        },
        fill: {
            opacity: 1
        },
        yaxis: {
            show: false
        },
        grid: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
    };
}

export const getTrafficChannelsChartOptions = () => {
    const trafficChannelsChartColors: TrafficChannelsChartColors = {
        strokeColor: getTheme() === 'dark' ? '#1f2937' : '#ffffff'
    };

    return {
        series: [70, 5, 25],
        labels: ['Desktop', 'Tablet', 'Phone'],
        colors: ['#16BDCA', '#FDBA8C', '#1A56DB'],
        chart: {
            type: 'donut',
            height: 400,
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false
            },
        },
        responsive: [{
            breakpoint: 430,
            options: {
                chart: {
                    height: 300
                }
            }
        }],
        stroke: {
            colors: [trafficChannelsChartColors.strokeColor]
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 0.9
                }
            }
        },
        tooltip: {
            shared: true,
            followCursor: false,
            fillSeriesColor: false,
            inverseOrder: true,
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif'
            },
            x: {
                show: true,
                formatter: function (_: any, { seriesIndex, w }: { seriesIndex: number, w: any }) {
                    const label = w.config.labels[seriesIndex];
                    return label;
                }
            },
            y: {
                formatter: function (value: number) {
                    return value + '%';
                }
            }
        },
        grid: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
    };
} 