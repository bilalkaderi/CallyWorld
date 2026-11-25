@php
  $clientemail=session()->get('clientemail');
  $clientId = DB::table('client')->where('email', $clientemail)->get()->all();
  $client = $clientId[0] ?? null;

  $id=$user->id;
  $nbPro=DB::table('product')->where('userId','=',$id)->count();
  $count=DB::table('rate')->where('userId','=',$id)->count();
  $sum=DB::table('rate')->where('userId','=',$id)->sum('rate');
  $ratings = $count > 0 ? round($sum/$count, 1) : 0;

  $subscribers=json_decode($user->subscribers ?? '[]', true) ?? [];
  $followers=count($subscribers);
  $subscribed = ($client && in_array($client->id, $subscribers)) ? 'yes' : 'no';

  $products=DB::table('product')
    ->select('id','name','photo','price','promotion','width','height')
    ->where('userId','=',$id)
    ->get();

  $photo = $user->photo && file_exists(public_path('profiles/'.$user->photo))
    ? asset('profiles/'.$user->photo)
    : asset('img/profile.png');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{csrf_token()}}">
  <title>{{ $user->name }} · CallyWorld</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
      $("#closemodal").click(function(){
        $("#modalAlert").fadeOut();
      });

      $(".ratingrb").click(function(){
        $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        var form_data = new FormData();
        form_data.append("userid", $('#userid').val());
        form_data.append("clientid", $('#clientid').val());
        form_data.append("rating", $(this).val());
        $.ajax({
          url:"{{ route('rateUser') }}",
          method:'post',
          data:form_data,
          contentType: false,
          processData: false,
          success: function(output){
            $("#ratingvalue").text(output.ratings);
          }
        });
      });

      $("#subscribe").click(function(){
        $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        var form_data = new FormData();
        form_data.append("userid",$('#userid').val());
        form_data.append("clientid",$('#clientid').val());
        $.ajax({
          url:"{{ route('subscribe') }}",
          method:'post',
          data:form_data,
          contentType: false,
          processData: false,
          success: function(output){
            $("#modalbody").text(output.success);
            $("#modalAlert").fadeIn();
            $("#subscribe").fadeOut();
          }
        });
      });

      $("#unsubscribe").click(function(){
        $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        var form_data = new FormData();
        form_data.append("userid",$('#userid').val());
        form_data.append("clientid",$('#clientid').val());
        $.ajax({
          url:"{{ route('unsubscribe') }}",
          method:'post',
          data:form_data,
          contentType: false,
          processData: false,
          success: function(output){
            $("#modalbody").text(output.success);
            $("#modalAlert").fadeIn();
            $("#unsubscribe").fadeOut();
          }
        });
      });

      $("#first-report").click(function(){
        $(".report").hide();
        $(".reason-report").fadeIn();
      });
      $("#cancel-block").click(function(){
        $(".block").fadeOut();
      });
    });

    function report(id){
      $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var form_data = new FormData();
      form_data.append("id", id);
      form_data.append("clientid", $('#clientid').val());
      form_data.append("reportmessage", $('#reportmessage').val());
      $.ajax({
        url:"{{ route('reportUser') }}",
        method:'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function(output){
          $(".reason-report").hide();
          $(".block-message").text(output.success);
          $(".block").fadeIn();
        }
      });
    }

    function block(id){
      $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var form_data = new FormData();
      form_data.append("id", id);
      form_data.append("clientid", $('#clientid').val());
      $.ajax({
        url:"{{ route('blockUser') }}",
        method:'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function(output){
          window.location.href = "{{route('home')}}";
        }
      });
    }

    function sendEmail(id){
      $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var form_data = new FormData();
      form_data.append("to",$('#to').val());
      form_data.append("clientname",$('#emailname').val());
      form_data.append("clientemail",$('#email').val());
      form_data.append("subject",$('#subject').val());
      form_data.append("message",$("#message").val());
      $.ajax({
        url:"{{ route ('sendEmail') }}",
        method:'post',
        data:form_data,
        contentType: false,
        processData: false,
        success: function(output){
          $("#message").val('');
          $("#subject").val('');
          $("#thanks").fadeIn();
        }
      });
    }
  </script>
  <style>
    .avatar { width: 112px; height: 112px; object-fit: cover; }
    .product-img, .product-img-empty { height: 200px; object-fit: cover; }
    .rating { display: inline-flex; flex-direction: row-reverse; gap: .15rem; }
    .rating input { display: none; }
    .rating label { font-size: 1.4rem; line-height: 1; color: #d1d5db; cursor: pointer; }
    .rating label:hover, .rating label:hover ~ label,
    .rating input:checked ~ label { color: #f59e0b; }
    .reason-report, .block, #thanks { display: none; }
  </style>
</head>
<body>

<div class="modal" id="modalAlert" tabindex="-1" style="background:rgba(0,0,0,.3)">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body" id="modalbody"></div>
      <div class="modal-footer">
        <button type="button" id="closemodal" class="btn btn-light">Close</button>
      </div>
    </div>
  </div>
</div>

<nav class="navbar bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      <img src="{{ asset('css/icons/pen.png') }}" width="32" height="32" alt="">
      <span class="fw-semibold">CallyWorld</span>
    </a>
    <div class="d-flex gap-1">
      <a class="nav-link px-2" href="{{ route('home') }}">Shop</a>
      @if($client)
        <a class="nav-link px-2" href="{{ route('cart') }}">Cart</a>
      @endif
    </div>
  </div>
</nav>

<main class="container py-4 py-lg-5">
  <button id="userid" style="display:none" value="{{ $user->id }}" disabled></button>
  @if($client)
    <button id="clientid" style="display:none" value="{{ $client->id }}" disabled></button>
  @endif

  <div class="cw-panel mb-5">
    <div class="d-flex flex-column flex-md-row gap-4 align-items-md-center">
      <img src="{{ $photo }}" alt="{{ $user->name }}" class="avatar rounded-circle border">
      <div class="flex-grow-1">
        <h1 class="h3 mb-1">{{ $user->name }}</h1>
        <p class="text-muted mb-3">{{ $user->aboutme ?: 'Calligrapher on CallyWorld.' }}</p>
        <div class="d-flex gap-4 small">
          <div><span class="fw-semibold fs-5">{{ $followers }}</span> <span class="text-muted">followers</span></div>
          <div><span class="fw-semibold fs-5">{{ $nbPro }}</span> <span class="text-muted">products</span></div>
          <div><span class="fw-semibold fs-5">{{ $ratings }}</span> <span class="text-muted">rating</span></div>
        </div>
      </div>

      @if($client)
        <div class="d-flex flex-column gap-2 align-items-md-end">
          <div class="d-flex gap-2">
            @if($subscribed == 'no')
              <button type="button" class="btn btn-dark" id="subscribe">Follow</button>
            @else
              <button type="button" class="btn btn-light" id="unsubscribe">Unfollow</button>
            @endif
            <button type="button" class="btn btn-light report" id="first-report">Report</button>
          </div>
          <div>
            <span class="small text-muted me-1">Rate:</span>
            <span class="rating align-middle">
              @for($i = 5; $i >= 1; $i--)
                <input type="radio" class="ratingrb" name="rating" value="{{ $i }}" id="{{ $i }}"><label for="{{ $i }}" title="{{ $i }} stars">★</label>
              @endfor
            </span>
            <span id="ratingvalue" class="small text-muted ms-1"></span>
          </div>
        </div>
      @endif
    </div>

    @if($client)
      <div class="reason-report border-top mt-4 pt-4">
        <label for="reportmessage" class="form-label">Why are you reporting {{ $user->name }}?</label>
        <textarea id="reportmessage" class="form-control mb-2" rows="2"></textarea>
        <button type="button" onclick="report({{ $user->id }})" class="btn btn-danger" id="last-report">Send report</button>
      </div>
      <div class="block border-top mt-4 pt-4">
        <p class="block-message mb-1"></p>
        <p class="mb-2">Do you also want to block this supplier? You will be returned to the shop.</p>
        <button type="button" onclick="block({{ $user->id }})" class="btn btn-danger">Block</button>
        <button type="button" id="cancel-block" class="btn btn-light">No</button>
      </div>
    @endif
  </div>

  <h2 class="h5 mb-3">Products by {{ $user->name }}</h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
    @forelse($products as $p)
      @php $newprice = $p->price - (($p->promotion / 100) * $p->price); @endphp
      <div class="col">
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
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-muted">No products yet.</div>
    @endforelse
  </div>

  <h2 class="h5 mb-3">Contact {{ $user->name }}</h2>
  <div class="cw-panel">
    <div id="thanks" class="alert alert-success">Your message was sent, thank you!</div>
    <form action="" method="post">
      @csrf
      <input type="email" name="to" id="to" value="{{ $user->email }}" style="display:none">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="emailname">Full name</label>
          <input type="text" class="form-control" name="name" id="emailname">
        </div>
        <div class="col-md-6">
          <label class="form-label" for="email">Email address</label>
          <input type="email" class="form-control" name="email" id="email">
        </div>
        <div class="col-12">
          <label class="form-label" for="subject">Subject</label>
          <input type="text" class="form-control" name="subject" id="subject">
        </div>
        <div class="col-12">
          <label class="form-label" for="message">Message</label>
          <textarea name="message" class="form-control" id="message" rows="4"></textarea>
        </div>
        <div class="col-12">
          <button type="button" onclick="sendEmail({{ $user->id }})" class="btn btn-dark">Send message</button>
        </div>
      </div>
    </form>
  </div>
</main>

<footer class="py-4 text-center">
  &copy; {{ date('Y') }} CallyWorld · Developed by Bilal Kaderi
</footer>

@include('layouts.theme', ['area' => 'shop'])
</body>
</html>
