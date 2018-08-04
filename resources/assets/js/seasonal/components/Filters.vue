<template>
  <div :class="`popup popup--filters ${ showFilter ? 'is-active' : ''}`">
    <div class="popup__content">
      <div class="row">
        <div class="column is-5">
          <h4>Sort By</h4>
          <div class="field row">
            <div class="column is-5">
              <select v-model="filters.sort" @change="filter" data-style="select" class="select">
                <option value="popularity">Popularity</option>
                <option value="score">Score</option>
                <option value="name">Name</option>
                <option value="start_date">Start Date</option>
                <option value="recently_added">Recently Added</option>
              </select>
            </div>
          </div>
          <h4>Score</h4>
          <div class="field is-centered row">
            <label for="filter--score" class="column is-4">Minimum score</label>
            <div class="column is-4">
              <input type="text" id="filter--score" v-model.number="filters.score" @change="filter">
            </div>
          </div>
          <template v-if="user">
            <h4>My List</h4>
            <input
              type="checkbox"
              class="checkbox"
              id="filter__list--all"
              @change="filterAllList"
              v-model="filters.list.all">
            <label for="filter__list--all">All</label>
            <template v-for="status in store.list_statuses">
              <input
                :key="`input-${status.code}`"
                type="checkbox"
                class="checkbox"
                :value="status.code"
                :id="`filter__list--${status.code}`"
                @change="filterListStatus"
                v-model="filters.list.values">
              <label :for="`filter__list--${status.code}`" :key="`label-${status.code}`">{{ status.name }}</label>
            </template>
          </template>
          <h4>Misc</h4>
          <input
            type="checkbox"
            class="checkbox"
            id="filter__unrated"
            v-model="filters.unrated"
            @change="filter">
          <label for="filter__unrated">Include non-rated shows</label>
          <template v-if="user">
            <input
              type="checkbox"
              class="checkbox"
              id="filter__sequels--list"
              v-model="filters.sequelsList"
              @change="filter">
            <label for="filter__sequels--list">Always include sequels that are in my list</label>
          </template>
        </div>
        <div class="column is-6 is-offset-1">
          <h4>Genres</h4>
          <input
            type="checkbox"
            class="checkbox"
            id="filter__genres--all"
            @change="filterAllGenres"
            v-model="filters.genres.all">
          <label for="filter__genres--all">All</label>
          <div class="row filter__genres is-multiline">
            <div v-for="genre in store.genres" :key="genre.id" class="column is-3">
              <input
                type="checkbox"
                class="checkbox"
                :value="genre.id"
                :id="`filter__genres--${genre.id}`"
                @change="filterGenres"
                v-model="filters.genres.values">
              <label :for="`filter__genres--${genre.id}`">{{ genre.name }}</label>
            </div>
          </div>

          <h4>Languages</h4>
          <div v-for="language in store.languages" :key="language.code" class="filter__languages">
            <input
              type="checkbox"
              class="checkbox"
              :value="language.code"
              :id="`filter__languages--${language.code}`"
              v-model="filters.languages"
              @change="filter">
            <label :for="`filter__languages--${language.code}`">{{ language.name }}</label>
          </div>
        </div>
      </div>
    </div>
    <div class="popup__arrow" />
  </div>
</template>

<script>
import Store from '../store';

export default {
  data() {
    return {
      filters: Store.data.filters,
      store: Store.data,
    };
  },
  props: {
    showFilter: {
      type: Boolean,
      required: true,
    },
  },
  computed: {
    user() {
      return this.store.user;
    },
  },
  methods: {
    filter() {
      this.$emit('filter');
    },
    filterListStatus() {
      if (this.store.filters.list.values.length !== this.store.list_statuses.length) {
        this.store.filters.list.all = false;
      }
      this.$emit('filter');
    },
    filterGenres() {
      if (this.store.filters.genres.values.length !== this.store.genreIds.length) {
        this.store.filters.genres.all = false;
      }
      this.$emit('filter');
    },
    filterAllGenres() {
      if (this.filters.genres.all === true) {
        this.store.filters.genres.values = this.store.genreIds.slice();
      } else {
        this.store.filters.genres.values = [];
      }
      this.$emit('filter');
    },
    filterAllList() {
      if (this.filters.list.all === true) {
        this.store.filters.list.values = ['not_in_list', 2, 3, 1, 5, 4];
      } else {
        this.store.filters.list.values = [];
      }
      this.$emit('filter');
    },
  },
};
</script>

<style lang="scss">
  .filter__languages {
    display: inline-block;
  }
</style>
