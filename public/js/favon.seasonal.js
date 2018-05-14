!function(t){function e(e){for(var a,n,o=e[0],l=e[1],u=e[2],f=0,d=[];f<o.length;f++)n=o[f],r[n]&&d.push(r[n][0]),r[n]=0;for(a in l)Object.prototype.hasOwnProperty.call(l,a)&&(t[a]=l[a]);for(c&&c(e);d.length;)d.shift()();return i.push.apply(i,u||[]),s()}function s(){for(var t,e=0;e<i.length;e++){for(var s=i[e],a=!0,o=1;o<s.length;o++){var l=s[o];0!==r[l]&&(a=!1)}a&&(i.splice(e--,1),t=n(n.s=s[0]))}return t}var a={},r={1:0},i=[];function n(e){if(a[e])return a[e].exports;var s=a[e]={i:e,l:!1,exports:{}};return t[e].call(s.exports,s,s.exports,n),s.l=!0,s.exports}n.m=t,n.c=a,n.d=function(t,e,s){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:s})},n.r=function(t){Object.defineProperty(t,"__esModule",{value:!0})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/";var o=window.webpackJsonp=window.webpackJsonp||[],l=o.push.bind(o);o.push=e,o=o.slice();for(var u=0;u<o.length;u++)e(o[u]);var c=l;i.push([207,0]),s()}({13:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a,r=(a=s(17))&&a.__esModule?a:{default:a},i={data:function(){return{filters:r.default.data.filters,store:r.default.data}},props:{showFilter:{type:Boolean,required:!0}},methods:{filter:function(){this.$emit("filter")},filterAllGenres:function(){!0===this.filters.genres.all?this.store.filters.genres.values=this.store.genreIds.slice():this.store.filters.genres.values=[],this.$emit("filter")}}};e.default=i},14:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=o(s(16)),r=o(s(73)),i=o(s(17)),n=o(s(32));function o(t){return t&&t.__esModule?t:{default:t}}var l={data:function(){return{store:i.default.data,showPopup:!1,status:"ptw",submittedStatus:null}},computed:{user:function(){return this.store.user},listStatus:function(){return this.submittedStatus?this.submittedStatus:0===this.tv_season.users.length?null:this.tv_season.users[0].pivot.status}},created:function(){var t=this;n.default.$on("close-all-popups",function(){t.showPopup=!1}),n.default.$on("close-all-popups-except",function(e){t.$refs.trigger!==e.target&&(t.showPopup=!1)})},mounted:function(){a.default.select(this.$refs.select)},props:{season:{type:Object,required:!0},tv_season:{type:Object,required:!0}},methods:{submit:function(){var t=this;console.log("Submitting: ",{tv_season_id:this.tv_season.id,status:this.status}),r.default.post("/api/users/me/seasons",{tv_season_id:this.tv_season.id,status:this.status},{headers:{"X-CSRF-TOKEN":document.head.querySelector("[name=csrf-token]").content}}).then(function(){t.submittedStatus=t.status,t.showPopup=!1}).catch(function(t){console.error(t)})}}};e.default=l},17:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,e.default={data:{user:null,filters:{sequels:!1,sort:"score",score:0,list:{all:!0,values:[]},genres:{all:!1,values:[1,2,4,5,6,7,8,9,10,15,16,17,19,20,21,24,25,26,27,28,30]},languages:["en"]},season:{},tv_seasons:[],filtered:[],genres:[],genreIds:[],languages:[{code:"en",name:"English"},{code:"ja",name:"Japanese"},{code:"de",name:"German"},{code:"fr",name:"French"},{code:"ko",name:"Korean"},{code:"es",name:"Spanish"}]}}},207:function(t,e,s){"use strict";var a=_(s(75)),r=_(s(73)),i=_(s(16)),n=_(s(186)),o=s(185);s(212);var l=_(s(82)),u=_(s(81)),c=_(s(17)),f=_(s(78)),d=_(s(32));function _(t){return t&&t.__esModule?t:{default:t}}a.default.component("tv_season",l.default),a.default.component("filters",u.default),new a.default({el:"#app",data:{store:c.default.data,showFilter:!1},created:function(){var t=this;d.default.$on("close-all-popups",function(){t.showFilter=!1}),d.default.$on("close-all-popups-except",function(e){t.$refs.trigger!==e.target&&(t.showFilter=!1)})},beforeMount:function(){var t=this,e=document.getElementById("currentSeason").dataset.season,s=n.default.getJSON("favon-filters");s&&(this.store.filters=s),r.default.get("/api/seasonal/".concat(e)).then(function(e){var s=e.data;t.store.season=s.season,t.store.tv_seasons=s.tvSeasons,t.formatDates(),t.filter()}),r.default.get("/api/genres").then(function(e){t.store.genres=e.data,t.store.genreIds=t.store.genres.map(function(t){return t.id})})},mounted:function(){var t=this;r.default.get("/api/users/me").then(function(e){t.store.user=e.data}).catch(function(){t.store.user=null}),document.addEventListener("click",function(t){null===t.target.closest(".popup, .popup__trigger")&&d.default.$emit("close-all-popups"),null!==t.target.closest(".popup__trigger")&&d.default.$emit("close-all-popups-except",t)}),i.default.select(".select")},methods:{filter:function(){var t=this;n.default.set("favon-filters",this.store.filters,{expires:365}),this.store.filtered=this.store.tv_seasons.filter(function(e){return!1!==f.default.filterSequels(e,t.store.filters.sequels)&&!1!==f.default.filterByScore(e,t.store.filters.score)&&!1!==f.default.filterByGenre(e,t.store.filters.genres.values,t.store.genreIds)&&f.default.filterByLanguage(e,t.store.filters.languages)}),this.store.filtered=this.store.filtered.sort(function(e,s){return f.default.sortBy(e,s,t.store.filters.sort)})},formatDates:function(){this.store.tv_seasons.forEach(function(t){t.first_aired_string=(0,o.format)(new Date(t.first_aired),"MMM D, YYYY")})}}})},212:function(t,e){},30:function(t,e,s){"use strict";s.d(e,"a",function(){return a}),s.d(e,"b",function(){return r});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{class:"popup popup--filters "+(t.showFilter?"is-active":"")},[s("div",{staticClass:"popup__content"},[s("div",{staticClass:"row"},[s("div",{staticClass:"column is-5"},[s("h4",[t._v("Sort By")]),t._v(" "),s("div",{staticClass:"field row"},[s("div",{staticClass:"column is-5"},[s("select",{directives:[{name:"model",rawName:"v-model",value:t.filters.sort,expression:"filters.sort"}],staticClass:"select",attrs:{"data-style":"select"},on:{change:[function(e){var s=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.$set(t.filters,"sort",e.target.multiple?s:s[0])},t.filter]}},[s("option",{attrs:{value:"popularity"}},[t._v("Popularity")]),t._v(" "),s("option",{attrs:{value:"score"}},[t._v("Score")]),t._v(" "),s("option",{attrs:{value:"name"}},[t._v("Name")]),t._v(" "),s("option",{attrs:{value:"start_date"}},[t._v("Start Date")]),t._v(" "),s("option",{attrs:{value:"recently_added"}},[t._v("Recently Added")])])])]),t._v(" "),s("h4",[t._v("Score")]),t._v(" "),s("div",{staticClass:"field is-centered row"},[s("label",{staticClass:"column is-4",attrs:{for:"filter--score"}},[t._v("Minimum score")]),t._v(" "),s("div",{staticClass:"column is-4"},[s("input",{directives:[{name:"model",rawName:"v-model.number",value:t.filters.score,expression:"filters.score",modifiers:{number:!0}}],attrs:{type:"text",id:"filter--score"},domProps:{value:t.filters.score},on:{change:t.filter,input:function(e){e.target.composing||t.$set(t.filters,"score",t._n(e.target.value))},blur:function(e){t.$forceUpdate()}}})])]),t._v(" "),s("h4",[t._v("My List")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--all",checked:""}}),t._v(" "),s("label",{attrs:{for:"filter__list--all"}},[t._v("All")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--not"}}),t._v(" "),s("label",{attrs:{for:"filter__list--not"}},[t._v("Not in my list")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--watching"}}),t._v(" "),s("label",{attrs:{for:"filter__list--watching"}},[t._v("Watching")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--ptw"}}),t._v(" "),s("label",{attrs:{for:"filter__list--ptw"}},[t._v("Plan To Watch")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--completed"}}),t._v(" "),s("label",{attrs:{for:"filter__list--completed"}},[t._v("Completed")]),t._v(" "),s("input",{staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__list--dropped"}}),t._v(" "),s("label",{attrs:{for:"filter__list--dropped"}},[t._v("Dropped")])]),t._v(" "),s("div",{staticClass:"column is-6 is-offset-1"},[s("h4",[t._v("Genres")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.filters.genres.all,expression:"filters.genres.all"}],staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__genres--all"},domProps:{checked:Array.isArray(t.filters.genres.all)?t._i(t.filters.genres.all,null)>-1:t.filters.genres.all},on:{change:[function(e){var s=t.filters.genres.all,a=e.target,r=!!a.checked;if(Array.isArray(s)){var i=t._i(s,null);a.checked?i<0&&t.$set(t.filters.genres,"all",s.concat([null])):i>-1&&t.$set(t.filters.genres,"all",s.slice(0,i).concat(s.slice(i+1)))}else t.$set(t.filters.genres,"all",r)},t.filterAllGenres]}}),t._v(" "),s("label",{attrs:{for:"filter__genres--all"}},[t._v("All")]),t._v(" "),s("div",{staticClass:"row filter__genres is-multiline"},t._l(t.store.genres,function(e){return s("div",{key:e.id,staticClass:"column is-3"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.filters.genres.values,expression:"filters.genres.values"}],staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__genres--"+e.id},domProps:{value:e.id,checked:Array.isArray(t.filters.genres.values)?t._i(t.filters.genres.values,e.id)>-1:t.filters.genres.values},on:{change:[function(s){var a=t.filters.genres.values,r=s.target,i=!!r.checked;if(Array.isArray(a)){var n=e.id,o=t._i(a,n);r.checked?o<0&&t.$set(t.filters.genres,"values",a.concat([n])):o>-1&&t.$set(t.filters.genres,"values",a.slice(0,o).concat(a.slice(o+1)))}else t.$set(t.filters.genres,"values",i)},t.filter]}}),t._v(" "),s("label",{attrs:{for:"filter__genres--"+e.id}},[t._v(t._s(e.name))])])})),t._v(" "),s("h4",[t._v("Languages")]),t._v(" "),t._l(t.store.languages,function(e){return s("div",{key:e.code,staticClass:"filter__languages"},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.filters.languages,expression:"filters.languages"}],staticClass:"checkbox",attrs:{type:"checkbox",id:"filter__languages--"+e.code},domProps:{value:e.code,checked:Array.isArray(t.filters.languages)?t._i(t.filters.languages,e.code)>-1:t.filters.languages},on:{change:[function(s){var a=t.filters.languages,r=s.target,i=!!r.checked;if(Array.isArray(a)){var n=e.code,o=t._i(a,n);r.checked?o<0&&t.$set(t.filters,"languages",a.concat([n])):o>-1&&t.$set(t.filters,"languages",a.slice(0,o).concat(a.slice(o+1)))}else t.$set(t.filters,"languages",i)},t.filter]}}),t._v(" "),s("label",{attrs:{for:"filter__languages--"+e.code}},[t._v(t._s(e.name))])])})],2)])]),t._v(" "),s("div",{staticClass:"popup__arrow"})])},r=[];a._withStripped=!0},31:function(t,e,s){"use strict";s.d(e,"a",function(){return a}),s.d(e,"b",function(){return r});var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"column is-4"},[s("div",{staticClass:"card is-seasonal is-winter"},[s("div",{staticClass:"card__head"},[t.user?[null===t.listStatus?s("a",{ref:"trigger",staticClass:"popup__trigger button is-outline is-tiny is-right",on:{click:function(e){t.showPopup=!t.showPopup}}},[t._v("\n          Add\n        ")]):"ptw"===t.listStatus?s("span",{staticClass:"label__status is-ptw is-right"},[t._v("Plan To Watch")]):"completed"===t.listStatus?s("span",{staticClass:"label__status is-completed is-right"},[t._v("Completed")]):"watching"===t.listStatus?s("span",{staticClass:"label__status is-watching is-right"},[t._v("Currently Watching")]):"hold"===t.listStatus?s("span",{staticClass:"label__status is-ptw is-right"},[t._v("On Hold")]):"dropped"===t.listStatus?s("span",{staticClass:"label__status is-dropped is-right"},[t._v("Dropped")]):t._e(),t._v(" "),s("div",{class:"popup popup--list "+(t.showPopup?"is-active":"")},[s("div",{staticClass:"popup__content"},[s("div",{staticClass:"field is-centered row"},[s("label",{staticClass:"column is-2",attrs:{for:"status"}},[t._v("Status")]),t._v(" "),s("div",{staticClass:"column is-6"},[s("select",{directives:[{name:"model",rawName:"v-model",value:t.status,expression:"status"}],ref:"select",staticClass:"select",attrs:{name:"status",id:"status"},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.status=e.target.multiple?s:s[0]}}},[s("option",{attrs:{value:"ptw"}},[t._v("Plan To Watch")]),t._v(" "),s("option",{attrs:{value:"watching"}},[t._v("Watching")]),t._v(" "),s("option",{attrs:{value:"completed"}},[t._v("Completed")]),t._v(" "),s("option",{attrs:{value:"hold"}},[t._v("On Hold")]),t._v(" "),s("option",{attrs:{value:"dropped"}},[t._v("Dropped")])])])]),t._v(" "),s("button",{staticClass:"button is-info is-narrow button--list",on:{click:t.submit}},[t._v("Add to list")])]),t._v(" "),s("div",{staticClass:"popup__arrow"})])]:t._e()],2),t._v(" "),s("div",{staticClass:"card__body"},[s("div",{staticClass:"body__poster"},[t.tv_season.poster?s("img",{attrs:{src:"https://image.tmdb.org/t/p/w342"+t.tv_season.poster}}):t.tv_season.tv_show.poster?s("img",{attrs:{src:"https://image.tmdb.org/t/p/w342"+t.tv_season.tv_show.poster}}):s("div",{staticClass:"poster__placeholder"},[s("img",{attrs:{src:"/images/poster_placeholder.svg"}})])]),t._v(" "),s("div",{staticClass:"body__description"},[s("h3",{staticClass:"description__title"},[t._v("\n          "+t._s(t.tv_season.tv_show.name)+"\n          "),s("span",{class:"text-"+t.season.name},[t._v(" S"+t._s(t.tv_season.number))])]),t._v(" "),t._l(t.tv_season.tv_show.genres,function(e){return s("span",{key:""+t.tv_season.id+e.id,staticClass:"genre-label"},[t._v("\n          "+t._s(e.name)+"\n        ")])}),t._v(" "),s("p",{staticClass:"description__networks"},[t._v(t._s(t.tv_season.tv_show.networks.map(function(t){return t.name}).join(", ")))]),t._v(" "),t.tv_season.summary?s("p",{staticClass:"description__plot"},[t._v("\n          "+t._s(t.tv_season.summary)+"\n        ")]):s("p",{staticClass:"description__plot"},[t._v("\n          "+t._s(t.tv_season.tv_show.summary)+"\n        ")])],2)]),t._v(" "),s("div",{staticClass:"card__footer"},[s("div",{staticClass:"flex-left"},[s("span",[t._v(t._s(t.tv_season.first_aired_string))])]),t._v(" "),s("div",{staticClass:"flex-center"},[s("span",[t._v(t._s(0===t.tv_season.episode_count?"?":t.tv_season.episode_count)+" Eps.")])]),t._v(" "),s("div",{staticClass:"flex-right"},[s("img",{attrs:{src:"/images/imdb.svg"}}),t._v(" "),0===t.tv_season.tv_show.imdb_score?s("span",[t._v("N/A")]):s("span",[s("a",{attrs:{href:"http://www.imdb.com/title/"+t.tv_season.tv_show.imdb_id+"/"}},[t._v(t._s(t.tv_season.tv_show.imdb_score))])]),t._v(" "),s("img",{attrs:{src:"/images/heart.svg"}}),t._v(" "),s("span",[t._v("0")]),t._v(" "),s("img",{attrs:{src:"/images/star.svg"}}),t._v(" "),s("span",[t._v("0")])])])])])},r=[];a._withStripped=!0},32:function(t,e,s){"use strict";var a;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=new(((a=s(75))&&a.__esModule?a:{default:a}).default);e.default=r},78:function(t,e,s){"use strict";function a(t,e){for(var s=0;s<e.length;s++){var a=e[s];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(t,a.key,a)}}Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var r=function(){function t(){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,t)}var e,s;return e=t,s=[{key:"sortBy",value:function(t,e,s){switch(s){case"score":return this.compareByScore(t,e);case"name":return this.compareByName(t,e);case"start_date":return this.compareByStartDate(t,e);case"recently_added":return this.compareByRecentlyAdded(t,e);default:return this.compareByPopularity(t,e)}}},{key:"filterByGenre",value:function(t,e,s){var a=new Set(e),r=new Set(function(t){return function(t){if(Array.isArray(t)){for(var e=0,s=new Array(t.length);e<t.length;e++)s[e]=t[e];return s}}(t)||function(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}(s).filter(function(t){return!a.has(t)}));return!t.tv_show.genres.some(function(t){return r.has(t.id)})}},{key:"filterByScore",value:function(t,e){return 0===t.tv_show.imdb_score||t.tv_show.imdb_score>=e}},{key:"filterSequels",value:function(t,e){return!!e||1===t.number}},{key:"filterByLanguage",value:function(t,e){var s=new Set(e);return t.tv_show.languages.some(function(t){return s.has(t.pivot.language_code)})}},{key:"compareByScore",value:function(t,e){return t.tv_show.imdb_score>e.tv_show.imdb_score?-1:t.tv_show.imdb_score<e.tv_show.imdb_score?1:0}},{key:"compareByName",value:function(t,e){return t.tv_show.name.localeCompare(e.tv_show.name)}},{key:"compareByStartDate",value:function(t,e){return t.first_aired>e.first_aired?-1:t.first_aired<e.first_aired?1:this.compareByName(t,e)}},{key:"compareByRecentlyAdded",value:function(t,e){return t.created_at>e.created_at?-1:t.created_at<e.created_at?1:this.compareByName(t,e)}},{key:"compareByPopularity",value:function(t,e){return t.tv_show.popularity>e.tv_show.popularity?-1:t.tv_show.popularity<e.tv_show.popularity?1:this.compareByName(t,e)}}],null&&a(e.prototype,null),s&&a(e,s),t}();e.default=r},79:function(t,e,s){(t.exports=s(83)(!1)).push([t.i,"\n.filter__languages {\n  display: inline-block;\n}\n",""])},80:function(t,e,s){var a=s(79);"string"==typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals),(0,s(77).default)("c4e13ce0",a,!1,{})},81:function(t,e,s){"use strict";s.r(e);var a=s(13),r=s.n(a);for(var i in a)"default"!==i&&function(t){s.d(e,t,function(){return a[t]})}(i);var n=s(30),o=s(15),l=Object(o.a)(r.a,n.a,n.b,!1,function(t){s(80)},null,null);l.options.__file="resources/assets/js/seasonal/components/Filters.vue",e.default=l.exports},82:function(t,e,s){"use strict";s.r(e);var a=s(14),r=s.n(a);for(var i in a)"default"!==i&&function(t){s.d(e,t,function(){return a[t]})}(i);var n=s(31),o=s(15),l=Object(o.a)(r.a,n.a,n.b,!1,null,null,null);l.options.__file="resources/assets/js/seasonal/components/TvSeasonCard.vue",e.default=l.exports}});