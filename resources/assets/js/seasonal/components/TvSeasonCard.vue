<template>
  <div class="column is-4">
    <div class="card is-seasonal is-winter">
      <div class="card__head">
        <img v-if="primaryNetwork && networks.includes(primaryNetwork)" :src="`/images/networks/${primaryNetwork}.png`">
        <template v-if="user">
          <a
            v-if="inList"
            ref="list"
            :class="`popup__trigger label__status is-${status} is-right`"
            @click="togglePopup">
            {{ listStatusDescription }}
          </a>
          <a
            v-else
            ref="trigger"
            class="popup__trigger button button--list-add is-outline is-tiny is-right"
            @click="togglePopup">
            Add To List
          </a>
        </template>
        <div :class="`popup popup--list ${ showPopup ? 'is-active' : ''}`">
          <div class="popup__content">
            <div class="field is-centered row">
              <label for="status" class="column is-3">Status</label>
              <div class="column is-9">
                <select
                  ref="select"
                  v-model="status"
                  class="select"
                  name="status"
                  id="status">
                  <option value="ptw">Plan To Watch</option>
                  <option value="watching">Watching</option>
                  <option value="completed">Completed</option>
                  <option value="hold">On Hold</option>
                  <option value="dropped">Dropped</option>
                </select>
              </div>
            </div>
            <div v-show="status !== 'completed' && status !== 'ptw'" class="field is-centered row list__progress">
              <label for="progress" class="column is-3">Progress</label>
              <div class="column is-6">
                <div class="progress__input">
                  <input
                    type="number"
                    name="progress"
                    id="progress"
                    v-model.number="progress"
                    @change="updateProgress">
                  <span>/ {{ episodeCount }} Eps.</span>
                </div>
              </div>
              <div class="column is-3 progress__column--3">
                <button
                  class="button is-info progress__increment"
                  :disabled="!canIncrement"
                  @click="incrementProgress">
                  + 1
                </button>
              </div>
            </div>
            <div v-show="status !== 'ptw'" class="field is-centered row">
              <label for="score" class="column is-3">Score</label>
              <div class="column is-9">
                <select
                  ref="scoreSelect"
                  v-model="score"
                  class="select"
                  name="score"
                  id="score">
                  <option value="0">Select a score</option>
                  <option value="10">10 - Masterpiece</option>
                  <option value="9">9 - Outstanding</option>
                  <option value="8">8 - Very Good</option>
                  <option value="7">7 - Good</option>
                  <option value="6">6 - Fine</option>
                  <option value="5">5 - Average</option>
                  <option value="4">4 - Underwhelming</option>
                  <option value="3">3 - Bad</option>
                  <option value="2">2 - Very Bad</option>
                  <option value="1">1 - Appalling</option>
                </select>
              </div>
            </div>
            <div class="list__buttons">
              <button class="button is-info is-narrow button--list" @click="submit">Save</button>
              <button
                v-if="inList"
                class="button is-danger is-narrow button--list"
                @click="remove">
                Remove from List
              </button>
            </div>
          </div>
          <div class="popup__arrow" />
        </div>
      </div>
      <div class="card__body">
        <div class="body__poster">
          <img
            v-if="tv_season.poster"
            :src="`https://image.tmdb.org/t/p/w342${tv_season.poster}`">
          <img
            v-else-if="tv_season.tv_show.poster"
            :src="`https://image.tmdb.org/t/p/w342${tv_season.tv_show.poster}`">
          <div
            v-else
            class="poster__placeholder">
            <img src="/images/poster_placeholder.svg">
          </div>
        </div>
        <div class="body__description">
          <h3 class="description__title">
            {{ tv_season.tv_show.name }}
            <span :class="`text-${season.name}`"> S{{ tv_season.number }}</span>
          </h3>
          <span
            v-for="genre in tv_season.tv_show.genres"
            :key="`${tv_season.id}${genre.id}`"
            class="genre-label">
            {{ genre.name }}
          </span>
          <p
            v-if="tv_season.summary"
            class="description__plot">
            {{ tv_season.summary }}
          </p>
          <p
            v-else
            class="description__plot">
            {{ tv_season.tv_show.summary }}
          </p>
        </div>
      </div>
      <div class="card__footer">
        <div class="flex-left">
          <span>{{ tv_season.first_aired_string }}</span>
        </div>
        <div class="flex-center">
          <span>{{ episodeCount }} Eps.</span>
        </div>
        <div class="flex-right">
          <img src="/images/imdb.svg">
          <span v-if="tv_season.tv_show.imdb_score === 0">N/A</span>
          <span v-else>
            <a
              :href="`http://www.imdb.com/title/${tv_season.tv_show.imdb_id}/`"
              target="_blank">
              {{ tv_season.tv_show.imdb_score }}
            </a>
          </span>
          <img src="/images/heart.svg">
          <span>0</span>
          <img src="/images/star.svg">
          <span>0</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import luma from 'lumacss';
