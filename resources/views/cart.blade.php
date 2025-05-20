@include('admin.bars.tops')
<script type="text/javascript">

  $(document).ready(function(){
    $("#closemodalAlert").click(function(){
      $("#modalAlert").fadeOut();
    });

$("#toggleNavBar").click(function(){
  $(".navbar-collapse").toggle();
});


$("#payamount").click(function(){
  alert("clci");
});

});

function openModel(){
  $('#exampleModal').fadeIn();
}

function showbankinfo(){
  if ($("#paymentMethod").val() == 'Master') {
    $("#bank_card").show();
    $("#addressDiv").hide();
  }
  else{
    $("#addressDiv").show();
    $("#bank_card").hide();
  }
}

function increase(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  if($("#quantity_"+id).val()<5){
    $("#quantity_"+id).val(Number($("#quantity_"+id).val())+1);
    $("#expecting_delivery_time_"+id).val(Number($("#time_"+id).val())*Number($("#quantity_"+id).val()));
    var price = Number($("#product_price_"+id).val());
    $("#totalPrice").val(Number($("#totalPrice").val())+price);
    $("#total_price_item_"+id).val(Number($("#total_price_item_"+id).val())+price);
  }
    $.ajax({
        url:"{{route('increase')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
          // alert(output.success);
            // $("#rate_"+id).fadeOut();
            // $("#alertUser").text('');
            // $("#alertUser").append(output.success);
            // $("#alertUser").fadeIn();
          }
});
}
function increase(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  if($("#quantity_"+id).val()<5){
    $("#quantity_"+id).val(Number($("#quantity_"+id).val())+1);
    $("#expecting_delivery_time_"+id).val(Number($("#time_"+id).val())*Number($("#quantity_"+id).val()));
    var price = Number($("#product_price_"+id).val());
    $("#totalPrice").val(Number($("#totalPrice").val())+price);
    $("#total_price_item_"+id).val(Number($("#total_price_item_"+id).val())+price);
  }
    $.ajax({
        url:"{{route('increase')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
          // alert(output.success);
            // $("#rate_"+id).fadeOut();
            // $("#alertUser").text('');
            // $("#alertUser").append(output.success);
            // $("#alertUser").fadeIn();
          }
});
}
function decrease(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  if($("#quantity_"+id).val()>1){
    $("#quantity_"+id).val(Number($("#quantity_"+id).val())-1);
    var price = Number($("#product_price_"+id).val());
    $("#expecting_delivery_time_"+id).val(Number($("#expecting_delivery_time_"+id).val())-Number($("#time_"+id).val()));
    $("#totalPrice").val(Number($("#totalPrice").val())-price);
    $("#total_price_item_"+id).val(Number($("#total_price_item_"+id).val())-price);
  }
    $.ajax({
        url:"{{route('decrease')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
          // alert(output.success);
          // var price= output.price;
            // $("#rate_"+id).fadeOut();
            // $("#totalPrice").text('');
            // $("#alertUser").fadeIn();
          }
});
}
    function removeFromCart(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      $("#countItems").val(Number($("#countItems").val())-1);
      var quantity=$("#quantity_"+id).val();
      var price = Number($("#product_price_"+id).val());
      var total=price*quantity;
      $("#totalPrice").val(Number($("#totalPrice").val())-total);
        $.ajax({
            url:"{{route('removeFromCart')}}",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#item_"+id).fadeOut();
                // $("#alertUser").append(output.success);
                // alert(output.success);
              }
    });
    }

    function orderCart(){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var total_amount=$("#totalPrice").val();
      var date=Number($("#date").val())+1;
      var paymentMethod=$("#paymentMethod").val();
      var address=$("#address").val();
      if(address==''){
        $('#exampleModal').fadeOut();
        $("#modalAlert").fadeIn();

      }
      else{
        $.ajax({
            url:"{{route('orderCart')}}",
            method:'post',
            data: {date:date, total_amount:total_amount, paymentMethod:paymentMethod, address:address},
            success: function(output){
              location.reload();
                // $("#prodrate_"+id).fadeOut();
                // $("#alertProd").text('');
                // $("#alertProd").append(output.success);
                // $("#alertProd").fadeIn();
              }
          });
      }
    }

</script>

