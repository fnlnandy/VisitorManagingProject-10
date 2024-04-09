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
