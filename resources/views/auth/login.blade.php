<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in · CallyWorld</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @include('layouts.theme', ['area' => 'shop'])
</head>
<body>

<nav class="navbar bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
    </a>
    <a class="nav-link text-secondary" href="{{ route('home') }}">Back to shop</a>
  </div>
</nav>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">

      @if($errors->any())
        <div class="alert alert-danger">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif
      @foreach(['notverified' => 'danger', 'notvalid' => 'danger', 'registered' => 'success', 'verified' => 'success'] as $key => $type)
        @if(session()->has($key))
          <div class="alert alert-{{ $type }}">{{ session()->get($key) }}</div>
        @endif
      @endforeach

      <div class="card p-4" id="loginPanel">
        <h1 class="h4 mb-1">Sign in</h1>
        <p class="text-muted mb-4">For suppliers and administrators. Buyers sign in from the shop.</p>
        <form action="{{ route('login.custom') }}" method="post">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}" autocomplete="username">
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" autocomplete="current-password">
          </div>
          <button type="submit" id="btnlogin" name="btnlogin" class="btn btn-dark w-100">Sign in</button>
        </form>
        <p class="text-center text-muted small mt-4 mb-0">
          New supplier? <button type="button" id="btnshow" class="btn btn-link btn-sm p-0 align-baseline">Create an account</button>
        </p>
      </div>

      <div id="registerPanel" style="display:none">
        @include('auth.registration')
      </div>

    </div>
  </div>
</main>

<script>
  $(function () {
    $("#btnshow").click(function () {
      $("#loginPanel").hide();
      $("#registerPanel").fadeIn();
    });
    $("#btnhide").click(function () {
      $("#registerPanel").hide();
      $("#loginPanel").fadeIn();
    });
  });
</script>
</body>
</html>
