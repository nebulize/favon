<html>
@include('layouts.partials.head')
<body>
@include('layouts.partials.navigation')
<main>
    @section('content')
    @show
</main>
@include('layouts.partials.footer')
{{--<script src="https://unpkg.com/lumacss/dist/luma.min.js"></script>--}}
{{--<script src="http://localhost:8080/js/favon.app.js"></script>--}}
<script src="/js/favon.vendor.js"></script>
<script src="/js/favon.seasonal.js"></script>
</body>
</html>
