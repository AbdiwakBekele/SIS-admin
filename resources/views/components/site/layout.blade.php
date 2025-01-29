<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('config.general.meta_description') }}">
    <meta name="keywords" content="{{ config('config.general.meta_keywords') }}">
    <meta name="author" content="{{ config('config.general.meta_author') }}">
    <title>{{ config('config.general.app_name', config('app.name', 'ScriptMint')) }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ config('config.assets.favicon') }}" type="image/png">

    <!-- Favicons -->
    <link href="{{ config('config.assets.favicon') }}" rel="icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/site/vendor/aos/aos.css" rel="stylesheet">
    <link href="/site/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/site/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/site/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/site/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/site/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/site/css/style.css" rel="stylesheet">
    @vite(['resources/js/site.js'], 'site/build')
    @livewireStyles
    <!-- =======================================================
  * Template Name: FlexStart - v1.9.0
  * Template URL: https://bootstrapmade.com/flexstart-bootstrap-startup-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="theme-{{ config('config.system.theme') }}">

    @livewire('cookie-consent')

    <x-site.header />

    {{ $slot }}

    <x-site.footer />

    <!-- Vendor JS Files -->
    <script src="/site/vendor/purecounter/purecounter.js"></script>
    <script src="/site/vendor/aos/aos.js"></script>
    <script src="/site/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/site/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/site/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/site/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="/site/js/main.js"></script>
    @livewireScriptConfig
</body>

</html>
