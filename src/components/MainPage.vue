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

        SendFetchRequest("http://localhost/phpdir/exo10/server/fetchvisitors.php", fetchReqParams);
    });

    async function SendFetchRequest(dest, formData)
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
            fetchedData.value = data["FetchedData"];
            loadedData.value  = true;
            console.log("Fetched data assigned:", fetchedData);
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
</script>

<template>
  <div class="main-app">
    <div v-if="!loadedData" >Loading table data...</div>
    <VisitorsList v-else :visitorsArray="ComputeFetchedData()"/>
    <TextualStats/>

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
