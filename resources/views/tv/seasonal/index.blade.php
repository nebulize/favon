@extends('layouts.main')
@section('content')
    <div class="banner is-{{ lcfirst($season->name) }}">
        <div class="container">
            <img class="banner__image" src="/images/banner-{{ lcfirst($season->name) }}.svg">
        </div>
            <div class="banner__top">
                <div class="container">
                    <h2>{{ $season->name }} {{ $season->year }}</h2>
                </div>
            </div>
            <div class="banner__bottom"></div>
    </div>
    <div class="filters has-depth-1">
        <div class="container">
            <div class="filters__seasons">
                <ul class="seasons__list">
                    @foreach ($seasons['before'] as $seasonBefore)
                        <li><a href="{{ route('tv.seasonal.index', [$seasonBefore->year, lcfirst($seasonBefore->name)]) }}">{{ ucfirst($seasonBefore->name) }} {{ $seasonBefore->year }}</a></li>
                    @endforeach
                    <li class="is-active"><a class="text-{{ lcfirst($season->name) }}" href="{{ route('tv.seasonal.index', [$season->year, lcfirst($season->name)]) }}">{{ ucfirst($season->name) }} {{ $season->year }}</a></li>
                    @foreach ($seasons['after'] as $seasonAfter)
                        <li><a href="{{ route('tv.seasonal.index', [$seasonAfter->year, lcfirst($seasonAfter->name)]) }}">{{ ucfirst($seasonAfter->name) }} {{ $seasonAfter->year }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="filters__list is-right">
                <div class="toggle">
                    <input type="checkbox" id="filter__sequels" name="filter__sequels">
                    <label for="filter__sequels"></label>
                </div>
                <span>Show sequels</span>
                <a class="popup__trigger" data-trigger="popup--filters">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 845.11 820.06"><title>filters</title><path d="M289.14,856h624a28,28,0,0,0,0-56h-624a28,28,0,1,0,0,56Z" transform="translate(-96.03 -103.44)"/><circle cx="109.48" cy="724.52" r="81.48"/><path d="M205.51,760.48c2.79,0,5.56.16,8.34.42,4.46.41-2.7-.48,1.7.23,1.33.22,2.66.5,4,.78a80.08,80.08,0,0,1,7.75,2.14c1.27.4,2.52.87,3.76,1.34,1.54.61,1.65.65.33.1.8.36,1.6.73,2.39,1.11a81.62,81.62,0,0,1,13.43,8.15c-2.29-1.69,1.08.93,1.24,1.07,1,.84,2,1.72,2.92,2.6a83.55,83.55,0,0,1,6.29,6.61c.14.15,2.79,3.51,1.07,1.24.79,1,1.53,2.12,2.27,3.19,1.49,2.16,2.81,4.43,4.08,6.72.64,1.15,1.22,2.34,1.8,3.52.1.18,1.83,4.15.73,1.48a81.31,81.31,0,0,1,3,8.84,79.58,79.58,0,0,1,1.75,7.9c.79,4.39-.25-2.75.24,1.71.15,1.37.23,2.75.31,4.13a81,81,0,0,1,0,8.39c-.06,1.38-.18,2.76-.31,4.14-.2,1.7-.21,1.82,0,.35-.14.9-.29,1.8-.46,2.69a80.9,80.9,0,0,1-4.5,15.4c1.06-2.68-.63,1.29-.73,1.49-.56,1.19-1.18,2.35-1.79,3.51a82.26,82.26,0,0,1-4.83,7.8c-.11.17-2.7,3.59-.93,1.35-.81,1-1.67,2-2.52,3-1.71,2-3.55,3.82-5.44,5.62-.94.9-1.93,1.75-2.91,2.61-.16.13-3.51,2.79-1.25,1.06a82.59,82.59,0,0,1-7.62,5.07c-2.26,1.35-4.62,2.53-7,3.65-3.9,1.84,2.5-.94-1.53.65-1.23.49-2.49.91-3.75,1.34-2.55.86-5.15,1.53-7.76,2.13-1.32.31-2.66.55-4,.78-1.68.26-1.8.29-.34.09-.91.11-1.82.2-2.74.28a82.73,82.73,0,0,1-16.65-.28c3,.33-1.45-.28-1.67-.32-1.34-.24-2.65-.55-4-.85a80.27,80.27,0,0,1-9-2.7c-.83-.31-1.66-.63-2.48-1,2.68,1.06-1.29-.63-1.49-.72-2-.94-3.9-2-5.8-3.09s-3.71-2.3-5.51-3.53c-.71-.5-1.41-1-2.11-1.54,2.29,1.69-1.09-.93-1.25-1.06a82.48,82.48,0,0,1-9.2-9.21c-.57-.66-1.12-1.33-1.67-2,1.8,2.2-.81-1.18-.93-1.35-1.27-1.78-2.42-3.64-3.54-5.51s-2.12-3.84-3.08-5.8c-.39-.8-.76-1.59-1.11-2.4,1.19,2.63-.5-1.37-.57-1.57a80.51,80.51,0,0,1-3.55-12.92c-.17-.9-.32-1.79-.46-2.69.48,3-.13-1.51-.15-1.73-.21-2.31-.27-4.63-.29-6.95s.12-4.65.29-7c.08-.92.17-1.83.28-2.74-.32,3,.29-1.45.33-1.67a79.32,79.32,0,0,1,3.55-12.93c.3-.83.62-1.66,1-2.48-1.06,2.68.63-1.29.73-1.49.93-2,2-3.9,3.08-5.8s2.3-3.71,3.54-5.51c.5-.71,1-1.42,1.53-2.11-1.69,2.29.93-1.08,1.07-1.25a81.56,81.56,0,0,1,9.2-9.2c.67-.57,1.34-1.12,2-1.67-2.21,1.8,1.17-.81,1.34-.93,1.78-1.27,3.64-2.42,5.51-3.53s3.84-2.13,5.8-3.09q1.2-.58,2.4-1.11c-2.63,1.19,1.37-.5,1.57-.57a80.51,80.51,0,0,1,12.92-3.55c.9-.17,1.79-.32,2.69-.46-3,.48,1.51-.12,1.73-.15,2.31-.21,4.63-.27,6.95-.28a14,14,0,0,0,0-28,97.36,97.36,0,0,0-66.59,27c-17.9,17.07-27.81,41.1-28.84,65.63-1,24.88,8.78,49.11,25.22,67.49s40.23,28.75,64.63,30.61c25,1.89,49.38-7.48,68.31-23.36s29.67-39.33,32.34-63.61c2.75-25-6.16-49.58-21.47-69-15.16-19.29-38.49-31-62.54-34a93,93,0,0,0-11.06-.74,14,14,0,0,0,0,28Z" transform="translate(-96.03 -103.44)"/><path d="M762.5,541.44H913.14a28,28,0,0,0,0-56H762.5a28,28,0,1,0,0,56Z" transform="translate(-96.03 -103.44)"/><path d="M124,541.44H591.49a28,28,0,0,0,0-56H124a28,28,0,1,0,0,56Z" transform="translate(-96.03 -103.44)"/><circle cx="581.24" cy="410" r="81.48"/><path d="M677.27,446c2.78,0,5.55.16,8.33.42,4.46.41-2.7-.47,1.7.23,1.34.22,2.66.5,4,.79,2.62.56,5.2,1.31,7.76,2.13,1.26.4,2.51.87,3.75,1.34,1.54.62,1.65.65.33.11.81.35,1.6.72,2.4,1.1A81.32,81.32,0,0,1,719,460.24c-2.29-1.69,1.09.93,1.25,1.06,1,.84,1.95,1.73,2.91,2.61a81.77,81.77,0,0,1,6.3,6.6c.13.16,2.79,3.51,1.06,1.25.79,1,1.53,2.11,2.27,3.19,1.49,2.16,2.82,4.43,4.09,6.72.63,1.15,1.22,2.33,1.8,3.51.09.19,1.82,4.16.72,1.49a81.14,81.14,0,0,1,3,8.83,79.15,79.15,0,0,1,1.75,7.91c.79,4.38-.25-2.75.24,1.7.15,1.38.23,2.76.31,4.14a81,81,0,0,1,0,8.39c-.06,1.38-.18,2.76-.31,4.14-.2,1.7-.2,1.82,0,.35-.14.9-.29,1.79-.46,2.69a80.39,80.39,0,0,1-4.5,15.4c1.06-2.68-.63,1.29-.73,1.49-.56,1.19-1.18,2.35-1.79,3.51A82.26,82.26,0,0,1,732,553c-.11.16-2.7,3.59-.93,1.35-.81,1-1.67,2-2.52,3-1.71,2-3.55,3.82-5.44,5.62-.94.9-1.93,1.75-2.91,2.61-.16.13-3.52,2.79-1.25,1.06a82.59,82.59,0,0,1-7.62,5.07c-2.26,1.34-4.62,2.53-7,3.65-3.9,1.84,2.5-.94-1.53.65-1.23.49-2.49.91-3.75,1.34-2.55.86-5.15,1.53-7.76,2.13-1.32.31-2.66.55-4,.78-1.68.26-1.8.29-.34.09-.91.11-1.82.2-2.74.28a82.73,82.73,0,0,1-16.65-.28c3,.32-1.45-.29-1.67-.32-1.34-.24-2.65-.55-4-.85a82.43,82.43,0,0,1-9-2.7c-.83-.31-1.66-.63-2.48-1,2.68,1.06-1.29-.63-1.49-.72-2-.94-3.9-2-5.8-3.09s-3.71-2.3-5.51-3.54c-.71-.5-1.42-1-2.11-1.53,2.29,1.69-1.08-.93-1.25-1.06a82.48,82.48,0,0,1-9.2-9.21c-.57-.66-1.12-1.34-1.67-2,1.8,2.21-.81-1.17-.93-1.35-1.27-1.77-2.42-3.63-3.53-5.51s-2.13-3.83-3.09-5.79q-.58-1.2-1.11-2.4c1.19,2.63-.5-1.37-.57-1.57a80.34,80.34,0,0,1-3.55-12.93c-.17-.89-.32-1.78-.46-2.68.48,3-.12-1.51-.15-1.73-.21-2.31-.27-4.64-.29-7s.12-4.64.29-7c.08-.92.17-1.83.28-2.74-.32,3,.29-1.45.33-1.68a79.48,79.48,0,0,1,3.55-12.92c.3-.83.62-1.66.95-2.48-1.06,2.68.63-1.29.73-1.49.93-2,2-3.9,3.09-5.8s2.29-3.71,3.53-5.51c.5-.71,1-1.42,1.53-2.11-1.69,2.29.93-1.09,1.07-1.25a82.48,82.48,0,0,1,9.2-9.21l2-1.66c-2.21,1.8,1.17-.81,1.35-.93,1.77-1.27,3.63-2.42,5.51-3.54s3.83-2.12,5.8-3.09c.79-.38,1.59-.75,2.39-1.1-2.63,1.19,1.37-.5,1.57-.58a81.39,81.39,0,0,1,12.93-3.55c.89-.16,1.78-.31,2.68-.45-3,.47,1.51-.13,1.73-.15,2.31-.21,4.63-.27,7-.29a14,14,0,0,0,0-28A97.32,97.32,0,0,0,610.68,445c-17.91,17.08-27.82,41.11-28.85,65.63-1.05,24.88,8.78,49.11,25.22,67.49s40.23,28.75,64.64,30.61c25,1.9,49.37-7.48,68.31-23.36s29.66-39.33,32.33-63.6c2.75-25-6.16-49.58-21.46-69-15.17-19.3-38.5-31-62.55-34a94.93,94.93,0,0,0-11.05-.74,14,14,0,0,0,0,28Z" transform="translate(-96.03 -103.44)"/><path d="M462.3,226.93H913.14a28,28,0,0,0,0-56H462.3a28,28,0,0,0,0,56Z" transform="translate(-96.03 -103.44)"/><path d="M124,226.93H291.29a28,28,0,0,0,0-56H124a28,28,0,0,0,0,56Z" transform="translate(-96.03 -103.44)"/><circle cx="281.03" cy="95.49" r="81.48"/><path d="M377.06,131.44c2.79,0,5.56.17,8.33.42,4.46.42-2.69-.47,1.7.24,1.34.22,2.67.5,4,.78,2.63.56,5.21,1.32,7.76,2.13,1.27.41,2.51.88,3.76,1.35,1.53.61,1.64.65.33.1.8.36,1.6.73,2.39,1.11a81.62,81.62,0,0,1,13.43,8.15c-2.29-1.68,1.08.93,1.24,1.07,1,.84,2,1.72,2.92,2.61a81.72,81.72,0,0,1,6.29,6.6c.13.15,2.79,3.51,1.07,1.24.79,1,1.53,2.12,2.27,3.2,1.48,2.16,2.81,4.42,4.08,6.71.64,1.16,1.22,2.34,1.8,3.52.09.19,1.83,4.15.73,1.49a78.31,78.31,0,0,1,3,8.83c.74,2.6,1.28,5.25,1.76,7.91.78,4.38-.26-2.76.23,1.7.16,1.37.24,2.76.32,4.14a83.65,83.65,0,0,1,0,8.38c-.06,1.39-.19,2.76-.32,4.14-.19,1.7-.2,1.82,0,.36-.14.89-.29,1.79-.46,2.68a80.9,80.9,0,0,1-4.5,15.4c1.06-2.68-.64,1.3-.73,1.49-.56,1.19-1.18,2.35-1.8,3.51a82.17,82.17,0,0,1-4.82,7.8c-.12.17-2.7,3.59-.94,1.35-.8,1-1.66,2-2.51,3-1.71,2-3.56,3.81-5.44,5.61-1,.9-1.93,1.76-2.92,2.61-.15.13-3.51,2.79-1.24,1.07a80.66,80.66,0,0,1-7.63,5.06c-2.26,1.35-4.61,2.53-7,3.65-3.9,1.84,2.5-.93-1.53.65-1.24.49-2.5.92-3.76,1.34-2.54.86-5.14,1.53-7.76,2.14-1.32.3-2.65.54-4,.78-1.68.26-1.79.29-.33.08-.91.11-1.83.2-2.74.28a82.11,82.11,0,0,1-16.66-.28c3,.33-1.44-.28-1.67-.32-1.33-.24-2.65-.54-4-.85a79.64,79.64,0,0,1-9-2.7c-.83-.3-1.66-.62-2.48-1,2.68,1.05-1.29-.64-1.49-.73-2-.94-3.9-2-5.8-3.09s-3.71-2.3-5.51-3.53c-.71-.5-1.42-1-2.12-1.53,2.3,1.68-1.08-.94-1.24-1.07a83.43,83.43,0,0,1-9.21-9.21c-.56-.66-1.12-1.33-1.66-2,1.79,2.21-.82-1.18-.94-1.35-1.26-1.78-2.41-3.64-3.53-5.51s-2.12-3.83-3.09-5.8q-.57-1.19-1.11-2.4c1.19,2.63-.49-1.37-.57-1.57a81.27,81.27,0,0,1-3.55-12.92c-.16-.89-.32-1.79-.45-2.68.47,2.95-.13-1.51-.15-1.73-.21-2.32-.27-4.64-.29-7s.11-4.64.29-7c.08-.91.17-1.83.28-2.74-.33,3,.28-1.45.32-1.67A81.27,81.27,0,0,1,314,174.64c.31-.84.62-1.66,1-2.48-1.05,2.68.64-1.29.73-1.49.94-2,2-3.9,3.09-5.8s2.3-3.71,3.53-5.51c.5-.71,1-1.42,1.53-2.12-1.68,2.29.94-1.08,1.07-1.24a82.57,82.57,0,0,1,9.21-9.21c.66-.56,1.33-1.12,2-1.66-2.21,1.79,1.18-.82,1.35-.94,1.78-1.26,3.64-2.41,5.51-3.53s3.84-2.12,5.8-3.09q1.18-.57,2.4-1.11c-2.63,1.19,1.37-.5,1.57-.57a81.27,81.27,0,0,1,12.92-3.55c.89-.17,1.79-.32,2.68-.46-2.95.48,1.52-.12,1.73-.14,2.31-.22,4.64-.28,7-.3a14,14,0,0,0,0-28,97.35,97.35,0,0,0-66.59,27.06c-17.91,17.07-27.81,41.1-28.85,65.63-1.05,24.87,8.78,49.11,25.22,67.49s40.24,28.75,64.64,30.6c25,1.9,49.38-7.47,68.31-23.35s29.66-39.34,32.33-63.61c2.76-25-6.16-49.58-21.46-69-15.16-19.29-38.5-31-62.54-34a93,93,0,0,0-11.06-.75,14,14,0,0,0,0,28Z" transform="translate(-96.03 -103.44)"/></svg>
                    <span>Customize filters</span>
                </a>
                <div class="popup popup--filters" id="popup--filters">
                    <div class="popup__content">
                        <div class="row">
                            <div class="column is-5">
                                <h4>Sort By</h4>
                                <div class="field row">
                                    <div class="column is-5">
                                        <select data-style="select">
                                            <option>Popularity</option>
                                            <option>Score</option>
                                            <option>Name</option>
                                            <option>Start Date</option>
                                            <option>Recently Added</option>
                                        </select>
                                    </div>
                                </div>
                                <h4>Votes / Score</h4>
                                <div class="field is-centered row">
                                    <label for="filter--votes" class="column is-3">Minimum votes</label>
                                    <div class="column is-4">
                                        <input type="text" id="filter--votes" name="filter--votes" value="2000">
                                    </div>
                                </div>
                                <div class="field is-centered row">
                                    <label for="filter--score" class="column is-3">Minimum score</label>
                                    <div class="column is-4">
                                        <input type="text" id="filter--score" name="filter-score" value="0">
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
                                <label for="filter__list--ptw">PTW</label>
                                <input type="checkbox" class="checkbox" id="filter__list--completed">
                                <label for="filter__list--completed">Completed</label>
                                <input type="checkbox" class="checkbox" id="filter__list--dropped">
                                <label for="filter__list--dropped">Dropped</label>
                            </div>
                            <div class="column is-6 is-offset-1">
                                <h4>Genres</h4>
                                <input type="checkbox" class="checkbox" id="filter__genres--all">
                                <label for="filter__genres--all">All</label>
                                <div class="row is-multiline">
                                    @foreach ($genres as $genre)
                                        <div class="column is-3">
                                            <input type="checkbox" class="checkbox" id="filter__genres--{{ $genre->id }}" {{ \in_array($genre->id, [3, 11, 12, 13, 18, 22], true) ? '' : 'checked' }}>
                                            <label for="filter__genres--{{ $genre->id }}">{{ $genre->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <h4>Languages</h4>
                                <input type="checkbox" class="checkbox" id="filter__languages--en" checked>
                                <label for="filter__languages--en">English</label>
                                <input type="checkbox" class="checkbox" id="filter__languages--ja">
                                <label for="filter__languages--ja">Japanese</label>
                                <input type="checkbox" class="checkbox" id="filter__languages--de">
                                <label for="filter__languages--de">German</label>
                                <input type="checkbox" class="checkbox" id="filter__languages--fr">
                                <label for="filter__languages--fr">French</label>
                                <input type="checkbox" class="checkbox" id="filter__languages--ko">
                                <label for="filter__languages--ko">Korean</label>
                                <input type="checkbox" class="checkbox" id="filter__languages--es">
                                <label for="filter__languages--es">Spanish</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column">
                                <div class="popup__confirm">
                                    <button class="button is-narrow is-info">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup__arrow"></div>
                </div>
            </div>
        </div>
    </div>
    <main class="seasonal">
        <div class="container">
            <div class="row is-multiline">
                @foreach ($tvSeasons as $tvSeason)
                    <div class="column is-4">
                        <div class="card is-seasonal is-winter">
                            <div class="card__head">

                            </div>
                            <div class="card__body">
                                <div class="body__poster">
                                    @if ($tvSeason->poster)
                                        <img src="http://image.tmdb.org/t/p/w342{{ $tvSeason->poster }}">
                                    @elseif($tvSeason->tvShow->poster)
                                        <img src="http://image.tmdb.org/t/p/w342{{ $tvSeason->tvShow->poster }}">
                                    @else
                                        <div class="poster__placeholder">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#b5b5b5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="body__description">
                                    <h3 class="description__title">{{ $tvSeason->tvShow->name }} <span class="text-{{ lcfirst($season->name) }}">S{{ $tvSeason->number }}</span></h3>
                                    @foreach ($tvSeason->tvShow->genres as $genre)
                                        <span class="genre-label">{{ $genre->name }}</span>
                                    @endforeach
                                    @if ($tvSeason->summary)
                                        <p class="description__plot">{{ $tvSeason->summary }}</p>
                                    @else
                                        <p class="description__plot">{{ $tvSeason->tvShow->summary }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="card__footer">
                                <span>{{ \Carbon\Carbon::parse($tvSeason->season_first_aired)->format('M d, Y') }}</span>
                                <div class="flex-group is-right">
                                    <img src="/images/imdb.svg">
                                    <span>{{ $tvSeason->tvShow->imdb_score == 0 ? 'N/A' : $tvSeason->tvShow->imdb_score }}</span>
                                    <img src="/images/heart.svg">
                                    <span>0</span>
                                    <img src="/images/star.svg">
                                    <span>0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </main>

@endsection
