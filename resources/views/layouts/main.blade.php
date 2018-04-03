<html>
@include('layouts.partials.head')
<body>
@include('layouts.partials.navigation')
<main>
    @section('content')
    @show
</main>
@include('layouts.partials.footer')
<script src="{{ asset_path('js/favon.vendor.js') }}"></script>
<script src="{{ asset_path('js/favon.seasonal.js') }}"></script>
</body>
</html>
