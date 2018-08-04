@foreach (session('flash_notification', collect())->toArray() as $message)
  <div class="alert is-{{ $message['level'] }}" role="alert">
    {!! $message['message'] !!}
  </div>
@endforeach

{{ session()->forget('flash_notification') }}
