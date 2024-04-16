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
    /**
     * @brief Modules needed by this component
     */
    import VisitorForm from './VisitorForm.vue'
    import { ref, defineProps } from 'vue';

    /**
     * @brief Variables tracked by this component
     */
    const showForm = ref(false);
    const currentId = ref(0);

    /**
     * @brief Arguments passed to this
     * component from MainApp.vue
     */
    const props = defineProps({
        visitorsArray: ref(Array),
    });

    /**
     * @brief Displays the form element
     * 
     * @details Sets the variable tracked by the
     * form to decided whether to be shown or not
     * to true
     */
    function ShowForm()
    {
        showForm.value = true;
    }

    /**
     * @brief Hides the form element
     * 
     * @details Sets the variable tracked by the
     * form to decided whether to be shown or not
     * to false
     */
    function HideForm()
    {
        showForm.value = false;
    }
    
    /**
     * @param value
     * 
     * @brief Sets the current tracked id to value
     */
    function SetCurrentId(value)
    {
        currentId.value = value;
    }

    /**
     * @brief Triggers all the callbacks related to
     * adding a new visitor
     * 
     * @details Displays the form to fill in data,
     * also set the current id to 0 (which in server
     * means that we're adding a new visitor)
     * 
     * @todo Clear the form while showing it, implement
     * a custom callback that will also be used with v-on:reset (?)
     */
    function AddNewVisitor()
    {
        ShowForm();
        SetCurrentId(0);
    }

    /**
     * @param id
     * 
     * @brief Triggers all the callbacks related
     * to editing the current visitor
     * 
     * @details Is integrated within all the embedded
     * buttons inside the table, params are set automatically
     * thanks to v-for
     * 
     * @todo Fill in the form with the selected visitor's
     * info
     */
    function EditCurrent(id)
    {
        console.log("Edit", id);
        ShowForm();
        SetCurrentId(id);
    }

    /**
     * @param id
     * 
     * @brief Triggers all the callbacks related
     * to deleting the current visitor
     * 
     * @details Hides the form, sets the current id
     * and eventually should perform a request to the
     * server
     * 
     * @todo Actually implement the request part
     */
    function DeleteCurrent(id)
    {
        console.log("Delete", id);
        HideForm();
        SetCurrentId(id);
    }

    /**
     * @param data
     * 
     * @brief Performs actions to the data emitted
     * by VisitorForm.vue
     * 
     * @details Should ultimately perform a request
     * to the server as well
     * 
     * @todo Implement the request to the server
     */
    function HandleEmittedFormData(data)
    {
        console.log(data);
    }

    /**
     * @brief Computes the current tracked id
     */
    function ComputeCurrentId()
    {
        return currentId;
    }
</script>

<style>

</style>