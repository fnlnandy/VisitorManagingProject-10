<template>
    <!-- Textual stats that should be under the table, could
    be put at an absolute position -->
    <div class="textual-stats-wrapper">
        <p><u>Statistiques:</u></p>
        <p class="textual-stats">Tarif total: {{ totalFees }}</p>
        <p class="textual-stats">Tarif minimal: {{ minimalFees }}</p>
        <p class="textual-stats">Tarif maximal: {{ maximalFees }}</p>
    </div>
</template>

<script setup>
    /**
     * @brief Needed imporst
     */
    import { ref, defineProps, onMounted } from 'vue';

    /**
     * @brief Data used throughout the code
     */
    const totalFees = ref(0);
    const minimalFees = ref(0);
    const maximalFees = ref(0);

    /**
     * @brief Props
     */
    const props = defineProps({
        fetchedData: ref(Array)
    });

    /**
     * @Brief As soon as this component's mounted,
     * we have to calculate all the stats and display
     * them in text
     */
    onMounted(() => {
        CalculateTotalFees();
        CalculateMaximalFees();
        CalculateMinimaFees();
    });

    /**
     * @param entry
     * 
     * @brief Calculates a fee using the formula daysCount * dailyFee
     */
    function CalculateImmediateFee(entry) {
        let result = entry?.NombreJours * entry?.TarifJournalier;

        return Number(result);
    }

    /**
     * @brief Calculates the total fees (i.e. sum of all the visitors' fees)
     */
    function CalculateTotalFees() {
        let receivedArray = props.fetchedData.value;

        for (let i = 0, max = receivedArray.length; i < max; i++) {
            totalFees.value += CalculateImmediateFee(receivedArray[i]);
        }
    }

    /**
     * @brief Calculates the minimal fee within the visitors
     */
    function CalculateMaximalFees() {
        let receivedArray = props.fetchedData.value;
        let currentMax = 0;

        for (let i = 0, max = receivedArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(receivedArray[i])

            if (currentMax <= buffer)
                currentMax = buffer;
        }

        maximalFees.value = currentMax;
    }

    /**
     * @brief Calculates the maximal fee within the visitors
     */
    function CalculateMinimaFees() {
        let receivedArray = props.fetchedData.value;
        let currentMin = 0;

        if (receivedArray.length <= 0)
            return;

        currentMin = CalculateImmediateFee(receivedArray[0]);

        //! i = 1 because we already read the first (0) entry's value
        for (let i = 1, max = receivedArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(receivedArray[i]);

            if (currentMin >= buffer)
                currentMin = buffer;
        }

        minimalFees.value = currentMin;
    }
</script>

<style>
    .textual-stats-wrapper {
        background-color: #2d2d2d;
        color: white;
        margin: 10px;
        width: fit-content;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 5px 5px 0px #171717;
    }
</style>