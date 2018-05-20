<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="@yield ('keywords')">
    <meta name="description" content="@yield ('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Favon @yield ('title')</title>

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="{{ asset_path('css/favon.seasonal.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">

    <!-- Favicon and Apple Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#ff3f7a">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="theme-color" content="#ff3f7a">
    <meta name="apple-mobile-web-app-title" content="Favon">
    <meta name="application-name" content="Favon">

    <!-- Facebook OpenGraph tags -->
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}/" />
    <meta property="og:image" content="/images/favon.png" />

</head>
