@php
  // Logged-in client (if any). The controller passes it as a one-row array.
  $c = $client ? $client[0] : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <title>Cally World</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
    section { scroll-margin-top: 72px; }
    .product-img { height: 220px; object-fit: cover; }
    .product-img-empty { height: 220px; }
    #searchbox { position: relative; }
    #searchbox .dropdown-menu { min-width: 260px; max-height: 360px; overflow-y: auto; padding: .5rem 1rem; }
    #searchbox .dropdown-menu h3 { font-size: .75rem; text-transform: uppercase; color: #6c757d; margin: .5rem 0 .25rem; }
    #searchbox .dropdown-menu p { margin: 0 0 .25rem; }
    .hero-img { max-height: 320px; object-fit: cover; }
    .nav-pills .nav-link { color: #212529; }
    .nav-pills .nav-link.active { background-color: #212529; }
  </style>
</head>
<body>

{{-- Simple modals for "sign in first" and info messages --}}
<div class="modal fade" id="modalAlert" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Sign in required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Please sign in first.</div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        @if($user)
          <li class="nav-item"><a class="nav-link" href="{{ route('user') }}">My Shop</a></li>
        @endif
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#accountPanel">Account</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#favorites">Wishlist</a></li>
        <li class="nav-item">
          <a class="nav-link" @if(session()->has('clientemail')) href="{{ route('cart') }}" @else href="#" onclick="showAlert(); return false;" @endif>Cart</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('explorevideos') }}">Watch</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('register-user') }}">Sell / Sign in</a></li>
        <li class="nav-item ms-lg-2 my-2 my-lg-0">
          <input class="form-control form-control-sm" type="search" id="search_query" placeholder="Search" autocomplete="off">
          <div id="searchbox"></div>
        </li>
      </ul>
    </div>
  </div>
</nav>

