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
          <h4>My List</h4>
          <input type="checkbox" class="checkbox" id="filter__list--all" checked>
          <label for="filter__list--all">All</label>
          <input type="checkbox" class="checkbox" id="filter__list--not">
          <label for="filter__list--not">Not in my list</label>
          <input type="checkbox" class="checkbox" id="filter__list--watching">
          <label for="filter__list--watching">Watching</label>
          <input type="checkbox" class="checkbox" id="filter__list--ptw">
          <label for="filter__list--ptw">Plan To Watch</label>
          <input type="checkbox" class="checkbox" id="filter__list--completed">
          <label for="filter__list--completed">Completed</label>
          <input type="checkbox" class="checkbox" id="filter__list--dropped">
          <label for="filter__list--dropped">Dropped</label>
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
                @change="filter"
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
  methods: {
    filter() {
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
  },
};
</script>

<style lang="scss">
  .filter__languages {
    display: inline-block;
  }
</style>
