<template>
        <!-- Textual stats that should be under the table, could
    be put at an absolute position -->
    <div class="textual-stats-wrapper">
      <p class="textual-stats">Tarif total: {{ totalFees }}</p>
      <p class="textual-stats">Tarif minimal: {{ minimalFees }}</p>
      <p class="textual-stats">Tarif maximal: {{  maximalFees  }}</p>
    </div>
</template>

<script setup>
import { ref, defineProps, onMounted } from 'vue';

const totalFees = ref(0);
const props = defineProps({
    fetchedData: ref(Array)
});

onMounted(() => {
    CalculateTotalFees();
});

function CalculateTotalFees()
{
    totalFees.value = 0;

    for(let i = 0, max = props.fetchedData.value.length; i < max; i++)
    {
        let daysCount = props.fetchedData.value[i]?.NombreJours;
        let dailyFees = props.fetchedData.value[i]?.TarifJournalier;

        totalFees.value += daysCount * dailyFees;
    }
}
</script>

<style>
</style>