<nav class="nav is-desktop">
  <div class="container">
    <div class="nav-left">
      <a class="nav-item nav-brand" href="#!">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 663.7 186.87" class="logo is-{{ lcfirst($currentSeason->name) }}"><title>Favon</title><polygon class="a" points="75.86 44.58 0 0 0 78.84 75.86 44.58"/><polygon class="b" points="88.78 52.17 0 139.75 0 186.87 159 93.43 88.78 52.17"/><path class="c" d="M631.41,327.59v23.1h46.34v22.95H631.41v36h-29.7v-105h82.34v22.95Z" transform="translate(-379 -265)"/><path class="c" d="M760.41,336.22q10.64,9.22,10.65,28.27v45.15H744.51v-10.5q-6.16,11.85-24,11.85a37.53,37.53,0,0,1-16.35-3.3,24.24,24.24,0,0,1-10.43-8.92,23.66,23.66,0,0,1-3.52-12.83q0-11.55,8.85-17.92t27.3-6.38h16.2q-.76-12.9-17.25-12.9a39.53,39.53,0,0,0-11.85,1.88,31.47,31.47,0,0,0-10.2,5.17l-9.6-19.35a53.14,53.14,0,0,1,16.42-7A78.08,78.08,0,0,1,729.51,327Q749.76,327,760.41,336.22Zm-23.25,54.37a13.46,13.46,0,0,0,5.4-7v-7.05h-12.3q-12.46,0-12.45,8.25a7.23,7.23,0,0,0,2.85,6c1.89,1.5,4.5,2.25,7.8,2.25A15.79,15.79,0,0,0,737.16,390.59Z" transform="translate(-379 -265)"/><path class="c" d="M873.21,328.34l-33.6,81.3h-29.4l-33.46-81.3H806L825.5,378l20.56-49.65Z" transform="translate(-379 -265)"/><path class="c" d="M878.15,360.37a54.74,54.74,0,0,1,17.63-21.9q11.78-8.78,28.72-8.78,13.05,0,19.88,6.6t6.82,18.9a63.24,63.24,0,0,1-5.85,25.88A54.71,54.71,0,0,1,927.73,403Q916,411.75,899,411.74q-13.05,0-19.87-6.6t-6.83-18.9A63.19,63.19,0,0,1,878.15,360.37Zm35-16.43a127.71,127.71,0,0,0-11.17,27.23q-4.73,16-4.73,29.17a21.66,21.66,0,0,0,.68,6.53c.45,1.25,1.17,1.87,2.17,1.87q4.05,0,10.43-10.87a122.34,122.34,0,0,0,11.1-26.63,104,104,0,0,0,4.72-29.85q0-5.25-.67-7c-.45-1.15-1.08-1.73-1.88-1.73Q919.55,332.69,913.1,343.94Z" transform="translate(-379 -265)"/><path class="c" d="M1038.8,385.34h2.85l-3.75,11q-5.4,15.45-21.45,15.45-9.6,0-13.5-5.4a13.74,13.74,0,0,1-2.55-8.4,39.48,39.48,0,0,1,2.4-12l12.9-38.85a18.4,18.4,0,0,0,1.2-5.85q0-2.25-1.5-2.25-3.9,0-10,10.5a192.14,192.14,0,0,0-12.52,26.85,303.11,303.11,0,0,0-10.73,32.85v-.3l-.15.75H955.4l18.3-65.1a17.37,17.37,0,0,0,.9-4.35q0-1.95-2.25-1.95a5.84,5.84,0,0,0-4.35,2.1q-1.95,2.1-3.9,7.35l-3.45,9.15H957.8l4.2-11.7q2.85-8,7.8-11.7t12.75-3.75q8.4,0,12.08,3.9a14.26,14.26,0,0,1,3.67,10.2,27.24,27.24,0,0,1-.9,6.9l-4,15.6q5.85-14.7,11.18-22.57t10.87-11a25.56,25.56,0,0,1,12.6-3.08q14.7,0,14.7,12.45a35.16,35.16,0,0,1-2,10.2l-14.85,44.55a15.08,15.08,0,0,0-.75,3.6q0,2.7,2.55,2.7,4.35,0,8-9.45Z" transform="translate(-379 -265)"/></svg>
      </a>
      <div class="nav-item nav__search">
        <img src="/images/search.png" alt="Search">
        <input placeholder="Search">
      </div>
    </div>
    <div class="nav-right">
      <div class="nav-item is-link">
        <a href="#!">Home</a>
      </div>
      <div class="nav-item is-link {{ Route::currentRouteName() === 'tv.seasonal.index' ? 'is-active' : '' }}">
        <a href="{{ route('television.seasonal.show') }}">Seasonal</a>
      </div>
      <div class="nav-item is-link">
        <a href="#!">Top Rated</a>
      </div>
      <div class="nav-item is-link has-margin-right">
        <a href="#!">Calendar</a>
      </div>

      @if (Auth::check())
        <div class="nav-item has-padding">
          <a href="#!" class="nav__icon icon--inbox">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18"><title>Messages</title><path d="M8,11v2.4l12,5.8,12-5.8V11Zm0,4.5V29H32V15.5L20,21.2Z" transform="translate(-8 -11)"/></svg>
          </a>
        </div>
        <div class="nav-item has-padding">
          <a href="#!" class="nav__icon icon--notifications">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.5 81.5"><title>Notifications</title><path class="a" d="M80.3,71.52l-5.55-8.31V40.75A24.75,24.75,0,0,0,53.5,16.25v-3.5a3.5,3.5,0,0,0-7,0v3.5a24.75,24.75,0,0,0-21.25,24.5V63.21L19.7,71.52a3.56,3.56,0,0,0,0,3.49,3.41,3.41,0,0,0,3,1.74H77.33a3.39,3.39,0,0,0,3-1.74A3.56,3.56,0,0,0,80.3,71.52ZM50,90.75c4.4,0,8.18-4.13,9.8-10H40.2C41.82,86.62,45.6,90.75,50,90.75Z" transform="translate(-19.25 -9.25)"/></svg>
          </a>
        </div>
        <div class="nav-item has-padding">
          <a href="#!">
            <img src="/images/avatar.png">
          </a>
        </div>
      @else
        <div class="nav-item is-link {{ Route::currentRouteName() === 'login' ? 'is-active' : '' }}">
          <a href="{{ route('login') }}">Login</a>
        </div>
        <div class="nav-item is-link {{ Route::currentRouteName() === 'register' ? 'is-active' : '' }}">
          <a href="{{ route('register') }}">Sign Up</a>
        </div>
      @endif

    </div>
  </div>
