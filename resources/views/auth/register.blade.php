@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title">Sign Up</span>
        </div>
        <div class="card-meta">
          <p>Track your progress, find new content to watch and share it with your friends. Signing up for an account is <strong>free</strong> and easy.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
          @csrf
          <div class="field">
            <label class="text-label" for="name">User Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" required autofocus>
            @include('components.form-errors', ['field' => 'name'])
          </div>

          @include('auth.partials.inputs')

          <div class="card-meta">
            <p>By clicking the "Submit" button below, I certify that I have read and agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
          </div>

          <div class="flex-group">
            <button type="submit" class="button is-primary">Submit</button>
          </div>

        </form>
      </div>
      <div class="card-action">
        <div class="card-meta">
          <span>If you already have an account, <a href="{{ route('login') }}">log in now</a>.</span>
        </div>
      </div>
    </div>
  </div>
@endsection