@php
  $items = session()->get('items');
  $itemsIds = collect($items)->map(function($item){
        return $item['id'];
    })->flatten()->toArray();
  $count = count($items);
  $totalPrice=0;
  if($count>0){
    $delivery=10;
  }else{
    $delivery=0;
  }
  $max_day=DB::table('product')->whereIn('id', $itemsIds)->max('expecting_delivery_time');

@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
<title></title>
<meta name="_token" content="{{csrf_token()}}">

<style media="screen">
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
    .box{
      width: 300px; height: 240px; position: center;
      border-radius: 20px;
      margin: 25px;
    }

    .box .front,
    .box .back{
      position: absolute;
      width: 300px; height: 240px; background: white;
      backface-visibility: hidden; cursor: default;
      border-radius: 20px;
      box-shadow: 0 5px 3px rgba(0,0,0, 0.2 ), 0 -5px 5px rgba(0,0,0, 0.200);
      transition: transform 0.4s;
      text-align: center;
    }

    .box .back{
      display: flex;
      justify-content: center;
      align-items: center; flex-direction: column;
      transform: perspective(800px) rotateX(-180deg);
      color:black;
    }
    .box .back a{

    }


    .box .back p{
      margin: 10px 20px;
      text-align: center;
    }

    .box.front img{
      width: 100%;
      height: 100%;
      border-radius:20px; position: absolute;
    }

    .btnorder{
      padding:5px 5px 5px 5px;
      background-color:rgba(100,5,0,0.6);
      border-color:white;
      border-radius:6px;
      color:white;
    }
    .btnorder:hover,
    .btnorder:focus{
      background-color: rgba(100,5,0,0.9);
      transition: 0.3s;
      color:white;
      cursor: pointer;
    }

    .box:hover .front{
      transform: perspective(800px) rotateX(180deg);
    }

    .box:hover .back{
      border-radius: 20px; transform: perspective(800px) rotateX(0);
    }
    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    nav {
      min-height: 70px;
    }

    nav .navbar-brand .logo {
      padding: 10px 15px;
      border-radius: 8px;
    }

    nav .navbar-brand .logo .h2 {
      margin: 0;
    }

    nav .navbar-brand .h4 {
      margin-bottom: 0px;
      font-weight: 900;
    }

    nav .navbar-brand .fs-6 {
      font-size: 0.88rem !important;
    }

    nav ul li {
      padding: 0 20px;
    }

    .navbar-light .navbar-nav .nav-link {
      color: #333;
    }

    .navbar-light .navbar-nav .nav-link:hover {
      color: #4e2296;
    }

    .navbar-light .navbar-nav .nav-link.active {
      color: #451296;
    }

    nav ul li a .cart {
      padding: 4px 6px;
      border-radius: 6px;
      position: relative;
      display: inline;
    }

    nav ul li a .cart::after {
      position: absolute;
      content: "";
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: #ff5252;
      top: -1px;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    #sidebar {
      padding: 15px 0px 15px 0px;
      border-radius: 10px;
    }

    #sidebar .h4 {
      font-weight: 500;
      padding-left: 15px;
    }

    #sidebar ul {
      list-style: none;
      margin: 0;
      padding-left: 0rem;
    }

    #sidebar ul li {
      padding: 10px 0;
      display: block;
      padding-left: 1rem;
      padding-right: 1rem;
      border-left: 5px solid transparent;
    }

    #sidebar ul li.active {
      border-left: 5px solid #ff5252;
      background-color: #44007c;
    }

    #sidebar ul li a {
      display: block;
    }

    #sidebar ul li a .fas,
    #sidebar ul li a .far {
      color: #ddd;
    }

    #sidebar ul li a .link {
      color: #fff;
      font-weight: 500;
    }

    #sidebar ul li a .link-desc {
      font-size: 0.8rem;
      color: #ddd;
    }

    #main-content {
      padding: 30px;
      border-radius: 15px;
    }

    #main-content .h5,
    #main-content .text-uppercase {
      font-weight: 600;
      margin-bottom: 0;
    }

    #main-content .h5+div {
      font-size: 0.9rem;
    }

    #main-content .box {
      padding: 10px;
      border-radius: 6px;
      width: 170px;
      height: 90px;
    }

    #main-content .box img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    #main-content .box .tag {
      font-size: 0.9rem;
      color: #000;
      font-weight: 500;
    }

    #main-content .box .number {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .order {
      padding: 10px 30px;
      min-height: 150px;
    }

    .order .order-summary {
      height: 100%;
    }

    .order .blue-label {
      background-color: #aeaeeb;
      color: #0046dd;
      font-size: 0.9rem;
      padding: 0px 3px;
    }

    .order .green-label {
      background-color: #a8e9d0;
      color: #008357;
      font-size: 0.9rem;
      padding: 0px 3px;
    }
    .order .red-label {
      background-color: #9b1a21;
      color: white;
      font-size: 0.9rem;
      padding: 0px 3px;
    }

    .order .fs-8 {
      font-size: 0.85rem;
    }

    .order .rating img {
      width: 20px;
      height: 20px;
      object-fit: contain;
    }

    .order .rating .fas,
    .order .rating .far {
      font-size: 0.9rem;
    }

    .order .rating .fas {
      color: #daa520;
    }

    .order .status {
      font-weight: 600;
    }

    .order .btn.btn-primary {
      background-color: #fff;
      color: #4e2296;
      border: 1px solid #4e2296;
    }

    .order .btn.btn-primary:hover {
      background-color: #4e2296;
      color: #fff;
    }

    .order .progressbar-track {
      margin-top: 20px;
      margin-bottom: 20px;
      position: relative;
    }

    .order .progressbar-track .progressbar {
      list-style: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-left: 0rem;
    }

    .order .progressbar-track .progressbar li {
      font-size: 1.5rem;
      border: 1px solid #333;
      padding: 5px 10px;
      border-radius: 50%;
      background-color: #dddddd;
      z-index: 100;
      position: relative;
    }

    .order .progressbar-track .progressbar li.green {
      border: 1px solid #007965;
      background-color: #d5e6e2;
    }

    .order .progressbar-track .progressbar li::after {
      position: absolute;
      font-size: 0.9rem;
      top: 50px;
      left: 0px;
    }

    #tracker {
      position: absolute;
      border-top: 1px solid #bbb;
      width: 100%;
      top: 25px;
    }

    #step-1::after {
      content: 'Placed';
    }

    #step-2::after {
      content: 'Accepted';
      left: -10px;
    }

    #step-3::after {
      content: 'Packed';
    }

    #step-4::after {
      content: 'Shipped';
    }

    #step-5::after {
      content: 'Delivered';
      left: -10px;
    }



    /* Backgrounds */
    .bg-purple {
      background-color: #55009b;
    }

    .bg-light {
      background-color: #f0ecec !important;
    }

    .green {
      color: #007965 !important;
    }

    /* Media Queries */
    @media(max-width: 1199.5px) {
      nav ul li {
          padding: 0 10px;
      }
    }

    @media(max-width: 500px) {
      .order .progressbar-track .progressbar li {
          font-size: 1rem;
      }

      .order .progressbar-track .progressbar li::after {
          font-size: 0.8rem;
          top: 35px;
      }

      #tracker {
          top: 20px;
      }
    }

    @media(max-width: 440px) {
      #main-content {
          padding: 20px;
      }

      .order {
          padding: 20px;
      }

      #step-4::after {
          left: -5px;
      }
    }

    @media(max-width: 395px) {
      .order .progressbar-track .progressbar li {
          font-size: 0.8rem;
      }

      .order .progressbar-track .progressbar li::after {
          font-size: 0.7rem;
          top: 35px;
      }

      #tracker {
          top: 15px;
      }

      .order .btn.btn-primary {
          font-size: 0.85rem;
      }
    }

    @media(max-width: 355px) {
      #main-content {
          padding: 15px;
      }

      .order {
          padding: 10px;
      }
    }

    .card-form {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
    }
