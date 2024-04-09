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

<script>
    export default {
        name: 'VisitorsList'
    }
</script>

<style>

</style>