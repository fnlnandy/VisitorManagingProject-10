<template>
    <!-- Textual stats that should be under the table, could
    be put at an absolute position -->
    <div class="textual-stats-wrapper">
        <p class="textual-stats">Tarif total: {{ totalFees }}</p>
        <p class="textual-stats">Tarif minimal: {{ minimalFees }}</p>
        <p class="textual-stats">Tarif maximal: {{ maximalFees }}</p>
    </div>
</template>

<script setup>
    import { ref, defineProps, onMounted } from 'vue';

    const totalFees = ref(0);
    const minimalFees = ref(0);
    const maximalFees = ref(0);

    const props = defineProps({
        fetchedData: ref(Array)
    });

    onMounted(() => {
        CalculateTotalFees();
        CalculateMaximalFees();
        CalculateMinimaFees();
    });

    function CalculateImmediateFee(entry) {
        let result = entry?.NombreJours * entry?.TarifJournalier;

        return Number(result);
    }

    function CalculateTotalFees() {
        let receivedArray = props.fetchedData.value;

        for (let i = 0, max = receivedArray.length; i < max; i++) {
            totalFees.value += CalculateImmediateFee(receivedArray[i]);
        }
    }

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

    function CalculateMinimaFees() {
        let receivedArray = props.fetchedData.value;
        let currentMin = 0;

        if (receivedArray.length <= 0)
            return;

        currentMin = CalculateImmediateFee(receivedArray[0]);

        for (let i = 1, max = receivedArray.length; i < max; i++) {
            let buffer = CalculateImmediateFee(receivedArray[i]);

            if (currentMin >= buffer)
                currentMin = buffer;
        }

        minimalFees.value = currentMin;
    }
</script>

<style></style>