</style>

<div class="modal" id="modalAlert" tabindex="-1" style="">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Alert</h5>
    </div>
    <div class="modal-body" id="modalbody">
      <div class="alert alert-danger" style="background-color:rgba(220,0,0,0.7);display:block;text-align: center;">Delivery address should not be empty!</div>
    </div>
    <div class="modal-footer" >
      <button type="button" id="closemodalAlert" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
   <div class="container">
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynav"
           aria-controls="mynav" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
       </button>
       <a class="navbar-brand" href="#">
           <div class="d-flex">
               <div class="d-flex align-items-center logo bg-white">
                 <img src="{{asset('css/icons/pen.png')}}" style="width:44px;height:46px">
               </div>
               <div class="ms-3 d-flex flex-column">
                 <div class="h4">CallyWorld</div>
                   <div class="fs-6">My Cart</div>
               </div>
           </div>
       </a>
       <div class="collapse navbar-collapse" id="mynav">
           <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
               <li class="nav-item">
                   <a class="nav-link active" aria-current="page" href="{{route('home')}}">Homepage<span
                           class="fas fa-th-large px-1"></span></a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" href="#" id="updateAccount">Update Account</a>
               </li>
               <li class="nav-item">
                   <a class="nav-link" style="cursor:default"><span class="fas fa-user pe-2"></span> Hello </a>
               </li>
           </ul>
       </div>
   </div>
