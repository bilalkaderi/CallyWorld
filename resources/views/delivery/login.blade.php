<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <title>Courier sign in · CallyWorld</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @include('layouts.theme', ['area' => 'courier'])
</head>
<body>

<nav class="navbar bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
      <span class="badge rounded-pill" style="background:var(--cw-accent-soft);color:var(--cw-accent)">Courier</span>
    </a>
    <a class="nav-link text-secondary" href="{{ route('home') }}">Back to shop</a>
  </div>
</nav>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
      <div class="alert alert-danger" id="clientalert" style="display:none">
        @if(session()->has('deliveryman'))
          {{ session()->get('deliveryman') }}
        @endif
      </div>

      <div class="card p-4" id="loginPanel">
        <h1 class="h4 mb-1">Courier sign in</h1>
        <p class="text-muted mb-4">See the orders assigned to you and update their status.</p>
        <form action="" method="post">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" autocomplete="username">
          </div>
          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" autocomplete="current-password">
          </div>
          <button type="button" id="dellogin" name="btnlogin" class="btn btn-dark w-100">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</main>

<script>
  $(function () {
    $("#dellogin").click(function (e) {
      e.preventDefault();
      if ($("#username").val().trim() == "" || $("#password").val().trim() == "") {
        $("#clientalert").text('Fill all information!').fadeIn();
        return;
      }
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      $.ajax({
        url: "{{ route('deliveryLogin') }}",
        method: 'POST',
        data: {username: $("#username").val(), password: $("#password").val()},
        success: function (output) {
          if (output.success == 'Logged in successfully!') {
            location.reload();
          } else {
            $("#clientalert").text(output.success).fadeIn();
          }
        }
      });
    });
  });
</script>
</body>
</html>
