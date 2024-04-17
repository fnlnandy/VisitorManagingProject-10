<template>
    <canvas id="myChart"></canvas>
</template>

<script setup>
    import Chart from 'chart.js/auto';
    import { onMounted } from 'vue';

    const labels = ['Total', 'Min', 'Max'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Foo',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [40, 80, 12]
        }]
    };
    const config = {
        type: 'bar',
        data: data,
        options: {}
    }

    onMounted(() => {
        new Chart(
            document.getElementById("myChart"),
            config
        );
    });
</script>

<style></style>