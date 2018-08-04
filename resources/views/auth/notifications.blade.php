@extends('layouts.main')
@section('content')
  <div class="auth" style="background-image: radial-gradient(circle at 20% 50%, rgba(11.76%, 18.43%, 23.53%, 0.98) 0%, rgba(11.76%, 18.43%, 23.53%, 0.88) 100%), url('https://image.tmdb.org/t/p/original{{ $banner }}'); background-size: cover;">
    <div class="card">
      <div class="card-content">
        <div class="auth__head">
          @include('components.logo', ['class' => 'head__logo'])
          <span class="head__title">Notification Settings</span>
        </div>
        <div class="auth__notifications--text">
          @include('flash::message')
          <p><strong class="text-success">Your account has been created!</strong> To unlock all features, please visit the verification link we've sent you.</p>
          <p>You can review your notification settings now, or change them in your profile settings later:</p>
        </div>
        <div class="auth__notifications--options card-meta">>>>>>>> develop
          <form method="POST" action="{{ route('users.settings.notifications') }}">
            @csrf
            <div class="row">
              <div class="column">
                <div class="field">
                  <input type="checkbox" class="checkbox" id="notify_messages" name="notify_messages" {{ $user->notify_messages ? 'checked' : '' }}>
                  <label for="notify_messages">Notify me when I receive messages from other users</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="column">
                <div class="field">
                  <input type="checkbox" class="checkbox" id="notify_shows" name="notify_shows" {{ $user->notify_shows ? 'checked' : '' }}>
                  <label for="notify_shows">Notify me when new seasons of shows I'm following start airing</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="column">
                <div class="field">
                  <input type="checkbox" class="checkbox" id="notify_features" name="notify_features" {{ $user->notify_features ? 'checked' : '' }}>
                  <label for="notify_features">Subscribe to the newsletter (don't worry, we'll only send you information about new features, no spam!)</label>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="button-group">
              <button type="submit" class="button is-primary">Save</button>
              <a href="/" class="button is-outline">Go To Homepage</a>
            </div>
          </form>
        </div>
      </div>
      <div class="card-action">
        <div class="card-meta">
          <span>Keep in mind that you will only receive email notifications once you've verified your email address.</span>
        </div>
      </div>
    </div>
  </div>
@endsection
