import Vue from 'vue';
import '../../scss/main.scss';
import TvSeasonCard from './components/TvSeasonCard';

// eslint-disable-next-line no-unused-vars
const app = new Vue({
  el: '#app',
  data: {
    checkSequels: false,
    text: 'Hello World',
    season: {},
    tv_seasons: [],
    filtered: [],
  },
  beforeMount() {
    const request = new XMLHttpRequest();
    request.open('GET', '/api/seasonal/2018/winter');
    request.send();
    request.onreadystatechange = () => {
      if (request.readyState === 4) {
        const response = JSON.parse(request.responseText);
        this.season = response.season;
        this.tv_seasons = response.tvSeasons;
        this.filtered = this.tv_seasons.filter(season => season.number === 1);
      }
    };
  },
  mounted() {
    const dropdownTrigger = document.querySelector('.popup__trigger');
    dropdownTrigger.addEventListener('click', () => {
      dropdownTrigger.classList.toggle('is-active');
      document.getElementById(dropdownTrigger.dataset.trigger).classList.toggle('is-active');
    });
    document.addEventListener('click', (e) => {
      if (e.target.closest('.popup, .popup__trigger') === null) {
        document.querySelectorAll('.popup.is-active').forEach((popup) => {
          popup.classList.remove('is-active');
        });
        document.querySelectorAll('.popup__trigger.is-active').forEach((popup) => {
          popup.classList.remove('is-active');
        });
      }
    });
  },
  methods: {
    filterSequels() {
      if (!this.checkSequels === false) {
        this.filtered = this.tv_seasons.filter(season => season.number === 1);
      } else {
        this.filtered = this.tv_seasons;
      }
    },
  },
});

Vue.component(
  'tv_season',
  TvSeasonCard,
);
