<template>
    <!-- Where the base data for each visitor should be visible -->
    <div class="list-table-wrapper">
        <table border="1" class="list-table">
            <tr>
                <th>Nom</th>
                <th>Nombre de jours</th>
                <th>Tarif journalier</th>
                <th>Tarif total (TJ * NJ)</th>
            </tr>
            <tr v-for="visitor in props.visitorsArray.value" :key="visitor?.NumVisiteur" class="list-row">
                <td>{{ visitor?.Nom }}</td>
                <td>{{ visitor?.NombreJours }}</td>
                <td>{{ visitor?.TarifJournalier }}</td>
                <td>{{ visitor?.NombreJours * visitor?.TarifJournalier }}</td>
                <td><button class="list-button blue-button" @click="EditCurrent(visitor?.NumVisiteur)">
                        <p>Modifier</p>
                        <img class="button-icon" width="24" height="24" src="../assets/edit-button.png">
                    </button></td>
                <td><button class="list-button red-button" @click="DeleteCurrent(visitor?.NumVisiteur)">
                        <p>Retirer</p>
                        <img class="button-icon" width="24" height="24" src="../assets/delete-button.png">
                    </button>
                </td>
            </tr>
        </table>
        <!-- Should be a big green button, to make it obvious that
        it's an 'Add New' button -->
        <button class="list-button green-button center-button" @click="AddNewVisitor">
            <p>Ajouter</p>
            <img class="button-icon" width="24" height="24" src="../assets/add-button.png">
        </button>
    </div>


    <VisitorForm class="visitor-form" @form-data-emitted="HandleEmittedFormData" v-if="showForm"
        :currentId="ComputeCurrentId()" :currentDataToFillIn="ComputeCurrentVisitorData()" />
</template>