</nav>
    <section class="h-100 gradient-custom">
        <div class="section" id="orders-section" style="padding:60px 0 60px 0;background-color:rgba(245,245,250,0.99);" >
        @php
          $id=$client->id;
          $carts=DB::table('cart')->where('clientId','=',$id)->get()->sortbyDesc('id');
        @endphp
        <div class="container mt-4">
             <div class="row">
                 <div class="col-lg-9 my-lg-0 my-1">
                   <div class="d-flex flex-column">
                     <div class="h5">Hello {{$client->name}}</div>
                     <div>Logged in as: {{$client->email}}</div>
                   </div>
                   @foreach($carts as $c)
                     @php
                       $data = json_decode($c->items, true);
                     @endphp
                       <div class="order my-3 bg-light" style="padding:20px;border-radius:10px;" id="cart_{{$c->id}}">
                           <div class="row" >
                               <div class="col-lg-4">
                                   <div class="d-flex flex-column justify-content-between order-summary">
                                       <div class="d-flex align-items-center">
                                         <div class="text-uppercase">Cart #{{$c->id}}</div>
                                       </div>
                                       <div class="d-flex align-items-center">
                                         <div class="text-uppercase">Address: {{$c->pref_address}}</div>
                                       </div>
                                       <div class="fs-8">{{$c->created_at}}</div>
                                   </div>
                               </div>
                               <div class="col-lg-8" >
                                   <div class="d-sm-flex align-items-sm-start justify-content-sm-between">
                                     <div class="status">Status : {{$c->status}}</div>
                                       @if($c->status=='On Preparing')
                                         @php
                                           $date1 = now()->Format('Y-m-d');
                                           $date2 = $c->expecting_delivery_date;
                                           $d1=new DateTime($date1);
                                           $d2=new DateTime($date2);

                                           $days = $d2->diff($d1)->format("%a");
                                         @endphp
                                         <div class="red-label ms-auto text-uppercase">
                                          @if($d1>$d2) {{$c->expecting_delivery_date}} Too Late @else @if($c->status=='On Preparing')Ready In @else In @endif {{$days}} Days @endif</div>
                                         @elseif($c->status=='On Delivery')
                                          <div class="blue-label ms-auto text-uppercase">Exp. Del. Time {{$c->delivery_hour}}</div>
                                         @elseif($c->status=='Delivered')
                                          <div class="blue-label ms-auto text-uppercase">Delivered on {{$c->updated_at}}</div>
                                        @else
                                         <div class="blue-label ms-auto text-uppercase">Exp. Del. Date {{$c->expecting_delivery_date}}</div>
                                        @endif

                                   </div>
                                   <div class="progressbar-track">
                                     @if($c->status=='Delivered')
                                        <span class="fas fa-gift" style="color:green;font-size:25px"></span>
                                       <div id="tracker"></div>
                                     @elseif($c->status=='On Delivery')
                                       <span class="fas fa-box" style="color:blue;font-size:25px"></span>
                                       <div id="tracker"></div>
                                       @elseif($c->status=='On Preparing')
                                        <span class="fas fa-box-open" style="color:red;font-size:25px"></span>
                                        <div id="tracker"></div>
                                       @else
                                        <span class="fas fa-check" style="color:#444;font-size:25px"></span>
                                        <div id="tracker"></div>
                                      @endif
                                   </div>
                                   <div class="d-sm-flex align-items-sm-start justify-content-sm-between">
                                     <div class="status"><strong>Products :</strong><br>
                                       @foreach($data as $data)
                                       @php
                                         $id=$data['id'];
                                         $quantity=$data['quantity'];
                                         $prod=DB::select("select * from product where id=$id");
                                       @endphp

                                       @foreach($prod as $p){{$p->name}}: {{$quantity}} items
                                       <div class="" style="font-size:14px">
                                         Item's price: $ {{$data['price']}}
                                       </div> <br>
                                       @endforeach
                                     @endforeach

                                     </div>
                                     <div class="red-label ms-auto text-uppercase"><strong>Total: </strong>${{$c->total_amount}}</div>
                                   </div>
                                   @if($c->delivery_details >0)
                                   @php
                                    $delman=DB::table('deliveryman')->where('id','=',$c->delivery_details)->first();
                                   @endphp
                                   <div class="red-label ms-auto text-uppercase"><strong>Driver: </strong>{{$delman->name}}<p><a style="color:white" href='tel:{{$delman->phone}}'>{{$delman->phone}}</a></p></div>
                                   @endif
                               </div>
                               <div class="alert alert-success" id="alertConfirm_{{$c->id}}" style="display:none;margin-top:15px"></div>
                             </div>
                           </div>
                       @endforeach
                   </div>
               </div>
             </div>
           </div>
      </center>

  <div class="container py-5">
    <div class="row d-flex justify-content-center my-4">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Cart - <input readonly type="number" id="countItems" style="font-weight:500;font-size:30;border:0;width:40px" value="{{$count}}"> items</h5>
          </div>
          <div class="card-body">
            <!-- Single item -->
            @foreach($items as $item)
              @php
                $id=$item['id'];
                $prod=DB::select("select * from product where id=$id");
              @endphp
              @foreach($prod as $prod)
            <div class="row" id="item_{{$prod->id}}">
              <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                <!-- Image -->
                 @php
                   $pr=$item['price'];
                 @endphp
                <input type="number" id="product_price_{{$prod->id}}" value="{{$pr}}" readonly style="display:none">
                <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                  <img src="{{asset ('img/'.$prod->photo)}}"
                    class="w-100" style="border-radius:4px"/>
                  <a href="#!">
                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                  </a>
                </div>
                <!-- Image -->
              </div>
              <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                <input type="number" style="display:none" readonly id="time_{{$prod->id}}" value="{{$prod->expecting_delivery_time}}">
                <!-- Data -->
                <p><strong>{{$prod->name}}</strong></p>
                <p>{{$prod->description}}</p>
                <p>Size: {{$prod->width}} x {{$prod->height}}</p>
                @php
                  $time=$item['quantity']*$prod->expecting_delivery_time;
                @endphp
                <p>Expecting Delivery Time: @if($val=$time%7 == 0)
                                              <input type="number" style="width:30px;border:0;text-align: right;" readonly id="expecting_delivery_time_{{$prod->id}}" value="{{$val}}"> Week
                                            @else
                                               <input type="number" style="width:30px;border:0;text-align: right;" readonly id="expecting_delivery_time_{{$prod->id}}" value="{{$time}}">Days
                                            @endif
                </p>
                <button type="button" onclick="removeFromCart({{$prod->id}})" class="btn btn-danger btn-sm me-1 mb-2" data-mdb-toggle="tooltip"
                  title="Remove item">
                  <i class="fas fa-trash"></i>
                </button>
                <!-- Data -->
              </div>

              <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <!-- Quantity -->
                <div class="d-flex mb-4" style="max-width: 300px">
                  <button class="btn btn-light px-3 me-2" id=""
                    onclick="decrease({{$prod->id}})">
                    <i class="fas fa-minus"></i>
                  </button>

                  <div class="form-outline">
                    <input id="quantity_{{$prod->id}}" readonly min="1" max="5"  name="quantity" value="{{$item['quantity']}}" type="number" class="form-control" />
                    <label class="form-label" for="form1">Quantity</label>
                  </div>

                  <button class="btn btn-light px-3 ms-2"
                    onclick="increase({{$prod->id}})">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
                <!-- Quantity -->

                <!-- Price -->
                <p class="text-start text-md-center">

                   @php
                     $total_price=$item['price'] * $item['quantity'];
                     $totalPrice+=$total_price;
                   @endphp
                  <span><strong>$</strong><input type="number" readonly id="total_price_item_{{$prod->id}}" style="font-weight:800;border:0;width:45px" value="{{$total_price}}"></span>
                </p>
                <!-- Price -->
              </div>
            </div>
            <!-- Single item -->

            <hr class="my-4" />
            @endforeach
            @endforeach
