<script setup>
    import {
        Chart as ChartJS,
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend
    } from 'chart.js';
    import {
        Line,
    } from 'vue-chartjs';
    import { computed } from 'vue';


    const props = defineProps({
        data: {
            type: Array,
            default: [
                [40, 39, 10, 40, 39, 80, 40, 40, 39, 10, 40, 39],
                [45, 44, 20, 45, 44, 75, 35, 35, 33, 20, 15, 15],
                [35, 30, 5, 33, 88, 64, 32, 19, 27, 41, 39, 30],
            ]
        }
    });

    ChartJS.register(
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend
    )

    const chartData = computed(() => {
        return {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Net Income',
                    backgroundColor: '#86f879',
                    borderColor: '#86f879',
                    data: props.data[0],
                },
                {
                    label: 'Expanse',
                    backgroundColor: '#f87979',
                    borderColor: '#f87979',
                    data: props.data[1],
                },
                {
                    label: 'Gross Income',
                    backgroundColor: '#2a72fc',
                    borderColor: '#2a72fc',
                    data: props.data[2],
                },
            ]
        };
    });

    const options = computed(() => {
        return {
            responsive: true,
            maintainAspectRatio: false
        };
    });
</script>

<template>
    <div style="max-height: 500px;">
        <Line :data="chartData" :options="options" height="400" />
    </div>
</template>
