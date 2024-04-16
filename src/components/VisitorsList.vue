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
            <td @click="EditCurrent(visitor?.NumVisiteur)">Edit button</td>
            <td @click="DeleteCurrent(visitor?.NumVisiteur)">Delete button</td>
        </tr>
        </table>
    </div>

    <!-- Should be a big green button, to make it obvious that
    it's an 'Add New' button -->
    <div class="magnified-button-wrapper">
        <button class="magnified-button" id="add-new-visitor-button" @click="AddNewVisitor">
        +
        </button>
    </div>

    <VisitorForm @form-data-emitted="HandleEmittedFormData" v-if="showForm" :currentId="ComputeCurrentId()"/>
</template>

<script setup>
    import VisitorForm from './VisitorForm.vue'
    import { ref, defineProps } from 'vue';

    const showForm = ref(false);
    const currentId = ref(0);

    const props = defineProps({
        visitorsArray: ref(Array),
    });

    function ShowForm()
    {
        showForm.value = true;
    }

    function HideForm()
    {
        showForm.value = false;
    }
    
    function SetCurrentId(value)
    {
        currentId.value = value;
    }

    function AddNewVisitor()
    {
        ShowForm();
        SetCurrentId(0);
    }

    function EditCurrent(id)
    {
        console.log("Edit", id);
        ShowForm();
        SetCurrentId(id);
    }

    function DeleteCurrent(id)
    {
        console.log("Delete", id);
        HideForm();
        SetCurrentId(0);
    }

    function HandleEmittedFormData(data)
    {
        console.log(data);
    }

    function ComputeCurrentId()
    {
        return currentId;
    }
</script>

<style>

</style>