{{-- Account panel --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="accountPanel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">My Account</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    @if($c)
      <input type="hidden" id="clientId" value="{{ $c->id }}">
      @if($c->status == 'verified')
        <div class="alert alert-success alertsProf" style="display:none"></div>
        <div class="mb-3"><label class="form-label">Name</label>
          <input id="updatename" class="form-control" type="text" value="{{ $c->name }}"></div>
        <div class="mb-3"><label class="form-label">Phone</label>
          <input id="updatephone" class="form-control" type="text" value="{{ $c->phone }}"></div>
        <div class="mb-3"><label class="form-label">Address</label>
          <input id="updateaddress" class="form-control" type="text" value="{{ $c->address }}"></div>
        <div class="mb-3"><label class="form-label">Card number</label>
          <input id="cardNumber" class="form-control" type="text" maxlength="19" value="{{ $c->card_number }}"></div>
        <button type="button" class="btn btn-primary w-100" onclick="updateClient({{ $c->id }})">Save changes</button>

        <hr class="my-4">
        <h6>Change password</h6>
        <input id="oldpassword" class="form-control mb-2" type="password" placeholder="Old password">
        <input id="newpassword" class="form-control mb-2" type="password" placeholder="New password">
        <input id="newpassword_confirmation" class="form-control mb-3" type="password" placeholder="Confirm new password">
        <button type="button" class="btn btn-outline-secondary w-100" onclick="changepassword({{ $c->id }})">Change password</button>

        <a href="{{ route('logout') }}" class="btn btn-link text-danger w-100 mt-4">Sign out</a>
      @else
        <div class="alert alert-warning">Your email isn't verified yet. Check your inbox for the verification link.</div>
      @endif
    @else
      <div class="alert alert-info">Sign up below to fully access the site.</div>
      <a href="#join-section" class="btn btn-primary w-100" data-bs-dismiss="offcanvas">Sign up / Log in</a>
    @endif

    @if($user)
      @foreach($user as $u)
        <a href="{{ route('user') }}" class="d-flex align-items-center gap-2 mt-4 text-decoration-none">
          <img src="{{ asset('profiles/'.$u->photo) }}" class="rounded-circle" width="48" height="48" style="object-fit:cover" alt="">
          <span>{{ $u->name }} (supplier)</span>
        </a>
      @endforeach
    @endif
  </div>
</div>

{{-- Wishlist --}}
@php
  $favorites = $c
    ? DB::select("select favorites.id, favorites.productId, product.name, product.photo from favorites
        left join product on favorites.productId = product.id where favorites.clientId = ?", [$c->id])
    : [];
@endphp
<div class="modal fade" id="favorites" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Wishlist</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        @if(!$c)
          <p class="text-muted mb-0">Sign in to see your wishlist.</p>
        @elseif(count($favorites) == 0)
          <p class="text-muted mb-0">Your wishlist is empty.</p>
        @else
          <div class="row row-cols-2 row-cols-md-3 g-3">
            @foreach($favorites as $f)
              <div class="col">
                <a href="/product/{{ $f->productId }}" class="card h-100 text-decoration-none">
                  <img src="{{ asset('img/'.$f->photo) }}" class="card-img-top" style="height:140px;object-fit:cover" alt="">
                  <div class="card-body p-2 small text-dark">{{ $f->name }}</div>
                </a>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@if(session()->has('alert'))
  <div class="container mt-3"><div class="alert alert-danger alert-dismissible">
    {{ session()->get('alert') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div></div>
@endif

{{-- Hero --}}
<section class="bg-white border-bottom py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <h1 class="display-5 fw-semibold">Calligraphy</h1>
        <p class="lead text-muted">“Calligraphy is an art form that uses ink and a brush to express the very souls of words on paper.”
          <br><small>— Kaoru Kakogawa</small></p>
        <a href="#products" class="btn btn-dark btn-lg mt-2">Browse products</a>
      </div>
      <div class="col-lg-6">
        <img src="{{ asset('css/home-gif.gif') }}" class="img-fluid rounded w-100 hero-img" alt="">
      </div>
    </div>
  </div>
</section>

{{-- Benefits --}}
<section class="py-5">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <i class="bi bi-truck fs-2"></i>
        <h5 class="mt-2">Fast delivery</h5>
        <p class="text-muted">Your orders arrive within a few days, all over Lebanon.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-grid fs-2"></i>
        <h5 class="mt-2">Variety of products</h5>
        <p class="text-muted">Many suppliers share their work, giving you plenty to choose from.</p>
      </div>
      <div class="col-md-4">
        <i class="bi bi-graph-up-arrow fs-2"></i>
        <h5 class="mt-2">Reach more buyers</h5>
        <p class="text-muted">Suppliers get their art in front of many clients, boosting sales.</p>
      </div>
    </div>
  </div>
</section>

{{-- Top products / Top sellers --}}
@php
  $topProducts = DB::table('product')
    ->select('product.id', 'product.name', 'product.photo', 'product.description', 'product.price', 'product.promotion',
             DB::raw('SUM(productsrate.rate) / COUNT(productsrate.productId) as rating'))
    ->join('productsrate', 'product.id', '=', 'productsrate.productId')
    ->groupBy('product.id', 'product.name', 'product.photo', 'product.description', 'product.price', 'product.promotion')
    ->havingRaw('SUM(productsrate.rate) / COUNT(productsrate.productId) > 3')
    ->whereRaw('product.soldno + product.sales_after_promotion > 49')
    ->orderBy('soldno', 'desc')->orderBy('rating', 'desc')
    ->take(4)->get();

  $topSellers = DB::table('users')
    ->select('users.id', 'users.name', 'users.photo', 'users.aboutme', 'users.nbOfSales',
             DB::raw('SUM(rate.rate) / COUNT(rate.userId) as rating'))
    ->join('rate', 'users.id', '=', 'rate.userId')
    ->groupBy('users.id', 'users.name', 'users.photo', 'users.aboutme', 'users.nbOfSales')
    ->havingRaw('SUM(rate.rate) / COUNT(rate.userId) > 3')
    ->where('nbOfSales', '>', '30')
    ->orderBy('nbOfSales', 'desc')->orderBy('rating', 'desc')
    ->take(4)->get();
@endphp
<section class="py-5 bg-white border-top border-bottom" id="best">
  <div class="container">
    <ul class="nav nav-pills mb-4" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#bestproducts" type="button">Top products</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#bestsalers" type="button">Top sellers</button></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="bestproducts">
        <p class="text-muted small">Products with 50+ sales and a rating above 3 compete for this list.</p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
          @forelse($topProducts as $pro)
            @php $newprice = $pro->price - (($pro->promotion / 100) * $pro->price); @endphp
            <div class="col">
              <div class="card h-100">
                <img src="{{ asset('img/'.$pro->photo) }}" class="card-img-top product-img" alt="">
                <div class="card-body">
                  @if($pro->promotion > 0)<span class="badge text-bg-danger mb-2">-{{ $pro->promotion }}%</span>@endif
                  <h6 class="card-title">{{ $pro->name }}</h6>
                  <p class="card-text small text-muted">{{ $pro->description }}</p>
                  @if($pro->promotion > 0)
                    <span class="text-muted text-decoration-line-through me-1">${{ $pro->price }}</span><strong>${{ $newprice }}</strong>
                  @else
                    <strong>${{ $pro->price }}</strong>
                  @endif
                </div>
                <div class="card-footer bg-white border-0 pt-0"><a href="/product/{{ $pro->id }}" class="btn btn-sm btn-outline-dark">See more</a></div>
              </div>
            </div>
          @empty
            <div class="col-12 text-muted">No top products yet.</div>
          @endforelse
        </div>
      </div>
      <div class="tab-pane fade" id="bestsalers">
        <p class="text-muted small">Suppliers with 50+ sales and a rating above 3 compete for this list.</p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
          @forelse($topSellers as $s)
            <div class="col">
              <div class="card h-100 text-center">
                <div class="card-body">
                  <img src="{{ asset('profiles/'.$s->photo) }}" class="rounded-circle mb-3" width="96" height="96" style="object-fit:cover" alt="">
                  <h6 class="card-title mb-1">{{ $s->name }}</h6>
                  <div class="small text-warning mb-2"><i class="bi bi-star-fill"></i> {{ round($s->rating, 1) }}</div>
                  <p class="card-text small text-muted">{{ $s->aboutme }}</p>
                  <p class="small mb-0">{{ $s->nbOfSales }} sold products</p>
                </div>
                <div class="card-footer bg-white border-0 pt-0"><a href="/userprofile/{{ $s->id }}" class="btn btn-sm btn-outline-dark">View profile</a></div>
              </div>
            </div>
          @empty
            <div class="col-12 text-muted">No top sellers yet.</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Products --}}
<section class="py-5" id="products">
  <div class="container">
    <h2 class="h3 mb-3">Products</h2>
    <div class="d-flex flex-wrap gap-2 mb-4" id="categoryFilter">
      <button type="button" class="btn btn-sm btn-dark" data-cat="all">All</button>
      @foreach($categories as $cat)
        <button type="button" class="btn btn-sm btn-outline-dark" data-cat="{{ $cat->id }}">{{ $cat->name }}</button>
      @endforeach
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
      @forelse($products as $p)
        @php
          $newprice = $p->price - (($p->promotion / 100) * $p->price);
          $isFav = $c ? DB::table('favorites')->where('productId', $p->id)->where('clientId', $c->id)->exists() : false;
        @endphp
        <div class="col product-col" data-cat="{{ $p->categoryId }}">
          <div class="card h-100">
            @if($p->photo && file_exists(public_path('img/'.$p->photo)))
              <a href="/product/{{ $p->id }}"><img src="{{ asset('img/'.$p->photo) }}" class="card-img-top product-img" alt=""></a>
            @else
              <div class="product-img-empty bg-light d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image fs-1"></i></div>
            @endif
            <div class="card-body">
              @if($p->promotion > 0)<span class="badge text-bg-danger mb-2">-{{ $p->promotion }}%</span>@endif
              <h6 class="card-title"><a href="/product/{{ $p->id }}" class="text-dark text-decoration-none">{{ $p->name }}</a></h6>
              <p class="small text-muted mb-1">Size: {{ $p->height }} x {{ $p->width }}</p>
              @if($p->promotion > 0)
                <span class="text-muted text-decoration-line-through me-1">${{ $p->price }}</span><strong>${{ $newprice }}</strong>
              @else
                <strong>${{ $p->price }}</strong>
              @endif
              <input type="hidden" id="prodPrice_{{ $p->id }}" value="{{ $newprice }}">
            </div>
            <div class="card-footer bg-white border-0 pt-0 d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm btn-dark" onclick="addToCart({{ $p->id }})"><i class="bi bi-cart-plus"></i> Add to cart</button>
              <i class="bi bi-check-circle-fill text-success" id="added_{{ $p->id }}" style="display:none"></i>
              @if($c)
                <button type="button" class="btn btn-sm btn-link ms-auto p-0 fs-5" onclick="addFav({{ $p->id }})" id="fav{{ $p->id }}"
                        style="color: {{ $isFav ? 'red' : '#bbb' }}" title="Add to wishlist"><i class="bi bi-heart-fill"></i></button>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-muted">No products available yet.</div>
      @endforelse
      <div class="col-12 text-muted" id="noCategoryProducts" style="display:none">No products in this category.</div>
    </div>
  </div>
</section>

{{-- Join / client sign up --}}
<section class="py-5 bg-white border-top" id="join-section">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            @if(session()->has('clientregistered'))
              <div class="alert alert-success">{{ session()->get('clientregistered') }}</div>
            @endif
            @if(!$c)
              <h4 id="caption" class="mb-3">Sign up</h4>
              <form action="{{ route('signup') }}" method="POST">
                @csrf
                <div id="nameTD" class="mb-2"><input type="text" name="name" placeholder="Your name" class="form-control" id="name"></div>
                <div class="mb-2"><input type="email" name="email" placeholder="Email address" class="form-control" id="email"></div>
                <div class="mb-2"><input type="password" name="password" placeholder="Password" class="form-control" id="password"></div>
                <div id="confirmpassTD" class="mb-2"><input type="password" name="password_confirmation" placeholder="Confirm password" class="form-control" id="password_confirmation"></div>
                <div id="phoneTD" class="mb-2"><input type="text" name="phone" placeholder="Phone number" class="form-control" id="phone"></div>
                <div id="addressTD" class="mb-3"><input type="text" name="address" placeholder="Address" class="form-control" id="address"></div>
                <div class="alert alert-info py-2" id="clientalert" style="display:none"></div>
                <button type="submit" id="btnsignup" class="btn btn-dark w-100">Sign up</button>
                <button type="button" id="btnLogin" class="btn btn-dark w-100" style="display:none">Log in</button>
                <button type="button" id="btnlogin" class="btn btn-link w-100 mt-2">Already have an account? Log in</button>
                <button type="button" id="btnregister" class="btn btn-link w-100 mt-2" style="display:none">Create a new account</button>
              </form>
            @else
              <h4 class="mb-2">Welcome, {{ $c->name }}</h4>
              <p class="text-muted mb-0">You're signed in. Manage your details from the Account menu.</p>
            @endif
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card h-100 border-0 bg-light">
          <div class="card-body p-4 d-flex flex-column justify-content-center">
            <h4>Are you an artist?</h4>
            <p class="text-muted">Join other suppliers and share your creative work with our clients.</p>
            <a href="{{ route('register-user') }}" class="btn btn-outline-dark align-self-start">Create a supplier account</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Contact --}}
<section class="py-5" id="contact">
  <div class="container" style="max-width:720px">
    <h2 class="h3 mb-3">Contact us</h2>
    <div class="alert alert-success" id="mailSuccess" style="display:none">Your message was sent, thank you!</div>
    <div class="row g-3">
      <div class="col-md-6"><input type="text" class="form-control" id="contactName" placeholder="Name"></div>
      <div class="col-md-6"><input type="email" class="form-control" id="contactEmail" placeholder="Email"></div>
      <div class="col-12"><input type="text" class="form-control" id="contactSubject" placeholder="Subject"></div>
      <div class="col-12"><textarea class="form-control" id="contactMessage" rows="4" placeholder="Message"></textarea></div>
      <div class="col-12"><button type="button" class="btn btn-dark" onclick="contact()">Send message</button></div>
    </div>
  </div>
</section>

<footer class="py-4 border-top bg-white text-center text-muted small">
  &copy; {{ date('Y') }} CallyWorld — Developed by Bilal Kaderi
</footer>

<script>
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') } });

  function showAlert() {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAlert')).show();
  }

  $(function () {
    // Live search
    $("#search_query").on("keyup", function () {
      $.get('/search', { search_query: $(this).val() }, function (data) {
        $("#searchbox").html(data.results);
      });
    });
    $(document).on('click', function (e) {
      if (!$(e.target).closest('#search_query, #searchbox').length) $("#searchbox").empty();
    });

    // Category filter
    $("#categoryFilter").on("click", "button", function () {
      var cat = String($(this).data('cat'));
      $("#categoryFilter button").removeClass('btn-dark').addClass('btn-outline-dark');
      $(this).removeClass('btn-outline-dark').addClass('btn-dark');
      var shown = 0;
      $(".product-col").each(function () {
        var match = cat === 'all' || String($(this).data('cat')) === cat;
        $(this).toggle(match);
        if (match) shown++;
      });
      $("#noCategoryProducts").toggle(shown === 0 && $(".product-col").length > 0);
    });

    // Format card number in groups of 4
    $('#cardNumber').on('input', function () {
      $(this).val($(this).val().replace(/\s/g, '').replace(/(\d{4})(?=\d)/g, '$1 '));
    });

    // Switch the join form between sign up and log in
    function toggleJoinForm(toLogin) {
      $("#nameTD, #confirmpassTD, #phoneTD, #addressTD, #btnsignup").toggle(!toLogin);
      $("#btnLogin, #btnregister").toggle(toLogin);
      $("#btnlogin").toggle(!toLogin);
      $("#caption").text(toLogin ? 'Log in' : 'Sign up');
    }
    $("#btnlogin").click(function () { toggleJoinForm(true); });
    $("#btnregister").click(function () { toggleJoinForm(false); });

    $("#btnLogin").click(function (e) {
      e.preventDefault();
      var email = $("#email").val().trim(), password = $("#password").val().trim();
      if (!email || !password) {
        $("#clientalert").text('Enter your email and password.').fadeIn();
        return;
      }
      $.post("{{ route('login.client') }}", { email: email, password: password }, function (output) {
        $("#clientalert").text(output.success).fadeIn();
        if (output.success == 'Logged in successfully.') location.reload();
      });
    });
  });

  function addFav(id) {
    $.post("{{ route('addFav') }}", { id: id, clientId: $("#clientId").val() }, function (output) {
      $("#fav" + id).css("color", output.color);
    });
  }

  function addToCart(id) {
    $.post("{{ route('addToCart') }}", { id: id, price: $("#prodPrice_" + id).val() }, function (output) {
      if (output.success == 'Item added!') {
        $("#added_" + id).fadeOut(100).fadeIn();
      } else {
        showAlert();
      }
    });
  }

  function updateClient(id) {
    $.post("{{ route('updateClient') }}", {
      id: id, name: $("#updatename").val(), phone: $("#updatephone").val(),
      address: $("#updateaddress").val(), cardNumber: $("#cardNumber").val()
    }, function (output) {
      $(".alertsProf").text(output.success).fadeIn();
    });
  }

  function changepassword(id) {
    if ($("#newpassword").val().trim() != $("#newpassword_confirmation").val().trim()) {
      $(".alertsProf").text('Password confirmation is not correct!').fadeIn();
      return;
    }
    $.post("{{ route('changeClientPassword') }}", {
      id: id, oldpassword: $("#oldpassword").val(), newpassword: $("#newpassword").val()
    }, function (output) {
      $(".alertsProf").text(output.success).fadeIn();
    });
  }

  function contact() {
    $.post("{{ route('contact') }}", {
      name: $('#contactName').val(), email: $('#contactEmail').val(),
      subject: $('#contactSubject').val(), message: $('#contactMessage').val()
    }, function () {
      $("#mailSuccess").fadeIn();
      $('#contactName, #contactEmail, #contactSubject, #contactMessage').val('');
    });
  }
</script>
</body>
</html>
