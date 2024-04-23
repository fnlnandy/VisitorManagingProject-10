<template>
    <!-- <img alt="Vue logo" src="./assets/logo.png"> -->
    <MainPage id="main-page" />
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
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        color: rgb(0, 31, 64);
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url("assets/background.jpg");
        background-size: cover;
    }
</style>
