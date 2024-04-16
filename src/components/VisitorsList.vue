<template>
    <!-- Where the base data for each visitor should be visible -->
    <div class="list-table-wrapper">
        <table border="1" class="list-table" id="visitors-list-table">
        <tr>
            <th>Nom</th>
            <th>Nombre de jours</th>
            <th>Tarif journalier</th>
            <th>Tarif total (TJ * NJ)</th>
        </tr>
        <tr v-for="visitor in props.visitorsArray.value" :key="visitor?.NumVisiteur">
            <td>{{ visitor?.Nom }}</td>
            <td>{{ visitor?.NombreJours }}</td>
            <td>{{ visitor?.TarifJournalier }}</td>
            <td>{{ visitor?.NombreJours * visitor?.TarifJournalier }}</td>
        </tr>
        </table>
    </div>

    <!-- Should be a big green button, to make it obvious that
    it's an 'Add New' button -->
    <div class="magnified-button-wrapper">
        <button class="magnified-button" id="add-new-visitor-button">
        +
        </button>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';

const props = defineProps({
    visitorsArray: ref(Array),
});
</script>

<style>

</style>