</nav>
<div class="nav is-toggle">
  <div class="nav-left">
    <a class="nav-item nav-brand" href="/">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 663.7 186.87" class="logo is-{{ lcfirst($currentSeason->name) }}"><title>Logo</title><polygon class="a" points="75.86 44.58 0 0 0 78.84 75.86 44.58"/><polygon class="b" points="88.78 52.17 0 139.75 0 186.87 159 93.43 88.78 52.17"/><path class="c" d="M631.41,327.59v23.1h46.34v22.95H631.41v36h-29.7v-105h82.34v22.95Z" transform="translate(-379 -265)"/><path class="c" d="M760.41,336.22q10.64,9.22,10.65,28.27v45.15H744.51v-10.5q-6.16,11.85-24,11.85a37.53,37.53,0,0,1-16.35-3.3,24.24,24.24,0,0,1-10.43-8.92,23.66,23.66,0,0,1-3.52-12.83q0-11.55,8.85-17.92t27.3-6.38h16.2q-.76-12.9-17.25-12.9a39.53,39.53,0,0,0-11.85,1.88,31.47,31.47,0,0,0-10.2,5.17l-9.6-19.35a53.14,53.14,0,0,1,16.42-7A78.08,78.08,0,0,1,729.51,327Q749.76,327,760.41,336.22Zm-23.25,54.37a13.46,13.46,0,0,0,5.4-7v-7.05h-12.3q-12.46,0-12.45,8.25a7.23,7.23,0,0,0,2.85,6c1.89,1.5,4.5,2.25,7.8,2.25A15.79,15.79,0,0,0,737.16,390.59Z" transform="translate(-379 -265)"/><path class="c" d="M873.21,328.34l-33.6,81.3h-29.4l-33.46-81.3H806L825.5,378l20.56-49.65Z" transform="translate(-379 -265)"/><path class="c" d="M878.15,360.37a54.74,54.74,0,0,1,17.63-21.9q11.78-8.78,28.72-8.78,13.05,0,19.88,6.6t6.82,18.9a63.24,63.24,0,0,1-5.85,25.88A54.71,54.71,0,0,1,927.73,403Q916,411.75,899,411.74q-13.05,0-19.87-6.6t-6.83-18.9A63.19,63.19,0,0,1,878.15,360.37Zm35-16.43a127.71,127.71,0,0,0-11.17,27.23q-4.73,16-4.73,29.17a21.66,21.66,0,0,0,.68,6.53c.45,1.25,1.17,1.87,2.17,1.87q4.05,0,10.43-10.87a122.34,122.34,0,0,0,11.1-26.63,104,104,0,0,0,4.72-29.85q0-5.25-.67-7c-.45-1.15-1.08-1.73-1.88-1.73Q919.55,332.69,913.1,343.94Z" transform="translate(-379 -265)"/><path class="c" d="M1038.8,385.34h2.85l-3.75,11q-5.4,15.45-21.45,15.45-9.6,0-13.5-5.4a13.74,13.74,0,0,1-2.55-8.4,39.48,39.48,0,0,1,2.4-12l12.9-38.85a18.4,18.4,0,0,0,1.2-5.85q0-2.25-1.5-2.25-3.9,0-10,10.5a192.14,192.14,0,0,0-12.52,26.85,303.11,303.11,0,0,0-10.73,32.85v-.3l-.15.75H955.4l18.3-65.1a17.37,17.37,0,0,0,.9-4.35q0-1.95-2.25-1.95a5.84,5.84,0,0,0-4.35,2.1q-1.95,2.1-3.9,7.35l-3.45,9.15H957.8l4.2-11.7q2.85-8,7.8-11.7t12.75-3.75q8.4,0,12.08,3.9a14.26,14.26,0,0,1,3.67,10.2,27.24,27.24,0,0,1-.9,6.9l-4,15.6q5.85-14.7,11.18-22.57t10.87-11a25.56,25.56,0,0,1,12.6-3.08q14.7,0,14.7,12.45a35.16,35.16,0,0,1-2,10.2l-14.85,44.55a15.08,15.08,0,0,0-.75,3.6q0,2.7,2.55,2.7,4.35,0,8-9.45Z" transform="translate(-379 -265)"/></svg>
    </a>
  </div>
  <div class="nav-right">
    <button class="nav-item hamburger hamburger--spin" type="button">
      <span class="hamburger-box">
        <span class="hamburger-inner"></span>
      </span>
    </button>
  </div>
