@php
    /** @var array<string, mixed> $seo */
    $seo = $page['props']['seo'] ?? [];
    $fullTitle = (string) ($seo['fullTitle'] ?? config('app.name', 'Mühlmattenschule'));
    $siteName = (string) ($seo['siteName'] ?? 'Mühlmattenschule – Grundschule Freiburg-Hochdorf');
    $description = (string) ($seo['description'] ?? 'Die Grundschule für den Freiburger Stadtteil Hochdorf mit Benzhausen – ein Ort zum Lernen, Wachsen und Entdecken.');
    $canonicalUrl = (string) ($seo['canonicalUrl'] ?? url()->current());
    $robots = (string) ($seo['robots'] ?? 'index, follow');
    $locale = (string) ($seo['locale'] ?? 'de_DE');
    $ogType = (string) ($seo['ogType'] ?? 'website');
    $ogImage = (string) ($seo['ogImage'] ?? url('/images/school/about-main.jpg'));
    $ogImageAlt = (string) ($seo['ogImageAlt'] ?? $siteName);
    $twitterCard = (string) ($seo['twitterCard'] ?? 'summary_large_image');
    $publishedTime = isset($seo['publishedTime']) && is_string($seo['publishedTime']) ? $seo['publishedTime'] : null;
    $modifiedTime = isset($seo['modifiedTime']) && is_string($seo['modifiedTime']) ? $seo['modifiedTime'] : null;
    $section = isset($seo['section']) && is_string($seo['section']) ? $seo['section'] : null;
    $prevUrl = isset($seo['prevUrl']) && is_string($seo['prevUrl']) ? $seo['prevUrl'] : null;
    $nextUrl = isset($seo['nextUrl']) && is_string($seo['nextUrl']) ? $seo['nextUrl'] : null;
    $jsonLd = isset($seo['jsonLd']) && is_string($seo['jsonLd']) ? $seo['jsonLd'] : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#029786">
        <meta name="format-detection" content="telephone=no">
        <link rel="icon" href="/images/logo.png" type="image/png">
        <link rel="apple-touch-icon" href="/images/logo.png">
        <link rel="manifest" href="/site.webmanifest">

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        <x-inertia::head>
            <title data-inertia>{{ $fullTitle }}</title>
            <meta name="description" content="{{ $description }}" data-inertia="description">
            <meta name="robots" content="{{ $robots }}" data-inertia="robots">
            <link rel="canonical" href="{{ $canonicalUrl }}" data-inertia="canonical">
            @if ($prevUrl)
                <link rel="prev" href="{{ $prevUrl }}" data-inertia="prev">
            @endif
            @if ($nextUrl)
                <link rel="next" href="{{ $nextUrl }}" data-inertia="next">
            @endif

            <meta property="og:locale" content="{{ $locale }}" data-inertia="og:locale">
            <meta property="og:site_name" content="{{ $siteName }}" data-inertia="og:site_name">
            <meta property="og:type" content="{{ $ogType }}" data-inertia="og:type">
            <meta property="og:title" content="{{ $fullTitle }}" data-inertia="og:title">
            <meta property="og:description" content="{{ $description }}" data-inertia="og:description">
            <meta property="og:url" content="{{ $canonicalUrl }}" data-inertia="og:url">
            <meta property="og:image" content="{{ $ogImage }}" data-inertia="og:image">
            <meta property="og:image:alt" content="{{ $ogImageAlt }}" data-inertia="og:image:alt">

            @if ($ogType === 'article' && $publishedTime)
                <meta property="article:published_time" content="{{ $publishedTime }}" data-inertia="article:published_time">
            @endif
            @if ($ogType === 'article' && $modifiedTime)
                <meta property="article:modified_time" content="{{ $modifiedTime }}" data-inertia="article:modified_time">
            @endif
            @if ($ogType === 'article' && $section)
                <meta property="article:section" content="{{ $section }}" data-inertia="article:section">
            @endif

            <meta name="twitter:card" content="{{ $twitterCard }}" data-inertia="twitter:card">
            <meta name="twitter:title" content="{{ $fullTitle }}" data-inertia="twitter:title">
            <meta name="twitter:description" content="{{ $description }}" data-inertia="twitter:description">
            <meta name="twitter:image" content="{{ $ogImage }}" data-inertia="twitter:image">
            <meta name="twitter:image:alt" content="{{ $ogImageAlt }}" data-inertia="twitter:image:alt">

            @if ($jsonLd)
                <script type="application/ld+json" data-inertia="json-ld">{!! $jsonLd !!}</script>
            @endif
        </x-inertia::head>
    </head>
    <body class="min-h-screen min-h-dvh overflow-x-clip font-sans">
        <x-inertia::app />
    </body>
</html>
