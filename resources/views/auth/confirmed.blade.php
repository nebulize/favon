@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title">Registration Complete</span>
        </div>
        <div class="auth__notifications--text card-text">
          <p>Your email has been successfully verified.</p>
          <a href="/" class="button is-primary">Go To Homepage</a>
        </div>
      </div>
    </div>
  </div>
@endsection