</div>

<!-- Responsive navigation -->
<nav class="nav is-responsive">
  <ul>
    <li class="nav-brand"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 663.7 186.87" class="is-{{ lcfirst($currentSeason->name) }}"><title>Favon</title><polygon class="a" points="75.86 44.58 0 0 0 78.84 75.86 44.58"/><polygon class="b" points="88.78 52.17 0 139.75 0 186.87 159 93.43 88.78 52.17"/><path class="c" d="M631.41,327.59v23.1h46.34v22.95H631.41v36h-29.7v-105h82.34v22.95Z" transform="translate(-379 -265)"/><path class="c" d="M760.41,336.22q10.64,9.22,10.65,28.27v45.15H744.51v-10.5q-6.16,11.85-24,11.85a37.53,37.53,0,0,1-16.35-3.3,24.24,24.24,0,0,1-10.43-8.92,23.66,23.66,0,0,1-3.52-12.83q0-11.55,8.85-17.92t27.3-6.38h16.2q-.76-12.9-17.25-12.9a39.53,39.53,0,0,0-11.85,1.88,31.47,31.47,0,0,0-10.2,5.17l-9.6-19.35a53.14,53.14,0,0,1,16.42-7A78.08,78.08,0,0,1,729.51,327Q749.76,327,760.41,336.22Zm-23.25,54.37a13.46,13.46,0,0,0,5.4-7v-7.05h-12.3q-12.46,0-12.45,8.25a7.23,7.23,0,0,0,2.85,6c1.89,1.5,4.5,2.25,7.8,2.25A15.79,15.79,0,0,0,737.16,390.59Z" transform="translate(-379 -265)"/><path class="c" d="M873.21,328.34l-33.6,81.3h-29.4l-33.46-81.3H806L825.5,378l20.56-49.65Z" transform="translate(-379 -265)"/><path class="c" d="M878.15,360.37a54.74,54.74,0,0,1,17.63-21.9q11.78-8.78,28.72-8.78,13.05,0,19.88,6.6t6.82,18.9a63.24,63.24,0,0,1-5.85,25.88A54.71,54.71,0,0,1,927.73,403Q916,411.75,899,411.74q-13.05,0-19.87-6.6t-6.83-18.9A63.19,63.19,0,0,1,878.15,360.37Zm35-16.43a127.71,127.71,0,0,0-11.17,27.23q-4.73,16-4.73,29.17a21.66,21.66,0,0,0,.68,6.53c.45,1.25,1.17,1.87,2.17,1.87q4.05,0,10.43-10.87a122.34,122.34,0,0,0,11.1-26.63,104,104,0,0,0,4.72-29.85q0-5.25-.67-7c-.45-1.15-1.08-1.73-1.88-1.73Q919.55,332.69,913.1,343.94Z" transform="translate(-379 -265)"/><path class="c" d="M1038.8,385.34h2.85l-3.75,11q-5.4,15.45-21.45,15.45-9.6,0-13.5-5.4a13.74,13.74,0,0,1-2.55-8.4,39.48,39.48,0,0,1,2.4-12l12.9-38.85a18.4,18.4,0,0,0,1.2-5.85q0-2.25-1.5-2.25-3.9,0-10,10.5a192.14,192.14,0,0,0-12.52,26.85,303.11,303.11,0,0,0-10.73,32.85v-.3l-.15.75H955.4l18.3-65.1a17.37,17.37,0,0,0,.9-4.35q0-1.95-2.25-1.95a5.84,5.84,0,0,0-4.35,2.1q-1.95,2.1-3.9,7.35l-3.45,9.15H957.8l4.2-11.7q2.85-8,7.8-11.7t12.75-3.75q8.4,0,12.08,3.9a14.26,14.26,0,0,1,3.67,10.2,27.24,27.24,0,0,1-.9,6.9l-4,15.6q5.85-14.7,11.18-22.57t10.87-11a25.56,25.56,0,0,1,12.6-3.08q14.7,0,14.7,12.45a35.16,35.16,0,0,1-2,10.2l-14.85,44.55a15.08,15.08,0,0,0-.75,3.6q0,2.7,2.55,2.7,4.35,0,8-9.45Z" transform="translate(-379 -265)"/></svg></li>
    <li><a href="#!">Home</a></li>
    <li><a class="{{ Route::currentRouteName() === 'television.seasonal.show' ? 'is-active' : '' }}" href="{{ route('television.seasonal.show') }}">Seasonal</a></li>
    <li><a href="#!">Calendar</a></li>
    @if (Auth::check() === false)
      <li><a href="{{ route('login') }}" class="{{ Route::currentRouteName() === 'login' ? 'is-active' : '' }}">Login</a></li>
      <li><a href="{{ route('register') }}" class="{{ Route::currentRouteName() === 'register' ? 'is-active' : '' }}">Sign Up</a></li>
    @endif
  </ul>
  @if (Auth::check())
  <div class="nav__user">
    <div>
      <a href="#!" class="nav__icon icon--inbox">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18"><title>Messages</title><path d="M8,11v2.4l12,5.8,12-5.8V11Zm0,4.5V29H32V15.5L20,21.2Z" transform="translate(-8 -11)"/></svg>
      </a>
      <a href="#!" class="nav__icon icon--notifications">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.5 81.5"><title>Notifications</title><path class="a" d="M80.3,71.52l-5.55-8.31V40.75A24.75,24.75,0,0,0,53.5,16.25v-3.5a3.5,3.5,0,0,0-7,0v3.5a24.75,24.75,0,0,0-21.25,24.5V63.21L19.7,71.52a3.56,3.56,0,0,0,0,3.49,3.41,3.41,0,0,0,3,1.74H77.33a3.39,3.39,0,0,0,3-1.74A3.56,3.56,0,0,0,80.3,71.52ZM50,90.75c4.4,0,8.18-4.13,9.8-10H40.2C41.82,86.62,45.6,90.75,50,90.75Z" transform="translate(-19.25 -9.25)"/></svg>
      </a>
    </div>
    <a href="#!">
      <img src="/images/avatar.png">
    </a>
  </div>
  @endif
</nav>
