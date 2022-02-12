<template>
  <weather-app :location="location" :weather-report="weatherReport"/>
</template>

<script>
import WeatherApp from "./WeatherApp";

export default {
  name: 'Home',
  components: {
    WeatherApp
  },
  data() {
    return {
      location: {},
      weatherReport: {}
    }
  },
  created() {
    this.location = JSON.parse(window.sessionStorage.getItem('location'));
    this.weatherReport = JSON.parse(window.sessionStorage.getItem('weatherReport'));
  }
}
</script>

<style lang="scss">
html, body {
  height: 100vh;
  margin: 0;
  font-family: 'Overpass', sans-serif;
}
</style>