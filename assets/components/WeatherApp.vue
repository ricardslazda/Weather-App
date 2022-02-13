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
          <img :src="getWeatherIconUrl(weatherReportData.weather[0]['icon'])" alt="Weather Image">
        </div>
        <div class="weather-app__information">
          <div class="flex-container">
            <p class="weather-app__temperature-text">
              {{ convertKelvinToCelsius(weatherReportData.main['temp']) }}°c
            </p>
          </div>
          <div class="flex-container">
            <p class="weather-app__weather-text">
              {{ capitalizeFirstLetter(weatherReportData.weather[0]['description']) }}
            </p>
          </div>
          <div class="flex-container weather-app__location-container">
            <p>
              {{ `${locationData.location['country_flag_emoji']} ${fullLocationText}` }}
            </p>
          </div>
        </div>
      </div>
      <div class="weather-app__refresh-container">
        <button :class="['weather-app__refresh-button', {'weather-app__refresh-button--disabled': isRefreshDisabled}]"
                @click="onRefresh">
          Refresh
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {useToast} from "vue-toastification";
import weatherReportRepository from "../repositories/weather-report.repository";
import _ from 'lodash';

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
  data() {
    return {
      isRefreshDisabled: false,
      locationData: _.cloneDeep(this.location),
      weatherReportData: _.cloneDeep(this.weatherReport)
    }
  },
  setup() {
    const toast = useToast();
    return {toast}
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
    },
    onRefresh() {
      if (this.isRefreshDisabled) {
        return;
      }

      this.isRefreshDisabled = true;
      weatherReportRepository.getFreshData()
          .then(response => {
            this.locationData = response.data.location;
            this.weatherReportData = response.data.weatherReport;
          })
          .then(() => {
            this.toast.success("Refreshed!", {
              timeout: 900,
              hideProgressBar: true,
              closeButton: false
            });
            this.isRefreshDisabled = false;
          });
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
    height: 70%;
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

  &__refresh {
    &-container {
      height: 15%;
      display: flex;
      align-items: center;
      justify-content: end;
      margin-right: 28px;
    }

    &-button {
      background-color: $primary-color;
      color: white;
      border: none;
      cursor: pointer;
      outline: none;
      box-shadow: none;
      padding: 6px 12px;

      &--disabled {
        background: gainsboro;
        cursor: auto;
      }
    }

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