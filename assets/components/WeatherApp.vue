<template>
  <div class="weather-app max-height">
    <div class="weather-app__modal">
      <div class="weather-app__title-container">
        <h1 class="weather-app__title">
          Weather App
        </h1>
      </div>
      <hr class="line"/>
      <div class="weather-app__content">
        <div class="weather-app__icon-container">
          <img :src="getWeatherIconUrl(weatherReport.weather[0]['icon'])" alt="Weather Image">
        </div>
        <div class="weather-app__information">
          <div class="flex-container">
            <p class="weather-app__temperature-text">
              {{ convertKelvinToCelsius(weatherReport.main['temp']) }}°c
            </p>
          </div>
          <div class="flex-container">
            <p class="weather-app__weather-text">
              {{ capitalizeFirstLetter(weatherReport.weather[0]['description']) }}
            </p>
          </div>
          <div class="flex-container weather-app__location-container">
            <p>
              {{ `${location.location['country_flag_emoji']} ${fullLocationText}` }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const ABSOLUTE_ZERO_TEMPERATURE = 273.15
export default {
  name: "WeatherApp",
  props: {
    location: {
      type: Object,
    },
    weatherReport: {
      type: Object,
    }
  },
  computed: {
    fullLocationText() {
      return `${this.location.city}, ${this.location.country_code}`;
    }
  },
  methods: {
    convertKelvinToCelsius(kelvin) {
      return (kelvin - ABSOLUTE_ZERO_TEMPERATURE).toFixed(2);
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    getWeatherIconUrl(iconCode) {
      return `https://openweathermap.org/img/wn/${iconCode}@4x.png`;
    }
  }
}
</script>

<style lang="scss">
$primary-color: #4AB9F9;

.weather-app {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: $primary-color;

  &__title {
    color: $primary-color;
    margin: 0 0 0 24px;

    &-container {
      height: 15%;
      display: flex;
      align-items: center;
    }
  }

  &__content {
    height: 85%;
  }

  &__bottom {
    height: 20%;
  }

  &__modal {
    background-color: white;
    height: 500px;
    width: 400px;
    border-radius: 8px;
  }

  &__icon {
    color: $primary-color;
    font-size: 110px;
    padding-top: 20px;

    &-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 45%;
    }
  }

  &__temperature-text {
    font-size: 48px;
    font-weight: bold;
    margin-top: 18px;
  }

  &__weather-text {
    font-size: 22px;
  }

  &__location-icon {
    margin-right: 6px;
  }

  &__location-container {
    margin-top: 10px;
  }
}

p {
  margin: 0;
}

.line {
  margin: 0;
}

.flex-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.max-height {
  height: 100vh;
}
</style>