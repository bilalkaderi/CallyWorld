@php
  $clientemail=session()->get('clientemail');
  $clientId = DB::table('client')->where('email', $clientemail)->get()->all();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{csrf_token()}}">
  <title>{{ $product[0]->name ?? 'Product' }} · CallyWorld</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
      $("#placeorder").click(function(e){
        e.preventDefault();
        $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        var productId= $("#productid").val();
        var quantity=$("#quantity").val();
        var price=$("#prodPrice").val();

        $.ajax({
          url:"{{route('ToCart')}}",
          method:'POST',
          data:{productId:productId, quantity:quantity, price:price},
          success: function(output){
            if(output.success=='Sign in first!'){
              $("#alert_fail").text(output.success).fadeIn();
            }
            else{
              $("#placeorder").fadeOut();
              $("#alert_main").text(output.success).fadeIn();
            }
          }
        });
      });

      $("#comment").keyup(function(){
        if($(this).val().length == 0)
          $("#sendComment").fadeOut();
        else
          $("#sendComment").fadeIn();
      });

      $(".ratingrb").click(function(){
        $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        var productid = $("#productId").val();
        var clientid=$("#clientId").val();
        var rating = $(this).val();

        $.ajax({
          url:"{{ route('rateProduct') }}",
          method:'POST',
          data:{productid:productid, clientid:clientid, rating:rating},
          success: function(output){
            $("#ratingvalue").text(output.ratings).fadeIn();
            if(output.success=='Sign in first!'){
              $("#alert_fail").text(output.success).fadeIn();
            }
            else{
              $("#alert_main").text(output.success).fadeIn();
            }
          }
        });
      });
    });

    function addComment(id){
      $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var cId=$("#clientid").val();
      var comment = $("#comment").val();
      $.ajax({
        url:"{{ route('addcomment') }}",
        method:'get',
        data:{id:id, cId:cId, comment:comment},
        success: function(output){
          if(output.success=='Sign in first!'){
            $("#alert_fail").text(output.success).fadeIn();
          }
          else{
            $("#alert_main").text(output.success).fadeIn();
          }
        }
      });
    }

    function deleteComment(id){
      $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      $.ajax({
        url:"{{route('deletecomment')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
          $("#comment_"+id).fadeOut();
        }
      });
    }
  </script>
  <style>
    .product-photo { aspect-ratio: 4 / 3; object-fit: cover; }
    .price-old { text-decoration: line-through; color: var(--cw-muted); }
    /* Star rating: inputs are listed 5..1 and shown right-to-left so hover fills to the left */
    .rating { display: inline-flex; flex-direction: row-reverse; gap: .15rem; }
    .rating input { display: none; }
    .rating label { font-size: 1.6rem; line-height: 1; color: #d1d5db; cursor: pointer; }
    .rating label:hover, .rating label:hover ~ label,
    .rating input:checked ~ label { color: #f59e0b; }
    #alert_main, #alert_fail, #ratingvalue, #sendComment { display: none; }
  </style>
</head>
<body>

<nav class="navbar bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
    </a>
    <div class="d-flex gap-1">
      <a class="nav-link px-2" href="{{ route('home') }}">Shop</a>
      @if(session()->has('clientemail'))
        <a class="nav-link px-2" href="{{ route('cart') }}">Cart</a>
      @endif
    </div>
  </div>
</nav>

@foreach($product as $product)
<main class="container py-4 py-lg-5">
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Shop</a></li>
      @if($product->type)<li class="breadcrumb-item">{{ $product->type }}</li>@endif
      <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
  </nav>

  <div id="alert_main" class="alert alert-success"></div>
  <div id="alert_fail" class="alert alert-danger"></div>

  <div class="row g-4 g-lg-5">
    <div class="col-lg-7">
      <div class="position-relative">
        @if($product->photo != null)
          <img src="{{ asset('img/'.$product->photo) }}" alt="{{ $product->name }}" class="product-photo w-100 rounded-4 border">
        @else
          <div class="product-photo w-100 rounded-4 border bg-light d-flex align-items-center justify-content-center text-muted">No preview photo</div>
        @endif
        @if($product->promotion > 0)
          <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6">-{{ $product->promotion }}%</span>
        @endif
      </div>
    </div>

    <div class="col-lg-5">
      <div class="cw-panel">
        <h1 class="h3 mb-1">{{ $product->name }}</h1>
        <p class="text-muted mb-3">by <a href="/userprofile/{{ $product->userId }}" class="fw-medium text-decoration-none">{{ $product->username }}</a></p>
        <button type="button" id="userid" value="{{ $product->userId }}" style="display:none">{{ $product->userId }}</button>

        <div class="d-flex align-items-baseline gap-2 mb-3">
          @if($product->promotion > 0)
            @php $newprice = $product->price - (($product->promotion / 100) * $product->price); @endphp
            <span class="fs-3 fw-semibold">${{ $newprice }}</span>
            <span class="price-old">${{ $product->price }}</span>
            <input type="number" id="prodPrice" value="{{ $newprice }}" style="display:none">
          @else
            <span class="fs-3 fw-semibold">${{ $product->price }}</span>
            <input type="number" id="prodPrice" value="{{ $product->price }}" style="display:none">
          @endif
        </div>

        <dl class="row small mb-3">
          <dt class="col-4 text-muted fw-normal">Size</dt>
          <dd class="col-8 mb-1">{{ $product->width }} × {{ $product->height }}</dd>
          @if($product->type)
            <dt class="col-4 text-muted fw-normal">Category</dt>
            <dd class="col-8 mb-1">{{ $product->type }}</dd>
          @endif
          <dt class="col-4 text-muted fw-normal">In stock</dt>
          <dd class="col-8 mb-1">{{ $product->stock }}</dd>
        </dl>

        @if($product->description)
          <p class="mb-4">{{ $product->description }}</p>
        @endif

        <div class="d-flex gap-2 mb-4">
          <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Qty" min="1" max="{{ $product->stock }}" value="1" style="max-width:90px">
          <button type="button" class="btn btn-dark flex-grow-1" name="placeorder" id="placeorder">Add to cart</button>
        </div>

        <div class="border-top pt-3">
          <div class="small text-muted mb-1">Rate this piece</div>
          <div class="rating">
            @for($i = 5; $i >= 1; $i--)
              <input type="radio" class="ratingrb" name="rating" value="{{ $i }}" id="{{ $i }}"><label for="{{ $i }}" title="{{ $i }} stars">★</label>
            @endfor
          </div>
          <div id="ratingvalue" class="small text-muted mt-1"></div>
        </div>
      </div>
    </div>
  </div>

  <section class="mt-5">
    <h2 class="h5 mb-3">Comments</h2>
    <div class="cw-panel">
      @php
        $comments=DB::select("select * from comment where productId = ? order by id desc", [$product->id]);
      @endphp
      <ul class="list-unstyled mb-0" id="tablecomments">
        @forelse($comments as $comments)
          @php
            $clients=DB::select("select * from client where id = ?", [$comments->clientId]);
          @endphp
          <li class="d-flex justify-content-between align-items-start py-2 border-bottom" id="comment_{{ $comments->id }}">
            <div>
              <div class="fw-medium">@foreach($clients as $clients){{ $clients->name }}@endforeach</div>
              <div>{{ $comments->comment }}</div>
            </div>
            @foreach($clientId as $c)
              @if($comments->clientId == $c->id)
                <button type="button" class="btn btn-sm btn-light" onclick="deleteComment({{ $comments->id }})" title="Delete comment"><i class="bi bi-trash"></i></button>
              @endif
            @endforeach
          </li>
        @empty
          <li class="text-muted py-2">No comments yet.</li>
        @endforelse
      </ul>

      <div class="input-group mt-3">
        <input type="text" id="comment" name="comment" class="form-control comment" placeholder="Add a comment">
        <button type="button" onclick="addComment({{ $product->id }})" class="btn btn-dark comment" id="sendComment">Post</button>
      </div>
    </div>
  </section>

  <div style="display:none">
    @foreach($clientId as $c)
      <button disabled value="{{ $c->id }}" id="clientId"></button>
      <button disabled value="{{ $product->id }}" id="productId"></button>
      <button disabled value="{{ $c->id }}" id="clientid"></button>
      <button disabled value="{{ $product->id }}" id="productid"></button>
    @endforeach
  </div>
</main>
@endforeach

<footer class="py-4 text-center">
  &copy; {{ date('Y') }} CallyWorld · Developed by Bilal Kaderi
</footer>

@include('layouts.theme', ['area' => 'shop'])
</body>
</html>
