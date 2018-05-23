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
        values: [1, 2, 4, 5, 6, 7, 8, 9, 10, 15, 16, 17, 19, 20, 21, 24, 25, 26, 27, 28, 30],
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
