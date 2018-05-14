<template>
  <div class="column is-4">
    <div class="card is-seasonal is-winter">
      <div class="card__head">
        <template v-if="user">
          <a
            v-if="listStatus === null"
            ref="trigger"
            class="popup__trigger button is-outline is-tiny is-right"
            @click="showPopup = !showPopup">
            Add
          </a>
          <span v-else-if="listStatus === 'ptw'" class="label__status is-ptw is-right">Plan To Watch</span>
          <span v-else-if="listStatus === 'completed'" class="label__status is-completed is-right">Completed</span>
          <span
            v-else-if="listStatus === 'watching'"
            class="label__status is-watching is-right">Currently Watching</span>
          <span v-else-if="listStatus === 'hold'" class="label__status is-ptw is-right">On Hold</span>
          <span v-else-if="listStatus === 'dropped'" class="label__status is-dropped is-right">Dropped</span>
          <div :class="`popup popup--list ${ showPopup ? 'is-active' : ''}`">
            <div class="popup__content">
              <div class="field is-centered row">
                <label for="status" class="column is-2">Status</label>
                <div class="column is-6">
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
              <button class="button is-info is-narrow button--list" @click="submit">Add to list</button>
            </div>
            <div class="popup__arrow" />
          </div>
        </template>
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
          <p class="description__networks">{{ tv_season.tv_show.networks.map(nw => nw.name).join(', ') }}</p>
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
          <span>{{ tv_season.episode_count === 0 ? '?' : tv_season.episode_count }} Eps.</span>
        </div>
        <div class="flex-right">
          <img src="/images/imdb.svg">
          <span v-if="tv_season.tv_show.imdb_score === 0">N/A</span>
          <span v-else>
            <a :href="`http://www.imdb.com/title/${tv_season.tv_show.imdb_id}/`">{{ tv_season.tv_show.imdb_score }}</a>
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
      submittedStatus: null,
    };
  },
  computed: {
    user() {
      return this.store.user;
    },
    listStatus() {
      if (this.submittedStatus) {
        return this.submittedStatus;
      }
      return this.tv_season.users.length === 0 ? null : this.tv_season.users[0].pivot.status;
    },
  },
  created() {
    EventBus.$on('close-all-popups', () => {
      this.showPopup = false;
    });
    EventBus.$on('close-all-popups-except', (event) => {
      if (this.$refs.trigger !== event.target) {
        this.showPopup = false;
      }
    });
  },
  mounted() {
    luma.select(this.$refs.select);
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
    submit() {
      console.log('Submitting: ', {
        tv_season_id: this.tv_season.id,
        status: this.status,
      });
      axios.post('/api/users/me/seasons', {
        tv_season_id: this.tv_season.id,
        status: this.status,
      }, {
        headers: { 'X-CSRF-TOKEN': document.head.querySelector('[name=csrf-token]').content },
      })
        .then(() => {
          this.submittedStatus = this.status;
          this.showPopup = false;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
