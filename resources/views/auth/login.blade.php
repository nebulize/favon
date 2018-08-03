@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title">Login</span>
        </div>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="field">
            <label class="text-label" for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required autofocus>
            @include('components.form-errors', ['field' => 'email'])
          </div>
          <div class="field">
            <label class="text-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
            @include('components.form-errors', ['field' => 'password'])
          </div>
          <div class="field">
            <input type="checkbox" class="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me</label>
          </div>
          <div class="flex-group">
            <button type="submit" class="button is-primary">Login</button>
            <span><a class="auth__reset" href="{{ route('password.request') }}">Reset password?</a></span>
          </div>
        </form>

      </div>
      <div class="card-action">
        <div class="card-meta">
          <span>If you haven't already created an account, <a href="{{ route('register') }}">sign up now</a>.</span>
        </div>
      </div>
    </div>
  </div>
@endsection
