<template>
    <canvas id="bar-chart"></canvas>
    <canvas id="pie-chart"></canvas>
</template>

<script setup>
    /**
     * @brief Needed imports
     */
    import Chart from 'chart.js/auto';
    import { onMounted, defineProps, ref } from 'vue';

    /**
     * @brief props
     */
    const props = defineProps({
        generalData: ref(Array)
    });

    /**
     * @brief Immediately loads the chart's data
     */
    onMounted(() => {
        console.log("Props:", props.generalData);

        const labels = ['Total', 'Max', 'Min'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Tarif',
                backgroundColor: ['rgb(0, 128, 255)',
                    'rgb(255, 165, 0)', 'rgb(144, 238, 144)'],
                borderColor: ['rgb(0, 128, 255)',
                    'rgb(255, 165, 0)', 'rgb(144, 238, 144)'],
                data: [
                    CalculateTotal(),
                    CalculateMaximum(),
                    CalculateMinimum()
                ]
            }
            ]
        };

        const barChartConfig = {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    },
                    tooltip: {
                        titleColor: 'white',
                        bodyColor: 'white'
                    }
                }
            }
        };

        const pieChartConfig = {
            type: 'pie',
            data: data,
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    },
                    tooltip: {
                        titleColor: 'white',
                        bodyColor: 'white'
                    }
                }
            }
        };

        new Chart(
            document.getElementById("bar-chart"),
            barChartConfig
        );

        new Chart(
            document.getElementById("pie-chart"),
            pieChartConfig
        );
    });

    /**
     * @param entry
     * 
     * @brief Calculates the fee using the formula daysCount * dailyFee
     * 
     * @note Is redundant as it has a clone in TextualStats.vue; however,
     * ref() doesn't need dereferencing in this page ? (Why ?)
     */
    function CalculateImmediateFee(entry) {
        let result = entry?.NombreJours * entry?.TarifJournalier;

        return Number(result);
    }

    /**
     * @brief Calculates the total fee and returns in
     * 
     * @note Is redundant as it has a clone in TextualStats.vue
     */
    function CalculateTotal() {
        let visitorsArray = props.generalData;
        let totalFees = 0;

        for (let i = 0, max = visitorsArray.length; i < max; i++) {
            totalFees += CalculateImmediateFee(visitorsArray[i]);
        }

        return totalFees;
    }

    /**
     * @brief Calculates the minimal fee within the visitors and returns it
     * 
     * @note Is redundant as it has a clone in TextualStats.vue
     */
    function CalculateMinimum() {
        let visitorsArray = props.generalData;
        let minimalFees = CalculateImmediateFee(visitorsArray[0]);

        //! i = 1 because we've already read the first (0) entry's data
        for (let i = 1, max = visitorsArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(visitorsArray[i]);

            if (buffer <= minimalFees)
                minimalFees = buffer;
        }

        return minimalFees;
    }

    /**
     * @brief Calculates the maximal fee within the visitors and returns it
     * 
     * @note Is redundant as it has a clone in TextualStats.vue
     */
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

<style scoped>
    canvas {
        float: left;
        max-height: 1000px;
        max-width: 1000px;
        background-color: #2d2d2d;
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 5px 5px 0px #171717;
    }
</style>