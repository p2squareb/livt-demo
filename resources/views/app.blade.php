<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="title" content="{{ cache('config.basic')->basic->site_name }}">
        <meta name="author" content="{{ cache('config.basic')->basic->site_name }}">
        <meta name="description" content="{{ cache('config.basic')->basic->site_description }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased dark:bg-gray-900">
        @inertia
    </body>

    @if (env('USE_GOOGLE_ANALYTICS'))
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID', '') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag("config", "{{ env('GOOGLE_ANALYTICS_ID', '') }}");
        </script>
    @endif 

    @if (env('USE_KAKAO_MAP'))
        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey={{ env('KAKAO_JAVASCRIPT_KEY', '') }}"></script>
    @endif
</html>
