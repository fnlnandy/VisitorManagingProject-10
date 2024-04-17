<template>
    <!-- <img alt="Vue logo" src="./assets/logo.png"> -->
    <MainPage msg="Welcome to Your Vue.js App" />
</template>

<script>
    /**
     * @brief Needed imports
     */
    import MainPage from './components/MainPage.vue'

    /**
     * @brief Default component params
     */
    export default {
        name: 'App',
        components: {
            MainPage
        }
    }
</script>

<style>
    #app {
        font-family: Avenir, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
