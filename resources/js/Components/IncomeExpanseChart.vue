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
    import {computed, onMounted, ref} from 'vue';

    const props = defineProps({
        data: {
            type: Array,
            default: [
                [], [], []
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

    const loaded = ref(false);
    const chartData = ref({
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
    });

    const options = computed(() => {
        return {
            responsive: true,
            maintainAspectRatio: false
        };
    });

    onMounted(() => {
        fetch(route('statistics'))
            .then(response => response.json())
            .then(({statistics}) => {
                chartData.value.datasets[0].data = statistics[0];
                chartData.value.datasets[1].data = statistics[1];
                chartData.value.datasets[2].data = statistics[2];
                loaded.value = true;
            })
    });
</script>

<template>
    <div style="max-height: 500px;">
        <Line v-if="loaded" :data="chartData" :options="options" height="400" />
    </div>
</template>