<script setup>
    /**
     * @brief Modules needed by this component
     */
    import VisitorForm from './VisitorForm.vue'
    import { ref, defineProps, defineEmits } from 'vue';

    /**
     * @brief Variables tracked by this component
     */
    const showForm = ref(false);
    const currentId = ref(0);
    const currentVisitorData = ref(Object);
    const isEditMode = ref(false);

    /**
     * @brief Arguments passed to this
     * component from MainApp.vue
     */
    const props = defineProps({
        visitorsArray: ref(Array),
    });

    const emits = defineEmits(['act-on-visitor-query', 'delete-visitor-query']);

    /**
     * @brief Displays the form element
     * 
     * @details Sets the variable tracked by the
     * form to decided whether to be shown or not
     * to true
     */
    function ShowForm() {
        showForm.value = true;
    }

    /**
     * @brief Hides the form element
     * 
     * @details Sets the variable tracked by the
     * form to decided whether to be shown or not
     * to false
     */
    function HideForm() {
        showForm.value = false;
    }

    /**
     * @param value
     * 
     * @brief Sets the current tracked id to value
     */
    function SetCurrentId(value) {
        currentId.value = value;
    }

    /**
     * @param object
     * 
     * @brief Sets the current tracked visitor data
     * to fill for the form
     */
    function SetCurrentVisitorData(object) {
        console.log(object);
        currentVisitorData.value = object;
    }

    /**
     * @param state
     * 
     * @brief Sets the current global boolean that
     * dictates whether we're in edit mode or not
     */
    function SetIsEditMode(state) {
        isEditMode.value = state;
    }

    /**
     * @brief Triggers all the callbacks related to
     * adding a new visitor
     * 
     * @details Displays the form to fill in data,
     * also set the current id to 0 (which in server
     * means that we're adding a new visitor)
     */
    function AddNewVisitor() {
        ShowForm();
        SetIsEditMode(false);
        SetCurrentId(0);
        SetCurrentVisitorData(FindVisitorWithIdData(0));
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
     */
    function EditCurrent(id) {
        console.log("Edit", id);
        ShowForm();
        SetIsEditMode(true);
        SetCurrentId(id);
        SetCurrentVisitorData(FindVisitorWithIdData(Number(id)));
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
     */
    function DeleteCurrent(id) {
        console.log("Delete", id);
        HideForm();
        SetIsEditMode(false);
        SetCurrentId(id);
        emits('delete-visitor-query', id);
    }

    /**
     * @param data
     * 
     * @brief Performs actions to the data emitted
     * by VisitorForm.vue
     * 
     * @details Should ultimately perform a request
     * to the server as well
     */
    function HandleEmittedFormData(data) {
        const searchResult = FindVisitorWithIdData(data?.NumVisiteur);
        let presumedId = Number(currentId.value);

        if (IsVisitorValid(searchResult)) {
            //! We're not in edit mode
            if (presumedId == 0) {
                alert("Un visiteur avec ce Numéro existe déjà.");
                return;
            }
            //! We're in edit mode, which means the id should either be
            //! the same as the actual one OR it has to be a new one
            //! no other visitor uses
            if (Number(currentId.value) != Number(data?.NumVisiteur)) {
                alert("Un visiteur avec ce Numéro existe déjà.");
                return;
            }
        }

        let emittedData = {
            IsEditMode: isEditMode.value,
            PreviousId: Number(currentId.value),
            VisitorData: data,
        };

        console.log("Emitting data to MainApp.vue", emittedData);
        emits('act-on-visitor-query', emittedData);
    }

    /**
     * @brief Computes the current tracked id
     */
    function ComputeCurrentId() {
        return currentId;
    }

    /**
     * @brief Computes the current visitor data
     */
    function ComputeCurrentVisitorData() {
        return currentVisitorData;
    }

    /**
     * @param visitorData
     * 
     * @brief Returns if a visitor's data is valid or not
     */
    function IsVisitorValid(visitorData) {
        let name = String(visitorData?.Nom);
        let daysCount = Number(visitorData?.NombreJours);
        let dailyFee = Number(visitorData?.TarifJournalier);

        if (name == "") {
            return false;
        }
        if (daysCount < 1 || daysCount > 365) {
            return false;
        }
        if (dailyFee < 100 || dailyFee > 200_000) {
            return false;
        }

        return true;
    }

    /**
     * @param id
     * 
     * @brief Finds a visitor with the specific if
     * 
     * @details Loops through the given fetchedData array
     * to try to find a matching entry
     */
    function FindVisitorWithIdData(id) {
        let retVal = {
            Nom: '',
            NombreJours: 0,
            TarifJournalier: 0
        };
        let arrayOfVisitors = props.visitorsArray.value;

        if (id == 0) return retVal;

        for (let i = 0, max = arrayOfVisitors.length; i < max; i++) {
            let visitorId = Number(arrayOfVisitors[i]?.NumVisiteur);

            if (id == visitorId) {
                retVal.Nom = arrayOfVisitors[i]?.Nom;
                retVal.NombreJours = Number(arrayOfVisitors[i]?.NombreJours);
                retVal.TarifJournalier = Number(arrayOfVisitors[i]?.TarifJournalier);

                break;
            }
        }

        return retVal;
    }
</script>

<style scoped>

    .list-table {
        border-radius: 10px;
        padding: 2px;
        background-color: #2d2d2d;
        box-shadow: 5px 5px 0px #171717;
        margin-bottom: 10px;
    }

    .list-table * {
        padding: 5px;
        border-radius: 5px;
        color: rgb(0, 34, 63);
        justify-content: center;
        align-items: center;
        align-content: center;
        background-color: #333;
        color: white;
    }

    .list-table th {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .list-row {
        transition: background-color 0.3s ease-in-out;
        text-align: center;
    }

    .list-row:hover {
        cursor: pointer;
    }

    .list-button {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        align-content: center;
        width: 100px;
        height: 40px;
        padding: 0px 20px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        border-radius: 10px;
    }

    .blue-button {
        background-color: rgb(38, 107, 255);
        box-shadow: 5px 5px 0px rgb(32, 74, 212);
    }

    .red-button {
        background-color: rgb(255, 38, 38);
        box-shadow: 5px 5px 0px rgb(212, 65, 32);
    }

    .green-button {
        background-color: rgb(17, 221, 27);
        box-shadow: 5px 5px 0px rgb(11, 187, 52);
    }

    .center-button {
        position: relative;
        left: 25%;
        right: 25%;
    }

    .list-button p {
        align-items: center;
        color: white;
        transition-duration: .3s;
        background-color: transparent;
        left: 0;
        position: absolute;
        margin-left: 5px;
    }

    .button-icon {
        position: absolute;
        right: 5px;
        transition-duration: .3s;
        background-color: transparent;
        width: 24px;
        height: 24px;
    }

    .list-button:hover p {
        color: transparent;
    }

    .list-button:hover .button-icon {
        right: 43%;
        margin: 0;
        padding: 0;
        border: none;
        transition-duration: .3s;
    }

    .list-button:active {
        transform: translate(3px, 3px);
        transition-duration: .3s;
        box-shadow: 2px 2px 0px rgb(140, 32, 212);
    }

    .visitor-form {
        position: fixed;
        right: 20px;
        top: 10px;
    }
</style>