{{--
  Shared look for every page: Poppins, the theme stylesheet, and the accent
  color for this part of the site. Include it at the END of a page so it wins
  over the page's own older styles:

      @include('layouts.theme', ['area' => 'supplier'])

  Areas: shop (default), supplier, admin, courier.
--}}
@php
  $accents = [
    'shop'     => ['#212529', '#000000', 'rgba(33, 37, 41, .08)'],
    'supplier' => ['#1f6f5c', '#17574a', 'rgba(31, 111, 92, .10)'],
    'admin'    => ['#3b4cb8', '#2f3d96', 'rgba(59, 76, 184, .10)'],
    'courier'  => ['#b45309', '#92400e', 'rgba(180, 83, 9, .10)'],
  ];
  [$cwAccent, $cwAccentHover, $cwAccentSoft] = $accents[$area ?? 'shop'] ?? $accents['shop'];
@endphp
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">
<style>
  :root {
    --cw-accent: {{ $cwAccent }};
    --cw-accent-hover: {{ $cwAccentHover }};
    --cw-accent-soft: {{ $cwAccentSoft }};
  }
</style>
