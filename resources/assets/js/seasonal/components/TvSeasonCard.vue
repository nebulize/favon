<template>
  <div class="column is-4">
    <div class="card is-seasonal is-winter">
      <div class="card__head"/>
      <div class="card__body">
        <div class="body__poster">
          <img
            v-if="tv_season.poster"
            :src="`http://image.tmdb.org/t/p/w342${tv_season.poster}`">
          <img
            v-else-if="tv_season.tv_show.poster"
            :src="`http://image.tmdb.org/t/p/w342${tv_season.tv_show.poster}`">
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
        <span>{{ tv_season.season_first_aired }}</span>
        <div class="flex-group is-right">
          <img src="/images/imdb.svg">
          <span>
            {{ tv_season.tv_show.imdb_score === 0 ? 'N/A' : tv_season.tv_show.imdb_score }}
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
export default {
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
};
</script>
