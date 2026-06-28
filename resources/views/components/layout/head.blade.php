@props([
    'title' => 'GuruHub — Platform Pembelajaran Digital Indonesia',
    'description' => null,
    'image' => null,
    'ogType' => 'website',
])

@php
    $siteName = 'GuruHub';
    $metaTitle = $title;
    $metaDescription = $description ?: 'Platform pembelajaran modern untuk siswa, pengajar, dan institusi di Indonesia. Kursus terkurasi, kelas live, quiz, dan sertifikat terverifikasi.';
    $logoUrl = url(asset('assets/logo-app/guru_hub_logo.jpeg'));
    $metaImage = $image ? (str_starts_with($image, 'http') ? $image : url($image)) : $logoUrl;
    $canonicalUrl = url()->current();
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $metaDescription }}">
<meta name="author" content="{{ $siteName }}">
<meta name="theme-color" content="#050b24">
<meta name="application-name" content="{{ $siteName }}">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

<title>{{ $metaTitle }}</title>
<link rel="canonical" href="{{ $canonicalUrl }}">

<link rel="icon" type="image/jpeg" href="{{ $logoUrl }}" sizes="32x32">
<link rel="shortcut icon" href="{{ $logoUrl }}">
<link rel="apple-touch-icon" href="{{ $logoUrl }}">

<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:alt" content="Logo {{ $siteName }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Instrument+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
