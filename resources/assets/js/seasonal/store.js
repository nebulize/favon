export default {
  data: {
    user: null,
    filters: {
      sequels: false,
      sort: 'score',
      score: 0,
      list: {
        all: true,
        values: [],
      },
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
