<template>
  <div class="column is-4">
    <div class="card is-seasonal is-winter">
      <div class="card__head"/>
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
