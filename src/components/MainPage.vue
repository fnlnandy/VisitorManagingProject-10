<template>
  <div class="main-app">
    <VisitorsList/>
    <TextualStats/>

    <div class="stats-histogram-wrapper">
      <HistogramStats filler="FILLER" />
    </div>
  </div>
</template>

<script>
import HistogramStats from './HistogramStats.vue'
import TextualStats from './TextualStats.vue'
import VisitorsList from './VisitorsList.vue'

export default {
  name: 'MainPage',
  props: {
    msg: String
  },
  components: {
    VisitorsList,
    TextualStats,
    HistogramStats,
  },
  methods: {
    sendFetchRequest(dest, formData) {
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
        console.log(data);
      })
      .catch(error => {
        console.error("There was a problem with the fetch operation: ", error);
      });
    },
    initPage() {
      console.log("In init page.");
      var data = {
        init: true
      };

      this.sendFetchRequest("http://localhost/phpdir/exo10/server/base.php", data);
    }
  },
  created() {
    this.initPage();
  }
}
</script>

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
