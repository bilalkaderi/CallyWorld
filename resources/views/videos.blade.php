<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Watch · CallyWorld</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .video-thumb { aspect-ratio: 16 / 10; object-fit: cover; background: #111; }
  </style>
</head>
<body>

<nav class="navbar bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
    </a>
    <a class="nav-link px-2" href="{{ route('home') }}">Shop</a>
  </div>
</nav>

<main class="container py-4 py-lg-5">
  <div class="mb-4">
    <h1 class="h3 mb-1">Watch</h1>
    <p class="text-muted mb-0">Short clips from our calligraphers at work.</p>
  </div>

  @if(count($videos))
    <div class="row g-4">
      @foreach($videos as $v)
        <div class="col-sm-6 col-lg-4">
          <div class="card h-100 overflow-hidden">
            <img src="{{ asset('videos/'.$v->id.'.gif') }}" alt="{{ $v->caption }}" class="video-thumb w-100" loading="lazy">
            <div class="card-body">
              @if($v->caption)<p class="mb-1">{{ $v->caption }}</p>@endif
              <a href="{{ route('userprofile', $v->userId) }}" class="small text-decoration-none">{{ $v->username }}</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="cw-panel text-center py-5">
      <i class="bi bi-camera-video fs-1 text-muted"></i>
      <p class="mt-2 mb-0 text-muted">No videos yet. Suppliers can share clips from their dashboard.</p>
    </div>
  @endif
</main>

<footer class="py-4 text-center">
  &copy; {{ date('Y') }} CallyWorld · Developed by Bilal Kaderi
</footer>

@include('layouts.theme', ['area' => 'shop'])
</body>
</html>
