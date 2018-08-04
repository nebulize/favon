@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title">Reset Password</span>
        </div>
        @if (session('status'))
          <div class="alert is-success">
            {{ session('status') }}
          </div>
        @endif
        <p>To reset your password, enter your email address below.</p>
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="field">
            <label class="text-label" for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus>
            @include('components.form-errors', ['field' => 'email'])
          </div>
          <div class="flex-group">
            <button type="submit" class="button is-primary">Send Password Reset Link</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
