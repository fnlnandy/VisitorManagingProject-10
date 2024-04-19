<template>
    <!-- The form about the Visitor's data -->
    <form @submit.prevent.stop="CheckAndEmitData" id="visitor-form" method="post">
        <label for="visitor-id-field">Num. Visiteur:</label>
        <input v-model="formIdField" id="visitor-id-field" type="number" min="1" required>
        <label for="visitor-name-field">Nom:</label>
        <input v-model="formNameField" id="visitor-name-field" type="text" maxlength="100" required>
        <label for="visitor-days-count-field">Nombre de jours:</label>
        <input v-model="formDaysCountField" id="visitor-days-count-field" type="number" min="1" max="365" required>
        <label for="visitor-daily-fee-field">Tarif journalier:</label>
        <input v-model="formDailyFeeField" id="visitor-daily-fee-field" type="number" min="100" max="200000" required>

        <input id="visitor-form-submit-button" type="submit">
        <input type="reset" @click.prevent="ClearFormCallback">
    </form>
</template>

<script setup>
    /**
     * @brief Needed imports
     */
    import { ref, defineProps, defineEmits, watch, onMounted } from 'vue';

    /**
     * @brief Props
     */
    const props = defineProps({
        currentId: ref(Number),
        currentDataToFillIn: ref(Object)
    });

    /**
     * @brief Emitted data from this component
     */
    const emits = defineEmits(['form-data-emitted']);

    /**
     * @brief Refs used throughout the code
     */
    const formIdField = ref(0);
    const formNameField = ref("");
    const formDaysCountField = ref(0);
    const formDailyFeeField = ref(0);

    /**
     * @brief Watching over the currentDataToFilllIn props
     * to know that we should reload the form inputs' values
     */
    watch([GetPropsCurrentId, GetPropsCurrentData], ([currentId, changedDataToFillIn]) => {
        LoadCurrentVisitorData(currentId, changedDataToFillIn);
    });

    /**
     * @brief Loads the form data passed as props immediately
     */
    onMounted(() => {
        LoadCurrentVisitorData(props.currentId.value, props.currentDataToFillIn.value);
    });

    /**
     * @param data
     * 
     * @brief Loads the visitor data into the form
     */
    function LoadCurrentVisitorData(id, data) {
        formIdField.value = Number(id);
        formNameField.value = String(data?.Nom);
        formDaysCountField.value = Number(data?.NombreJours);
        formDailyFeeField.value = Number(data?.TarifJournalier);
    }

    /**
     * 
     */
    function GetPropsCurrentId() {
        return props.currentId.value;
    }

    /**
     * 
     */
    function GetPropsCurrentData() {
        return props.currentDataToFillIn.value;
    }

    /**
     * @param visitorId
     * @param visitorName
     * @param visitorDaysCount
     * @param visitorDailyFee
     * 
     * @brief Emits the given variables as a visitor's data
     */
    function EmitData(visitorId, visitorName, visitorDaysCount, visitorDailyFee) {
        let emittedData = {
            NumVisiteur: visitorId,
            Nom: visitorName,
            NombreJours: visitorDaysCount,
            TarifJournalier: visitorDailyFee
        };

        console.log("Emitting data from VisitorForm.vue");
        emits('form-data-emitted', emittedData);
    }

    /**
     * @brief Performs a check on the form's data and
     * tries to emit if it's validated
     */
    function CheckAndEmitData() {
        let idVal = Number(formIdField.value);
        let nameVal = String(formNameField.value);
        let daysCountVal = Number(formDaysCountField.value);
        let dailyFeeVal = Number(formDailyFeeField.value);

        console.log("idVal:", idVal,
            "nameVal:", nameVal,
            "daysCountVal:", daysCountVal,
            "dailyFeeVal:", dailyFeeVal);
        console.log("Checking and trying to emit form data from VisitorForm.vue");

        if (!CheckValues(idVal, nameVal, daysCountVal, dailyFeeVal)) {
            console.log("Check was negative, returning...");
            return;
        }

        console.log("Check was positive, emitting...");
        EmitData(idVal, nameVal, daysCountVal, dailyFeeVal);
    }

    /**
     * @param name
     * @param daysCount
     * @param dailyFee
     * 
     * @brief Checks the expected validy of name, daysCount and dailyFee
     */
    function CheckValues(id, name, daysCount, dailyFee) {
        if (id == 0) {
            console.log("Id is 0");
            alert("Le Numéro du visiteur ne peut pas être égal à 0.");
            return false
        }
        if (name == "") {
            console.log("Name is emtpy");
            alert("Le nom du visiteur ne peut pas être vide ou composé d'espaces.");
            return false;
        }
        if (daysCount < 1 || daysCount > 365) {
            console.log("Days count is invalid");
            alert("Le visiteur ne peut pas rester moins d'un (1) jour ou plus de trois cent soixante cinq (365) jours.");
            return false;
        }
        if (dailyFee < 100 || dailyFee > 200_000) {
            console.log("Daily fee is invalid");
            alert("Le visiteur ne peut pas payer moins de 100 Ariary et plus de 200.000 Ariary.");
            return false;
        }

        return true;
    }

    /**
     * @brief Clears the form fields
     */
    function ClearFormCallback() {
        formIdField.value = Number();
        formNameField.value = String();
        formDaysCountField.value = Number();
        formDailyFeeField.value = Number();
    }
</script>

<style></style>