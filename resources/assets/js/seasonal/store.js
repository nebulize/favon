export default {
  data: {
    user: null,
    filters: {
      sequels: false,
      sequelsList: true,
      sort: 'score',
      score: 0,
      list: {
        all: true,
        values: ['not_in_list', 'ptw', 'completed', 'watching', 'hold', 'dropped'],
      },
      unrated: true,
      genres: {
        all: false,
        values: [32, 33, 34, 35, 37, 38, 39, 40, 46, 47, 50, 51, 53, 54, 55],
      },
      languages: ['en'],
    },
    season: {},
    tv_seasons: [],
    filtered: [],
    genres: [],
    genreIds: [],
    list_statuses: [{
      code: 'not_in_list',
      name: 'Not in my list',
    }, {
      code: 'ptw',
      name: 'Plan to Watch',
    }, {
      code: 'watching',
      name: 'Watching',
    }, {
      code: 'completed',
      name: 'Completed',
    }, {
      code: 'hold',
      name: 'On Hold',
    }, {
      code: 'dropped',
      name: 'Dropped',
    }],
    languages: [{
      code: 'en',
      name: 'English',
    }, {
      code: 'ja',
      name: 'Japanese',
    }, {
      code: 'de',
      name: 'German',
    }, {
      code: 'fr',
      name: 'French',
    }, {
      code: 'ko',
      name: 'Korean',
    }, {
      code: 'es',
      name: 'Spanish',
    }],
  },
};
