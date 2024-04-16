<template>
    <form @submit.prevent.stop="CheckAndEmitData" id="visitor-form" method="post">
        <label for="visitor-name-field">Nom:</label>
        <input v-model="formNameField" id="visitor-name-field" type="text" maxlength="100" required>
        <label for="visitor-days-count-field">Nombre de jours:</label>
        <input v-model="formDaysCountField" id="visitor-days-count-field" type="number" min="1" max="365" required>
        <label for="visitor-daily-fee-field"></label>
        <input v-model="formDailyFeeField" id="visitor-daily-fee-field" type="number" min="100" max="200000" required>
    
        <input id="visitor-form-submit-button" type="submit">
        <input type="reset" @click.prevent="ClearFormCallback">
    </form>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue';

const props = defineProps({
    currentId: ref(Number),
    currentDataToFillIn: ref(Object)
});

const emits = defineEmits(['form-data-emitted']);

const formNameField = ref("");
const formDaysCountField = ref(0);
const formDailyFeeField = ref(0);

watch(() => props.currentDataToFillIn.value, (newValue) => {
    formNameField.value = String(newValue?.Nom);
    formDaysCountField.value = Number(newValue?.NombreJours);
    formDailyFeeField.value = Number(newValue?.TarifJournalier);
});

function EmitData(visitorId, visitorName, visitorDaysCount, visitorDailyFee)
{
    let emittedData = {
        NumVisiteur: visitorId,
        Nom: visitorName,
        NombreJours: visitorDaysCount,
        TarifJournalier: visitorDailyFee
    };

    console.log("Emitting data from VisitorForm.vue");
    emits('form-data-emitted', emittedData);
}

function CheckAndEmitData()
{
    let nameVal      = String(formNameField.value);
    let daysCountVal = Number(formDaysCountField.value);
    let dailyFeeVal  = Number(formDailyFeeField.value);

    console.log("nameVal:", nameVal, 
                "daysCountVal:", daysCountVal,
                "dailyFeeVal:", dailyFeeVal);
    console.log("Checking and trying to emit form data from VisitorForm.vue");

    if (!CheckValues())
    {
        console.log("Check was negative, returning...");
        return;
    }

    console.log("Check was positive, emitting...");
    EmitData(props.currentId.value, nameVal, daysCountVal, dailyFeeVal);    
}

function CheckValues(name, daysCount, dailyFee)
{
    if (name == "")
    {
        console.log("Name is emtpy");
        alert("Le nom du visiteur ne peut pas être vide ou composé d'espaces.");
        return false;
    }
    if (daysCount < 1 || daysCount > 365)
    {
        console.log("Days count is invalid");
        alert("Le visiteur ne peut pas rester moins d'un (1) jour ou plus de trois cent soixante cinq (365) jours.");
        return false;
    }
    if (dailyFee < 100 || dailyFee > 200_000)
    {
        console.log("Daily fee is invalid");
        alert("Le visiteur ne peut pas payer moins de 100 Ariary et plus de 200.000 Ariary.");
        return false;
    }

    return true;
}

function ClearFormCallback()
{
    formNameField.value = String();
    formDaysCountField.value = Number();
    formDailyFeeField.value = Number();
}
</script>

<style>
</style>