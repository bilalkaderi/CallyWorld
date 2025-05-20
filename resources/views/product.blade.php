@php
  $clientemail=session()->get('clientemail');
  $clientId = DB::select("select * from client where email='$clientemail'");
@endphp
  <head>
    <meta charset="utf-8">
    <title></title>
    <meta name="_token" content="{{csrf_token()}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           $("#placeorder").click(function(e){
             e.preventDefault();
             $.ajaxSetup({
                 headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
               });

               //form_data.append("name", "name");
               var productId= $("#productid").val();
               var quantity=$("#quantity").val();
               var price=$("#prodPrice").val();

               $.ajax({
                   url:"{{route('ToCart')}}",
                   method:'POST',
                   data:{productId:productId, quantity:quantity, price:price},

                   success: function(output){
                     if(output.success=='Sign in first!'){
                       $("#alert_fail").text('');
                       $("#alert_fail").prepend(output.success);
                       $("#alert_fail").fadeIn();
                     }
                     else{
                       $("#placeorder").fadeOut();
                       $("#quantity").text('');
                       $("#alert_main").text('');
                       $("#alert_main").prepend(output.success);
                       $("#alert_main").fadeIn();
                     }

                       //$(".alert").show();
                       //$(".alert").html(output.success);
                       //$("#productstable").append(output.row);
                  }
               });
           });
           $("#comment").keyup(function(){
             var r = $(this).val().length;
                if(r == 0)
                    $("#sendComment").fadeOut();
                else
                    $("#sendComment").fadeIn("2000");
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
                     $("#ratingvalue").text(output.ratings);
                       $("#ratingvalue").fadeIn();

                       if(output.success=='Sign in first!'){
                         $("#alert_fail").text('');
                         $("#alert_fail").prepend(output.success);
                         $("#alert_fail").fadeIn();
                       }
                       else{
                         $("#alert_main").text('');
                         $("#alert_main").prepend(output.success);
                         $("#alert_main").fadeIn();
                       }
                       //$(".alert").html(output.success);
                  }
               });
           });
        });

        function addComment(id){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
          var id = id;
          var cId=$("#clientid").val();
          var comment = $("#comment").val();
            $.ajax({
                url:"{{ route('addcomment') }}",
                method:'get',
                data:{id:id, cId:cId, comment:comment},
                success: function(output){
                  if(output.success=='Sign in first!'){
                    $("#alert_fail").text('');
                    $("#alert_fail").prepend(output.success);
                    $("#alert_fail").fadeIn();
                  }
                  else{
                    $("#alert").fadeIn();
                    $("#alert_main").text('');
                    $("#alert_main").prepend(output.success);
                    $("#alert_main").fadeIn();
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
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <style media="screen">
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');

    @keyframes fadeInOutAnimation {
      0% {
        opacity: 0;
      }

      100% {
        opacity: 1;
      }
    }
    *{
      font-family: 'Poppins', sans-serif;
    }
    h2{
      font-weight: 800;
      text-transform:uppercase;
      color: black;
      letter-spacing: 2px;
      margin-top: -40px;
      font-size: 40px;
      animation: fadeInOutAnimation 1s infinite alternate;
    }

      body{
        align-items: center;
        background-color: white;
        width:97%;
      }
      .productinfo{
        padding: 10px;
        margin-left: 4rem;
        margin-top: 1rem;
        font-size: 25px;
      }

      .table{
        letter-spacing: -0.5px;
        width:500px;
        margin-top: 100px;
      }
      .table td{
        font-size: 19px;
        font-weight: 600;
      }

      .productphoto{
        margin-right:;
        margin-top: ;
        transition-delay: 0.3s;
      }

      .btnordernow{
        background-color: #8e221a;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        color:white;
        border:0;
        width:9rem;
        transition: all 0.3s ease-in;
      }
      .btnordernow:hover{
        background-color: white;
        color:#8e221a;
        transition: 0.3s;
        letter-spacing: 4px;

        cursor: pointer;
      }

      .back{
        text-decoration: none;
        font-size: 18px;
        color:white;
        margin-left: 5px;
        background-color:rgba(10,20,150,0.4);
        border-radius:5px;

        margin-top: ;
        text-align: center;
        transition: all 0.2s ease-in;
        padding:6px 15px;
      }
      .back:hover{
        background-color: rgba(142,34,26,0.8);
        color:white;
        transition: 0.2s;
      }


      .quantity:hover,
      .quantity:required{
        border-color: rgb(140,10,10);
        border-style: groove;
        transition: all 0.3s ease-out;
      }
      .commentdiv{
        margin: 30px;
      }

      .comment{
        text-transform: uppercase;
        text-align: center;
        border-radius: 5px;
        border-color: white;
        margin: 5px;
        padding: 10px;
        border-bottom: solid #8e221a;
        transition: 0.2s all;
      }
      .comment:focus,
      .comment:hover{
        background-color: #eee;
      }

      .alert-success {
        color: #0f6848;
        background-color: #66bb6a;
      }
      .alert-danger {
        color: blue;
        background-color: rgba(160,10,20,0.8);
      }

      .alert-success hr {
        border-top-color: #aaebd3;
      }
      .alert {
        position: static;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: none;
        display: none;
        align-self: center;
        align-items: center;
        border-radius: 0.35rem;
        color: #fafafa !important;
        width: 20%;
      }
      /*rating*/


      .rating {
      display: flex;
      margin-bottom: -20px;
      flex-direction: row-reverse;
      justify-content: center;
      }


      .rating > input{ display:none;}

      .rating > label {
      position: relative;
      width: 1em;
      font-size: 1.5vw;
      color: yellow;
      cursor: pointer;
      }

      .rating > label::before{
      content: "\2605";
      position: absolute;
      opacity: 0;
      }

      .rating > label:hover:before,
      .rating > label:hover ~ label:before {
      opacity: 1 !important;
      }

      .rating > input:checked ~ label:before{
      opacity:1;
      }

      .rating:hover > input:checked ~ label:before{ opacity: 0.4; }

      .ratingDiv > h4{
        font-style: italic;
        font-size: 20px;
        text-transform: uppercase;
        text-shadow: 5px;
      }




      .caption-image{
        top:100px;
        right:200px;
        border-radius: 5px;
        z-index: 20;
        color:white;
        background-color: blue;
      }

          .ribbon {
        width: 150px;
        height: 150px;
        overflow: hidden;
        position: relative;
        }
        .ribbon::before,
        .ribbon::after {
        position: absolute;
        z-index: -1;
        content: '';
        display: block;
        border: 5px solid #2980b9;
        }
        .ribbon span {
        position: absolute;
        display: block;
        width: 225px;
        padding: 15px 0;
        background-color: #3498db;
        box-shadow: 0 5px 10px rgba(0,0,0,.1);
        color: #fff;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        text-transform: uppercase;
        text-align: center;
        }

        /* top left*/
        .ribbon-top-left {
        top: -10px;
        left: -10px;
        }
        .ribbon-top-left::before,
        .ribbon-top-left::after {
        border-top-color: transparent;
        border-left-color: transparent;
        }
        .ribbon-top-left::before {
        top: 0;
        right: 0;
        }
        .ribbon-top-left::after {
        bottom: 0;
        left: 0;
        }
        .ribbon-top-left span {
        right: -25px;
        top: 30px;
        transform: rotate(-45deg);
        }

        /* top right*/
        .ribbon-top-right {
        top: -10px;
        right: -10px;
        }
        .ribbon-top-right::before,
        .ribbon-top-right::after {
        border-top-color: transparent;
        border-right-color: transparent;
        }
        .ribbon-top-right::before {
        top: 0;
        left: 0;
        }
        .ribbon-top-right::after {
        bottom: 0;
        right: 0;
        }
        .ribbon-top-right span {
        left: -25px;
        top: 30px;
        transform: rotate(45deg);
        }

        /* bottom left*/
        .ribbon-bottom-left {
        bottom: -10px;
        left: -10px;
        }
        .ribbon-bottom-left::before,
        .ribbon-bottom-left::after {
        border-bottom-color: transparent;
        border-left-color: transparent;
        }
        .ribbon-bottom-left::before {
        bottom: 0;
        right: 0;
        }
        .ribbon-bottom-left::after {
        top: 0;
        left: 0;
        }
        .ribbon-bottom-left span {
        right: -25px;
        bottom: 30px;
        transform: rotate(225deg);
        }

        /* bottom right*/
        .ribbon-bottom-right {
        bottom: -10px;
        right: -10px;
        }
        .ribbon-bottom-right::before,
        .ribbon-bottom-right::after {
        border-bottom-color: transparent;
        border-right-color: transparent;
        }
        .ribbon-bottom-right::before {
        bottom: 0;
        left: 0;
        }
        .ribbon-bottom-right::after {
        top: 0;
        right: 0;
        }
        .ribbon-bottom-right span {
        left: -25px;
        bottom: 30px;
        transform: rotate(-225deg);
        }

    </style>

  </head>
  <body>

    <form class="" action="" method="post">
      @foreach($product as $product)
      <h1 align=center style="width:100%;border-bottom:0.5px solid #8e221a;font-size:40px;letter-spacing: -1px;font-weight:700;padding:8px">{{$product->name}}    |Details </h1>
      @csrf
      <center><div id="alert_main" class="alert alert-success" ></div>
      <div id="alert_fail" class="alert alert-danger" ></div></center>
        <div class="productinfo" style="float:left">
          <div class="">
            <div class="">
              @if($product->promotion>0)
                <div class="ribbon ribbon-top-left"><span style="font-size:18px">%{{$product->promotion}} Promotion</span></div>
              @endif

              @if($product->photo != null)
              <img src="{{asset('img/'.$product->photo)}}" style='border-radius:0 25px 25px 0;height:350px;width:500px;margin-top:'>
              @else
                <center><h2 style="font-size:40px;margin-right: 200px" >No Preview Photo</h2></center>
              @endif
          </div>
          <table border='0' class='table' style="border-bottom:0.5px solid black;padding-bottom:17px">
            <tr>
            </tr>
            <tr>
              <td><h2 style="font-size:30px;font-weight:700">{{$product->name}}</h2></td>
            </tr>
            <tr>
              <td>@if($product->promotion>0)
              @php
                $pr=$product->price;
                $pro=$product->promotion;
                $newprice=$pr-(($pro/100)*$pr);
              @endphp
                Price:  <p style=''><span style="text-decoration: line-through;">${{$product->price}}</span> <span style="width:10px">  </span> <span style="font-weight:600">${{$newprice}}</span><input type="number" id="prodPrice" value="{{$newprice}}" style="display:none">
              @else
                Price:  <p style=''>$ {{$product->price}}<input type="number" id="prodPrice" value="{{$product->price}}" style="display:none">
              @endif    || </td>
              <td><b>{{$product->width}} x {{$product->height}}</b> sized, made from {{$product->type}}.</td>
            </tr>
          </table>
        </div>

          <table>
              <tr>
                <td style="font-size:20px;letter-spacing:-0.6px">{{$product->description}}</td>
              </tr>
              <tr>
                <td><b>By: </b><a href="/userprofile/{{$product->userId}}" style="color:black;font-weight:800;text-decoration:none">{{$product->username}}</td>
              </tr>
              <tr>
                <td><button type="button" id='userid'  value="{{$product->userId}}" style='display:none'> {{$product->userId}}</button></td>
              </tr>
              </table>

            <input type="number" class="quantity" id="quantity" name="quantity" placeholder="Qunatity" min="1" max="{{$product->stock}}" style="margin-top: 20px;width:100px;font-size:15px;padding: 10px;font-weight:600;border-radius:10px;border:0;background-color:rgba(10,10,130,0.1)"/><br>
              <button type="button" class="btnordernow"  name="placeorder" id="placeorder">Add To Cart</button>
              <a class="back" href="{{route('home')}}">Back</a>

          </div>


        <div class="" style="border-bottom:3px solid #8e221a;display:inline-grid;float:right;margin-right:100px">

          <div class="commentdiv" style="">
              <div id="alert" style="border:0.11rem solid white;border-radius:5px;background-color:white;padding:5px;padding:30px">
                <table align='right' style="font-size:22px;" id="tablecomments">
                @php
                  $comments=DB::select("select * from comment where productId=$product->id order by id desc");
                @endphp
                @foreach($comments as $comments)
                  @php
                    $clients=DB::select("select * from client where id=$comments->clientId");
                  @endphp
                  <tr id="comment_{{$comments->id}}"><th style="padding:5px 40px">@foreach($clients as $clients){{$clients->name}}@endforeach</th>
                      <td style="padding:5px 40px">{{$comments->comment}}</td>
                      @foreach($clientId as $c)
                        @if($comments->clientId==$c->id)<td style="border-left:1px solid #aaa"><img src="{{asset('css/icons/bin.png')}}" onclick="deleteComment({{$comments->id}})" style="width:22px;height:26px;cursor:pointer;"></td>@endif
                      @endforeach
                  </tr>
                @endforeach
              </table>
              <table>
                <tr>
                  <th>Add a Comment ...</th>
                </tr>
              <tr>
                <td>
                  <input type="text" style="width:250px" id="comment" name="comment" class="comment" placeholder="Add Comment"/><button type="button" onclick="addComment({{$product->id}})" value="" style="border-radius: 0;cursor:pointer;z-index:10;border-top: 1px;margin-left:-40.5px;background-color:white;font-size:14px;font-weight:900;display:none" class="comment" id="sendComment">⤊</button></input>
                </td>
              </tr>
              </table>
            </div>
        </div>
    <div class="" style="float:right">
      <table id="productrate">
        <tr>
          <th>Rate {{$product->name}}</th>
        </tr>
        <tr>
          <form class="" action="" method="post">

          @csrf
              <div class="rating">
                  <input type="radio" class="ratingrb" name="rating" value="5" id="5"><label for="5">☆</label>
                  <input type="radio" name="rating" class="ratingrb" value="4" id="4"><label for="4">☆</label>
                  <input type="radio" name="rating" class="ratingrb" value="3" id="3"><label for="3">☆</label>
                  <input type="radio" name="rating" class="ratingrb" value="2" id="2"><label for="2">☆</label>
                  <input type="radio" name="rating" class="ratingrb" value="1" id="1"><label for="1">☆</label>
                </div>
        </tr>
        <tr>
          <td id="ratingvalue" style="display:none"></td>
        </tr>
        <tr>
          <div class="" style="display:none">
            @foreach($clientId as $c)
              <button disabled value="{{$c->id}}" id="clientId"></button>
              <button disabled value="{{$product->id}}" id="productId"></button>
            @endforeach
          </div>
        </tr>
      </form>

      </table>

    </div>

  </div>


    <div class="" style="display:none">
      @foreach($clientId as $c)
        <button disabled value="{{$c->id}}" id="clientid"></button>
        <button disabled value="{{$product->id}}" id="productid"></button>
      @endforeach
    </div>
  </form>
  @endforeach
  </body>

</html>
