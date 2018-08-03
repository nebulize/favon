@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title text-primary">Reset Password</span>
        </div>
        @if (session('status'))
          <div class="alert is-success">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('password.request') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">

          @include('auth.partials.inputs')

          <div class="flex-group">
            <button type="submit" class="button is-primary">Reset Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
