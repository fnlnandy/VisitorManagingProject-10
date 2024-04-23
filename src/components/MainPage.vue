<script setup>
    /**
     * @brief Needed imports
     */
    import HistogramStats from './HistogramStats.vue'
    import TextualStats from './TextualStats.vue'
    import VisitorsList from './VisitorsList.vue'
    import { ref, onMounted } from 'vue'

    /**
     * @brief Data used throughout the code
     */
    const fetchedData = ref([]);
    const loadedData = ref(false);

    /**
     * @brief Initiates the page immediately
     */
    onMounted(() => {
        InitPage();
    });

    /**
     * @brief Fetches the needed data for the application
     */
    function InitPage() {
        let fetchReqParams = {
            init: true
        };

        SendFetchRequest("http://localhost/phpdir/exo10/server/fetchvisitors.php", fetchReqParams, true);
    }

    /**
     * @param dest
     * @param formData
     * @param fetchData
     * 
     * @brief Sends a fetch request to the specified dest
     * 
     * @details Sends an object that will be parsed as JSON (formData)
     * to an url (dest), also can store the data inside fetchedData if
     * fetchData is set to true
     */
    async function SendFetchRequest(dest, formData, fetchData = false) {
        console.log("Preparing fetch request to: ", dest, "With data: ", formData);

        fetch(dest, { method: 'POST', body: JSON.stringify(formData) })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response wasn't ok.");
                }

                console.log("Getting the request response into JSON...");

                return response.json();
            })
            .then(data => {
                if (fetchData) {
                    fetchedData.value = data["FetchedData"];
                    loadedData.value = true;
                    console.log("Fetched data assigned:", fetchedData.value);
                }
            })
            .catch(error => {
                console.info("There was a problem with the fetch operation: ", error);
            });
    }

    /**
     * @brief Computes the fetched data
     */
    function ComputeFetchedData() {
        if (fetchedData.value.length > 0) {
            console.log("Fetched data length is > 0");
            return fetchedData;
        }
        else {
            console.log("Fetched data length == 0");
            return fetchedData;
        }
    }

    /**
     * @param data
     * 
     * @brief Handles the act-on-visitor emitted event
     * 
     * @details Sends a request to the server, which will
     * handle the type, and reloads the page to reflect
     * the changes
     */
    async function HandleActionOnVisitorEmitted(data) {
        const bufferedVisitorData = data?.VisitorData;
        let visitorData = {
            NumVisiteur: bufferedVisitorData?.NumVisiteur,
            Nom: bufferedVisitorData?.Nom,
            NombreJours: bufferedVisitorData?.NombreJours,
            TarifJournalier: bufferedVisitorData?.TarifJournalier,
            ModeAjout: !(data?.IsEditMode),
            IdPrecedent: data?.PreviousId
        };

        console.log("Visitor data about to be emitted:", visitorData);
        await SendFetchRequest("http://localhost/phpdir/exo10/server/actions.php", visitorData);
        location.reload();
    }

    /**
     * @param id
     * 
     * @brief Handles the delete-visitor emitted event
     * 
     * @details Sends a request to the server to remove
     * the specified visitor with id; the id's validity
     * will be checked within said server
     */
    async function HandleDeletingVisitorEmitted(id) {
        let data = { NumVisiteur: Number(id) };

        await SendFetchRequest("http://localhost/phpdir/exo10/server/rmvisitor.php", data);
        location.reload();
    }
</script>

<template>
    <div v-if="loadedData" class="main-app">
        <VisitorsList v-if="loadedData" :visitorsArray="ComputeFetchedData()"
            @act-on-visitor-query="HandleActionOnVisitorEmitted" @delete-visitor-query="HandleDeletingVisitorEmitted" />
        <TextualStats v-if="loadedData" :fetchedData="ComputeFetchedData()" />
        <HistogramStats v-if="loadedData" :generalData="fetchedData" class="histogram" />
    </div>
</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
    h3 {
        margin: 40px 0 0;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }

    .histogram * {
        background: transparent;
    }
</style>
