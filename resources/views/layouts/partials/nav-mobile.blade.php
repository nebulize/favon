{{-- Responsive navigation top bar and toggle button --}}
<div class="nav is-toggle">
  <div class="nav-left">
    <a class="nav-item nav-brand" href="/">
      @if (isset($currentSeason))
        @include('components.logo', ['class' => 'nav__logo is-'.$currentSeason->slug])
      @else
        @include('components.logo', ['class' => 'nav__logo is-primary'])
      @endif
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

{{-- Responsive navigation slideout --}}
<nav class="nav is-responsive">
  <ul>
    <li class="nav-brand">
      @if (isset($currentSeason))
        @include('components.logo', ['class' => 'nav__logo is-'.$currentSeason->slug])
      @else
        @include('components.logo', ['class' => 'nav__logo is-primary'])
      @endif
    </li>
    <li><a href="/">Home</a></li>
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
        <a href="#!" class="nav__icon nav__icon--inbox">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 18"><title>Messages</title><path d="M8,11v2.4l12,5.8,12-5.8V11Zm0,4.5V29H32V15.5L20,21.2Z" transform="translate(-8 -11)"/></svg>
        </a>
        <a href="#!" class="nav__icon nav__icon--notifications">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 61.5 81.5"><title>Notifications</title><path class="a" d="M80.3,71.52l-5.55-8.31V40.75A24.75,24.75,0,0,0,53.5,16.25v-3.5a3.5,3.5,0,0,0-7,0v3.5a24.75,24.75,0,0,0-21.25,24.5V63.21L19.7,71.52a3.56,3.56,0,0,0,0,3.49,3.41,3.41,0,0,0,3,1.74H77.33a3.39,3.39,0,0,0,3-1.74A3.56,3.56,0,0,0,80.3,71.52ZM50,90.75c4.4,0,8.18-4.13,9.8-10H40.2C41.82,86.62,45.6,90.75,50,90.75Z" transform="translate(-19.25 -9.25)"/></svg>
        </a>
      </div>
      <a href="#!">
        <img src="/images/avatar.png">
      </a>
    </div>
  @endif
</nav>
