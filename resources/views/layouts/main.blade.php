<html lang="en">
@include('layouts.partials.head')
<body>
@include('layouts.partials.navigation')
<main>
  @section('content')
  @show
</main>
@include('layouts.partials.footer')
@section('scripts')
  <script src="{{ asset_path('js/favon.vendor.js') }}"></script>
  <script src="{{ asset_path('js/favon.app.js') }}"></script>
@show
</body>
</html>
