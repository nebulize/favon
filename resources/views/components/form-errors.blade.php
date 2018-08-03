@if ($errors->has($field))
  <span class="invalid-feedback">
    <strong>{{ $errors->first($field) }}</strong>
  </span>
@endif
