<script setup>
    import HistogramStats from './HistogramStats.vue'
    import TextualStats from './TextualStats.vue'
    import VisitorsList from './VisitorsList.vue'
    import { ref, onMounted } from 'vue'

    const fetchedData = ref([]);
    const loadedData = ref(false);

    onMounted(() => {
        let fetchReqParams = {
            init: true
        };

        SendFetchRequest("http://localhost/phpdir/exo10/server/fetchvisitors.php", fetchReqParams, true);
    });

    async function SendFetchRequest(dest, formData, fetchData = false)
    {
        console.log("Preparing fetch request to: ", dest, "With data: ", formData);
      
        fetch (dest, {method: 'POST', body: JSON.stringify(formData)})
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response wasn't ok.");
            }

            console.log("Getting the request response into JSON...");
      
            return response.json();
        })
        .then(data => {
            if (fetchData)
            {
                fetchedData.value = data["FetchedData"];
                loadedData.value  = true;
            }
            console.log("Fetched data assigned:", fetchedData.value);
        })
        .catch(error => {
            console.error("There was a problem with the fetch operation: ", error);
        });
    }

    function ComputeFetchedData()
    {
        if (fetchedData.value.length > 0)
        {
            console.log("Fetched data length is > 0");
            return fetchedData;
        }
        else
        {
            console.log("Fetched data length == 0");
            return fetchedData;
        }
    }

    function HandleActionOnVisitorEmitted(data)
    {
        SendFetchRequest("http://localhost/phpdir/exo10/server/actions.php", data);
    }

    function HandleDeletingVisitorEmitted(id)
    {
        let data = { NumVisiteur: Number(id) };

        SendFetchRequest("http://localhost/phpdir/exo10/server/rmvisitor.php", data);
    }
</script>

<template>
  <div class="main-app">
    <VisitorsList v-if="loadedData" :visitorsArray="ComputeFetchedData()" @act-on-visitor-query="HandleActionOnVisitorEmitted" @delete-visitor-query="HandleDeletingVisitorEmitted"/>
    <TextualStats v-if="loadedData" :fetchedData="ComputeFetchedData()"/>

    <div class="stats-histogram-wrapper">
      <HistogramStats filler="FILLER" />
    </div>
  </div>
</template>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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
</style>