<!-- -------------------------------------------------------------- -->

          </div>
        </div>
        @if($count!='0')
          <div class="card mb-4">
            <div class="card-body">
              @php
                $dt=now()->addDays($max_day+1)->format('d-m-Y');
              @endphp
              <p><strong>Expected Delivery Date</strong></p><input type="date" id="date" value="{{$max_day}}" style="display:none">
              <p class="mb-0">{{$dt}}</p>
            </div>
          </div>
        @endif
        <div class="card mb-4 mb-lg-0">
          <div class="card-body">
            <p><strong>We accept</strong></p>
            <img class="me-2" width="45px"
              src="https://mdbcdn.b-cdn.net/wp-content/plugins/woocommerce-gateway-stripe/assets/images/visa.svg"
              alt="Visa" />

            <img class="me-2" width="45px"
              src="https://mdbcdn.b-cdn.net/wp-content/plugins/woocommerce-gateway-stripe/assets/images/mastercard.svg"
              alt="Mastercard" />
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                Products
                <span>${{$totalPrice}}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0" id="delivery">
                Delivery
                <span>${{$delivery}}</span>
              </li>
              <li
                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                <div>
                  <strong>Total amount</strong>
                  <strong>
                    <p class="mb-0">(including VAT)</p>
                  </strong>
                </div>
                <span><strong>$@php $total=$totalPrice+$delivery @endphp</strong><input type="number" readonly id="totalPrice" style="font-weight:800;border:0;width:45px" value="{{$total}}"></strong></span>
              </li>
            </ul>
          @if($count!='0')
          <button type="button" class="btn btn-primary" id="" onclick="openModel()">
              Order Now
            </button>
          @endif
          </div>
        </div>
        <div class="modal" id="exampleModal" style="height:auto; display:none">
          <div class="modal-dialog" role="document" style="height:auto">
            <div class="modal-content" style="width:auto;height:auto">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Additional Info</h5>
              </div>
              <div class="modal-body">
                <strong style="font-size:14px; color:red">Kindly note that once you press the button below you have to receive your order, or you'll lose our services.</strong><br>
                <strong>Payment Method</strong>
                <select id="paymentMethod" class="form-control" name="" onchange="showbankinfo()">
                  <option value="Cash">Cash</option>
                  <option value="Visa">Visa Card</option>
                  <option value="Master">Master Card</option>
                </select>
                <div class="" id="bank_card" style="display:none">
                  <!-- Bank Form -->
                    <div class="container">
                      <div class="card-form">
                        <h2>Bank Card Information</h2>
                        <form action="{{ route('processPayment') }}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" class="form-control" id="card-number" name="cardNumber" placeholder="Enter card number" required>
                          </div>
                          <div class="form-group">
                            <label for="card-holder">Card Holder</label>
                            <input type="text" class="form-control" id="card-holder" name="cardHolder" placeholder="Enter card holder name" required>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="expiry-date">Expiry Date</label>
                              <input type="text" class="form-control" id="expiry-date" name="cardExpiry" placeholder="MM/YY" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="cvv">CVV</label>
                              <input type="text" class="form-control" id="cvv" placeholder="CVV" name="cardCVV" required>
                            </div>
                            <input type="text" style="display:none" value="{{$total}}" id="amountPayment" name="amountPayment">
                          </div>
                          <strong>Delivery Address:</strong>
                          <input type="text" name="addressClient" value="{{$client->address}}" id="addressClient" placeholder="Preferred Delivery Address" class="form-control">
                          <input type="date" id="date_2" value="{{$max_day}}" style="display:none">

                      <!-- Existing form fields -->
                      <!-- ... -->
                          <script src="https://checkout.stripe.com/checkout.js"
                                  class="stripe-button"
                                  data-key="{{ config('services.stripe.key') }}"
                                  data-amount="{{$total*100}}"
                                  data-name="CallyWorld"
                                  data-description="Payment Data"
                                  data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                  data-locale="auto"
                                  data-currency="usd">
                          </script>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                      </div>
                    </div>
                    <!-- Bank form end -->
                </div>
                <div id="addressDiv">
                  <strong>Delivery Address:</strong>
                  <input type="text" name="address" value="{{$client->address}}" id="address" placeholder="Preferred Delivery Address" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button onclick="orderCart()" type="button" class="btn btn-primary btn-lg btn-block">Order Now</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
