{{-- Input fields for email and password used by both the register and reset password pages --}}
<div class="field">
  <label class="text-label" for="email">Email Address</label>
  <input type="email" id="email" name="email" value="{{ $email or old('email') }}" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus>
  @include('components.form-errors', ['field' => 'email'])
</div>
<div class="field">
  <label class="text-label" for="password">Password</label>
  <input type="password" id="password" name="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
  @include('components.form-errors', ['field' => 'password'])
</div>
<div class="field">
  <label class="text-label" for="password-confirm">Confirm Password</label>
  <input type="password" id="password-confirm" name="password_confirmation" required>
</div>