import axios from 'axios';
import Store from '../store';
import EventBus from '../event-bus';

export default {
  data() {
    return {
      store: Store.data,
      /**
       * Visibility of the list popup
       * @type {Boolean}
       */
      showPopup: false,
      status: 'ptw',
      progress: 0,
      score: 0,
      inList: false,
      networks: ['netflix', 'hulu', 'hbo', 'syfy', 'amazon', 'showtime', 'starz'],
    };
  },
  computed: {
    user() {
      return this.store.user;
    },
    listStatusDescription() {
      switch (this.status) {
        case 'completed':
          return `Completed ★ ${this.score}`;
        case 'watching':
          return `Watching ${this.progress}/${this.episodeCount} ★ ${this.score}`;
        case 'hold':
          return `On Hold ${this.progress}/${this.episodeCount} ★ ${this.score}`;
        case 'dropped':
          return `Dropped ★ ${this.score}`;
        default:
          return 'Plan To Watch';
      }
    },
    episodeCount() {
      return this.tv_season.episode_count === 0 ? '?' : this.tv_season.episode_count;
    },
    primaryNetwork() {
      return this.tv_season.tv_show.networks[0] ? this.tv_season.tv_show.networks[0].name.toLowerCase() : null;
    },
    canIncrement() {
      return this.tv_season.episode_count === 0 || (this.progress + 1) <= this.tv_season.episode_count;
    },
  },
  created() {
    EventBus.$on('close-all-popups', () => {
      this.showPopup = false;
    });
    EventBus.$on('close-all-popups-except', (event) => {
      if (event.target !== this.$refs.trigger && event.target !== this.$refs.list) {
        this.showPopup = false;
      }
    });
    if (this.tv_season.user_tv_seasons && this.tv_season.user_tv_seasons.length > 0) {
      this.inList = true;
      const pivot = this.tv_season.user_tv_seasons[0];
      this.progress = pivot.progress;
      this.status = pivot.status;
      this.score = pivot.score ? pivot.score : 0;
    }
  },
  mounted() {
    luma.select(this.$refs.select);
    luma.select(this.$refs.scoreSelect);
  },
  props: {
    season: {
      type: Object,
      required: true,
    },
    tv_season: {
      type: Object,
      required: true,
    },
  },
  methods: {
    togglePopup() {
      this.showPopup = !this.showPopup;
    },
    incrementProgress() {
      if (this.tv_season.episode_count !== 0 && (this.progress + 1) <= this.tv_season.episode_count) {
        this.progress += 1;
      }
    },
    updateProgress() {
      if (!this.progress || this.progress === '' || this.progress < 0) {
        this.progress = 0;
      } else if (this.progress > this.tv_season.episode_count) {
        this.progress = this.tv_season.episode_count;
      }
    },
    submit() {
      if (this.inList) {
        axios.patch(`/api/users/me/seasons/${this.tv_season.id}`, {
          tv_season_id: this.tv_season.id,
          status: this.status,
          progress: this.progress,
          score: this.score,
        }, {
          headers: { 'X-CSRF-TOKEN': document.head.querySelector('[name=csrf-token]').content },
        }).then(() => {
          this.showPopup = false;
        }).catch((error) => {
          console.error(error);
        });
      } else {
        axios.post('/api/users/me/seasons', {
          tv_season_id: this.tv_season.id,
          status: this.status,
          progress: this.progress,
          score: this.score,
        }, {
          headers: { 'X-CSRF-TOKEN': document.head.querySelector('[name=csrf-token]').content },
        }).then(() => {
          this.showPopup = false;
          this.inList = true;
        });
      }
    },
    remove() {
      axios.delete(`/api/users/me/seasons/${this.tv_season.id}`)
        .then(() => {
          this.inList = false;
        });
    },
  },
};
</script>
