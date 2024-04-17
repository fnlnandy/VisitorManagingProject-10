<template>
    <canvas id="myChart"></canvas>
</template>

<script setup>
    import Chart from 'chart.js/auto';
    import { onMounted, defineProps, ref } from 'vue';

    const props = defineProps({
        generalData: ref(Array)
    });

    onMounted(() => {
        console.log("Props:", props.generalData);

        const labels = ['Total', 'Min', 'Max'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Tarif',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [CalculateTotal(), CalculateMinimum(),
                CalculateMaximum()]
            }]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {}
        }

        new Chart(
            document.getElementById("myChart"),
            config
        );
    });

    function CalculateImmediateFee(entry) {
        let result = entry?.NombreJours * entry?.TarifJournalier;

        return Number(result);
    }

    function CalculateTotal() {
        let visitorsArray = props.generalData;
        let totalFees = 0;

        for (let i = 0, max = visitorsArray.length; i < max; i++) {
            totalFees += CalculateImmediateFee(visitorsArray[i]);
        }

        return totalFees;
    }

    function CalculateMinimum() {
        let visitorsArray = props.generalData;
        let minimalFees = CalculateImmediateFee(visitorsArray[0]);

        for (let i = 1, max = visitorsArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(visitorsArray[i]);

            if (buffer <= minimalFees)
                minimalFees = buffer;
        }

        return minimalFees;
    }

    function CalculateMaximum() {
        let visitorsArray = props.generalData;
        let maximalFees = 0;

        for (let i = 0, max = visitorsArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(visitorsArray[i]);

            if (buffer >= maximalFees)
                maximalFees = buffer;
        }

        return maximalFees;
    }
</script>

<style></style>