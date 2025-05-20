<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Cally World</title>
    <meta name="_token" content="{{csrf_token()}}">
    <script src="css/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/nav.css">
  </head>

<style media="screen">
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
  *{
    font-family: 'Poppins', sans-serif;
  }
  body{
    /* background-color: #ffffff; */
    height: 100%;
    background-image:url(css/1.jpg); background-attachment:fixed;background-size: cover;
  }
  .alert{
    display: none;
    position: relative;
  }

      .ribbon {
    width: 150px;
    height: 150px;
    overflow: hidden;
    position: absolute;
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3-beta1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
  <link rel="stylesheet" href="{{asset('css/user/bootstrap.min.css')}}">

  <script>
      $(document).ready(function(){
          $('#profile').click(function () {
              $(".profchart").toggle("slide");
          });
          $("#closemodalAlert").click(function(){
            $("#modalAlert").fadeOut();
          });
          $("#closetopAlert").click(function(){
            $("#topAlert").fadeOut();
          });
          $('#topinfophoto').click(function () {
            $("#topbody").text('');
            $("#topbody").append('Suppliers with 50 sales, and a rating not less than 3 will compete to be in the top list.');
            $("#topAlert").fadeIn();
          });
          $('#infophoto').click(function () {
            $("#topbody").text('');
            $("#topbody").append('Products with 50 sales, and a rating not less than 3 will compete to be in the top list.');
            $("#topAlert").fadeIn();
          });

          $('#topsalers').click(function () {
            $("#bestsalers").fadeIn('up');
            $("#bestproducts").fadeOut('up');
          });
          $('#topprods').click(function () {
            $("#bestproducts").fadeIn('down');
            $("#bestsalers").fadeOut('down');
          });


          $('#allProducts').click(function () {
            $(".productsdiv").fadeOut();
            // $("#topbody").append('Suppliers with 50 sales, and a rating not less than 3 will compete to be in the top list.');
            $("#all").fadeIn();
          });

          $('.closeprofile').click(function () {
              $(".profchart").fadeOut();
          });

          $("#showfavorites").click(function(){
            $("#favorites").css("display", "block");
        });
        $(".closefav").click(function(){
          $("#favorites").fadeOut();
        });

        $("#search_query").on("keyup",function() {
          var search_query = $("#search_query").val();
          $.ajax({
              type: 'GET',
              url: '/search',
              data: { search_query: search_query },
              success: function(data) {
                $("#searchbox").empty();
                $("#searchbox").append(data.results);
              }
          });
        });

        $("#toggleNavBar").click(function(){
          $(".navbar-collapse").toggle();
        });

        $('#cardNumber').on('input', function() {
            // Remove any existing spaces
            let cardNumber = $(this).val().replace(/\s/g, '');

            // Insert a space after every four characters
            cardNumber = cardNumber.replace(/(\d{4})(?=\d)/g, '$1 ');

            // Update the input value
            $(this).val(cardNumber);
        });


        $("#btnregister").click(function(){
          $("#nameTD").toggle();
          $("#confirmpassTD").toggle();
          $("#addressTD").toggle();
          $("#phoneTD").toggle();
          $(this).toggle();
          $("#btnsignup").toggle();
          $("#btnLogin").toggle();
          $("#caption").text('Sign Up');
          $("#btnlogin").fadeIn();
      });
      $("#btnlogin").click(function(){
        $("#nameTD").toggle();
        $("#addressTD").toggle();
        $("#confirmpassTD").toggle();
        $("#phoneTD").toggle();
        $(this).toggle();
        $("#btnsignup").toggle();
        $("#btnLogin").fadeIn();
        $("#caption").text('Login');
        $("#btnregister").fadeIn();
    });

        /*$("#btnsign").click(function(){
                $(".modal-signup").fadeIn();
            });
            $(".close").click(function(){
              $("#myModal").fadeOut();
           });*/

                    // $("#btnsignup").click(function(e){
                    //   e.preventDefault();
                    //   if($("#name").val().trim() == "" || $("#email").val().trim() == ""
                    //           || $("#password").val().trim() != $("#password_confirmation").val().trim() || $("#phone").val().trim() < 8)
                    //           {
                    //             $(".errorspan").fadeIn();
                    //             return;
                    //           }
                    //       $.ajaxSetup({
                    //           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                    //       });
                    //
                    //     var form_data = new FormData();
                    //     form_data.append("name", $("#name").val());
                    //     form_data.append("email", $("#email").val());
                    //     form_data.append("password", $("#password").val());
                    //     form_data.append("phone", $("#phone").val());
                    //     form_data.append("address", $("#address").val());
                    //     $.ajax({
                    //         url:"{{ route('signup') }}",
                    //         method:'post',
                    //         data:form_data,
                    //         contentType: false, //multipart/form-data
                    //         processData: false,
                    //         success: function(output){
                    //
                    //             //$(".alert").show();
                    //             //$("#clientSignup").append(output.row);
                    //             location.reload();
                    //        }
                    //     });
                    //
                    // });

                    $("#btnLogin").click(function(e){
                      e.preventDefault();
                      if($("#email").val().trim() == "" || $("#password").val().trim() == "")
                              {
                                $(".errorspan").fadeIn();
                                return;
                              }
                          $.ajaxSetup({
                              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                          });

                        var email = $("#email").val();
                        var password = $("#password").val();
                        $.ajax({
                            url:"{{ route('login.client') }}",
                            method:'POST',
                            data:{email:email, password:password},
                            success: function(output){
                                $("#clientalert").text('');
                                $("#clientalert").append(output.success);
                                $("#clientalert").fadeIn();
                                if(output.success == 'Logged in successfully.'){
                                  location.reload();
                                }
                           }
                        });

                    });


        });

        function showAlert(){
          $("#modalAlert").fadeIn();
        }


        function category(id){
          $(".productsdiv").fadeOut();
          $("#cat_"+id).fadeIn();
        }
        function all(){
          $(".productsdiv").fadeOut();
          $("#all").fadeIn();
        }

        function addFav(id){
          $.ajaxSetup({
            headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
          var clientId=$("#clientId").val();
          $.ajax({
            url:"{{route('addFav')}}",
            method:'post',
            data:{id:id,clientId:clientId},
            success: function(output){
              $("#fav"+id).fadeOut();
              $("#fav"+id).css("color",output.color);
              $("#fav"+id).fadeIn();
            }
          });
        }

        function addToCart(id){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
          var price=$("#prodPrice_"+id).val();
            $.ajax({
                url:"{{route('addToCart')}}",
                method:'POST',
                data: {id:id, price:price},
                success: function(output){
                  if(output.success=='Item added!'){
                    $("#added_"+id).fadeOut();
                    $("#added_"+id).fadeIn();
                  }
                  else{
                    $("#modalAlert").fadeIn();

                  }
                  }
        });
        }

        function deleteOrder(id){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
            $.ajax({
                url:"{{route('deleteOrder')}}",
                method:'get',
                data: {id:id},
                success: function(output){
                    $("#order_"+id).fadeOut();
                  }
       });
     }

     function updateClient(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
       var name=$("#updatename").val();
       var phone=$("#updatephone").val();
       var address=$("#updateaddress").val();
       var cardNumber=$("#cardNumber").val();
         $.ajax({
             url:"{{route('updateClient')}}",
             method:'post',
             data: {id:id,name:name,phone:phone,address:address,cardNumber:cardNumber},
             success: function(output){
                $(".alertsProf").text('');
                 $(".alertsProf").append(output.success);
                 $(".alertsProf").fadeIn();
               }
    });
  }

  function changepassword(id){
    if($("#newpassword").val().trim() != $("#newpassword_confirmation").val().trim()){
      $(".alertsProf").text('');
       $(".alertsProf").append('Password confirmation is not correct!');
       $(".alertsProf").fadeIn();
       return;
    }
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    var oldpassword=$("#oldpassword").val();
    var newpassword=$("#newpassword").val();
      $.ajax({
          url:"{{route('changeClientPassword')}}",
          method:'post',
          data: {id:id,oldpassword:oldpassword,newpassword:newpassword},
          success: function(output){
             $(".alertsProf").text('');
              $(".alertsProf").append(output.success);
              $(".alertsProf").fadeIn();
            }
 });
}


    function contact(){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var name=$('#contactName').val();
      var email=$('#contactEmail').val();
      var subject=$('#contactSubject').val();
      var message=$('#contactMessage').val();

      $.ajax({
        url:"{{ route ('contact') }}",
          method:'POST',
          data:{name:name,message:message,email:email,subject:subject},
          success: function(output){
            $("#mailSuccess").fadeOut();
            $("#mailSuccess").fadeIn();
            $('#contactName').val('');
            $('#contactEmail').val('');
            $('#contactSubject').val('');
            $('#contactMessage').val('');
        }
      });
    }
  </script>

  <body>

    <div class="modal" id="modalAlert" tabindex="-1" style="">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Alert</h5>
        </div>
        <div class="modal-body" id="modalbody">
          <div class="alert alert-danger" style="background-color:rgba(220,0,0,0.7);display:block;text-align: center;">Sign in first!</div>
        </div>
        <div class="modal-footer" >
          <button type="button" id="closemodalAlert" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="topAlert" tabindex="-1" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alert</h5>
      </div>
      <div class="modal-body" id="topbody">

      </div>
      <div class="modal-footer" >
        <button type="button" id="closetopAlert" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div>
<header id="header" style="position: fixed;width:100%;z-index: 1000;top:0;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" >
       <div class="container" >
           <button class="navbar-toggler" type="button" id="toggleNavBar" data-bs-toggle="collapse" data-bs-target="#mynav"
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
                       <div class="fs-6">Homepage</div>
                   </div>
               </div>
           </a>
           <div class="collapse navbar-collapse" id="mynav">
               <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="font-size:18px">
                 @if($user)
                   <li class="nav-item">
                       <a class="nav-link active" aria-current="page" href="{{route('user')}}">My Account
                         <span class="fas fa-th-large px-1"></span></a>
                   </li>
                  @endif
                   <li class="nav-item">
                       <a class="nav-link" id="profile" style="cursor:pointer">Update Account</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" id="showfavorites" style="cursor:pointer">Wishlist &#x2764;</a>
                   </li>
                   <li class="nav-item">
                     <a class="nav-link" @if(session()->has('clientemail')) href="{{route('cart')}}" @else onclick="showAlert()" @endif>Cart</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="{{route('register-user')}}" style="cursor:default"><span class="fas fa-user pe-2"></span>Register/Sign In</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="{{route('explorevideos')}}" style="cursor:default">Watch</a>
                   </li>
                   <li class="nav_item" style="">
                     <input class="form-control " type="text" id="search_query" value="" data-bs-toggle="collapse" data-bs-target="#mynav"
                         aria-controls="mynav" aria-expanded="true" aria-label="Toggle navigation" placeholder="Search">
                     <div class="searchbox" id="searchbox" style="height:auto;position:relative">

                     </div>
                   </li>
               </ul>
           </div>
       </div>
   </nav>
 </header>
</div>

    <section>
          <div class="profchart"  style="z-index:2040;">
             <div class="">
               <span id="span" class="closeprofile" style="position:absolute;">&times;</span>
               <div class="">
                <div class='' style=''>
                  <div class=''>
                    <p><span>Edit Account</span></p>
                    <span>
                      @if($client)
                        @foreach($client as $c)
                        @if($c->status=='verified')
                        <div class="alertsProf" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
                        <table style='height:100%'>
                          <th>My Name</th><th>Phone number</th>
                          <tr>
                            <td><input id='updatename' class='updatetext' type='text' value='{{$c->name}}' name='clientname' /></td>
                            <td><input id='updatephone' class='updatetext' type='text' value='{{$c->phone}}' name='clientphone' /></td>
                          </tr>
                          <tr>
                            <td><input type="text" value="{{$c->id}}" id="clientId" style="display:none"></td>
                          </tr>
                          <tr>
                            <td>My Address</td>
                            <td><input id='updateaddress' class='updatetext' type='text' value='{{$c->address}}' name='clientaddress' /></td>
                          </tr>
                          <tr>
                            <th>Card Number</th>
                            <td>
                              <input type="text" class="form-control" id="cardNumber" maxlength="19" value="{{$c->card_number}}" />
                            </td>
                          </tr>
                        </table>
                        <center><button type='button' onclick='updateClient({{$c->id}})' id='buttonupdate' class='buttonupdate'>&#10004;</button></center></span>
                        <center><div class="" style="padding:20px">
                        </div></center>
                      </div>
                      <div class='info'><p>Do you want to change your password?</p>
                        <span class='month'><input class='updatetext' type='password' value='' id='oldpassword' placeholder='Old Password'/></span>
                        <span class='month'><input type='password' class='updatetext' value='' id='newpassword' placeholder='New Password'/></span><br/>
                        <span class='month'><input type='password' class='updatetext' value='' id='newpassword_confirmation' placeholder='Confirm New Password'/></span>
                        <button type='button' id='changepassword' class='btn btnchange' onclick='changepassword({{$c->id}})'>Change password</button>
                      </div>
                      <a href="{{ route('logout') }}" value='Log out' name='logout' class='logout'/>Signout</a></center>
                        @else
                          <div class="alert alert-danger" style="background-color: rgba(120,0,0,0.8);display:block;margin-top:60px">
                            <strong>Your email isn't verified, re-check for an email with verification link.</strong>
                          </div>
                        @endif
                        @endforeach
                      </div>
                      @else
                      <div class="alert alert-danger" style="display:block;margin-top:60px;background-color: rgba(120,0,0,0.8);">
                        <strong>Sign up now to fully access our site!</strong>
                      </div>
                      @endif
                    </div>
                    @if($user)
                      @foreach($user as $u)
                      <div style="margin-top:20px">
                        <a href="{{route('user')}}"><img src="{{asset('profiles/'.$u->photo)}}" style='width:60px;height:70px;border-radius:50px' ></a>
                      @endforeach
                    @endif


            </div>
          </div>
          </div>
      </section>
          <div id="favorites" class="modal">
              <span id="span" class="closefav">&times;</span>
              <div class="favclass">
                <span><div>
                  @if($client)
                    @php
                      $favorites=DB::select("select favorites.id,favorites.productId,product.name,product.photo from favorites
                          left join product on favorites.productId=product.id where favorites.clientId=$c->id");
                    @endphp
                  @else
                    @php $favorites=''; @endphp
                  @endif
                  <table id="tableoffav"><tr>
                    @if($favorites)
                      @foreach($favorites as $f)
                        <td>
                          <div class='fav_container'>
                              <img src="{{asset('img/'.$f->photo)}}" class='favimg' style="border-radius:4px"/>
                              <span id='favspan' class='favimgtitle'>
                                <a href="product/{{$f->productId}}" style='font-size:22px;color:#eed;font-weight:900' id='iname'><strong>View</strong></a></span>
                              <span id='favspan' class='line topline'></span>
                              <span id='favspan' class='line leftline'></span>
                          </div></td></a>
                          @endforeach
                        @endif
                    </tr><tr>
              </table></div>
            </span>
          </div>
      </div>

      <form class="" action="" method="post">
        <section class="homesection" style="">
          <div class="homeDiv" id="home-section" style="">
            <center>
              <div class="glass" style="">
                <h2 class="h2" style="text-align:left;font-style:italic">Calligraphy</h2>
                <img src="{{('css/home-gif.gif')}}" class="gif" style="float:right;border-radius:5px;" alt="">
                <p class="art" style="text-align:left">Calligraphy is an art form that uses ink and a brush to express the very souls of words on paper. <strong style="font-size:14px">Kaoru Kakogawa</strong></p>
                <a href="#cat-section" value='Get Started' class='btn-light nav-link btnHome' >Let's take a look</a>
              </div>
            </center>
          </div>
        <div style="height:10px;background-color:#333"></div>
      </section>
    </form>
@if(session()->has('alert'))
  @include('layouts.alert');
@endif

    <section class="service-section">
      <div class="services" id="services" style="padding-top:120px;padding-bottom:120px;background-color:white">
        <!-- <hr style="width:200px;margin-top:50px;float:left;border-color:#bba174;background-color:#bba174;Height:0.2px"> -->
        <div class="" style="border-bottom: 2px solid #eee;">
          <p class="benefits">Benefits using our services</p>
        </div>
      <center><div>
        <div class="ser" >
          <div class="icon">
            <h3 style="font-weight:700;color:#444;"><img src="{{asset('css/icons/package.png')}}" style="width:35px;height:38px"><br> Faster Delivery</h4>
        </div>
        <p style="font-size:18px;">Faster delivery is the key of success. We provide you with your order(s) within few days, all over Lebanon!</p>
      </div>
      <div class="ser" >
        <div class="icon">
          <h3 style="font-weight:700;color:#444;"><img src="{{asset('css/icons/package.png')}}" style="width:35px;height:38px"><br> Variety of Products</h4>
        </div>
        <p style="font-size:18px;">We have a big base of suppliers that release their work for many clients, which increase their possibilities of choice.</p>
      </div>
      <div class="ser">
        <h3 style="font-weight:700;color:#444;"><img src="{{asset('css/icons/online.png')}}" style="width:35px;height:38px"><br> High Possibility of Selling</h4>
        <p style="font-size:18px;">Suppliers can easily make their arts reach high number of clients, due to that it will increase the potential selling numbers.</p>
      </div>
    </div>
  </div>
    </center>
    </section>

    <section id="best">
      <script type="text/javascript">

      </script>
      <nav class="navbar navbar-expand-lg navbar-light bg-light" >
         <div class="container" >
             <div class="collapse navbar-collapse" id="mynav">
                 <ul class="navbar-nav ms-auto mb-2 mb-lg-0" style="font-size:18px">
                   <li class="nav-item">
                     <a class="nav-link active" id="topprods" style="cursor:pointer">Top Products</a>
                   </li>
                     <li class="nav-item">
                         <a class="nav-link active" id="topsalers" style="cursor:pointer">Top Salers</a>
                     </li>
                 </ul>
             </div>
         </div>
     </nav>
<div class="" style="">

      <div class="bestproducts" id="bestproducts" style="background-color:rgba(223,223,235,0.9);padding:20px;border-radius: 5px;margin-bottom:;margin-top:0;">
        <div class="pro" style="border-bottom: 2px solid #daa;">Top products</div><img id="infophoto" src="{{asset('css/icons/info.png')}}" style="cursor:pointer;width:34px;height:36px">
        <center><div style="padding-top:70px;padding-bottom:80px">
          @php
              $top = DB::table('product')
            ->select('product.id','product.name', DB::raw('SUM(productsrate.rate) / COUNT(productsrate.productId) as rating'))
            ->join('productsrate', 'product.id', '=', 'productsrate.productId')
            ->groupBy('product.id', 'product.name')
            ->havingRaw('SUM(productsrate.rate) / COUNT(productsrate.productId) > 3')
            ->orderBy('soldno', 'desc')
            ->orderBy('rating', 'desc')
            ->whereRaw('product.soldno+product.sales_after_promotion > 49')
            ->take(4)
            ->get();
          @endphp
            @foreach($top as $t)
              @php
                $pro=DB::table('product')
                  ->select('product.id','product.name','product.photo','product.description','product.price','product.promotion')
                  ->where('id','=',$t->id)
                  ->get();
              @endphp
              @foreach($pro as $pro)
                <div class='top'>
                  @if($pro->promotion>0)
                    <div class="ribbon ribbon-top-right"><span style="z-index:233">%{{$pro->promotion}} Promotion</span></div>
                  @endif
                  <img src="{{asset('img/'.$pro->photo)}}" alt=''>
                  <div class='con-text'>
                    <h4>{{$pro->name}}</h4>
                    <p>{{$pro->description}}.<br></p>
                    @if($pro->promotion>0)
                    @php
                      $pr=$pro->price;
                      $prom=$pro->promotion;
                      $newprice=$pr-(($prom/100)*$pr);
                    @endphp
                      <p style=''><span style="color:red;text-decoration: line-through">${{$pro->price}}</span> <span style="font-weight:700">${{$newprice}}</span></p>
                    @else
                      <p style='color:#fff;font-weight:600'>$ {{$pro->price}}</p>
                    @endif<br>
                    <p><a href='/product/{{$pro->id}}' class='atop'>See More</a></p>
                  </div>
                  </div>
                  @endforeach
              @endforeach
       </div></center>
      </div>

      <div class="topsalerss" id="bestsalers" style="background-color:rgba(255,255,255,0.93);padding:20px;display:none">
        <div class="pro" style="border-bottom: 2px solid #daa;">Top Salers</div><img id="topinfophoto" src="{{asset('css/icons/info.png')}}" style="cursor:pointer;width:34px;height:36px">
        <center><div style="padding-top:70px;padding-bottom:80px">
          @php
              $top = DB::table('users')
            ->select('users.id','users.name', DB::raw('SUM(rate.rate) / COUNT(rate.userId) as rating'))
            ->join('rate', 'users.id', '=', 'rate.userId')
            ->groupBy('users.id', 'users.name')
            ->havingRaw('SUM(rate.rate) / COUNT(rate.userId) > 3')
            ->orderBy('nbOfSales', 'desc')
            ->orderBy('rating', 'desc')
            ->where('nbOfSales', '>','30')
            ->take(4)
            ->get();
          @endphp
            @foreach($top as $t)
            @php
              $pro=DB::table('users')
                ->select('users.id','users.name','users.photo','users.aboutme','users.nbOfSales')
                ->where('id','=',$t->id)
                ->get();
              $ratings=round($t->rating,'1');
            @endphp
            @foreach($pro as $pro)
              <div class='top'>
                <img src="{{asset('profiles/'.$pro->photo)}}" alt=''>
                <div class="ribbon ribbon-top-right"><span style="z-index:233">{{$ratings}}</span></div>
                <div class='con-text'>
                  <h4>{{$pro->name}}</h4>
                  <p>{{$pro->aboutme}}.<br>{{$pro->nbOfSales}} sold products<br>
                  <a href='/userprofile/{{$pro->id}}' class='atop'>View Profile</a></p>
                </div>
                </div>
                @endforeach
            @endforeach
       </div></center>
      </div>
    </div>
    </section>


    <form class="" action="" method="post">
      @csrf
      <section class="cat-section" id="cat-section">
        <div class="section" id="cat-section" style="background-color:rgb(245,245,255);padding:100px 0 100px 0" >

          <center><div class="pro" style="border-bottom: 2px solid #daa;margin-left:20px;">Products</div><table class="nav-cat" id="navbar" border="0" align="center" >
            <tr align="center" class="nav_list-cat" id="navlinkitems">
              <td class="nav_item-cat">
                <button type="button"  class="nav_link-cat" style="font-weight:800;" id="allProducts">All</button>
              </td>
              @foreach($categories as $cat)
                <td class="nav_item-cat">
                  <button type="button" onclick="category({{$cat->id}})" class="nav_link-cat" style="font-weight:600;" value="{{$cat->id}}">{{$cat->name}}</button>
                </td>
              @endforeach
              </tr>
          </table>

        <div class="productsdiv" id="all" style="display:block">
          <center>
          <table align="center"><tr>
              @foreach($products as $p)
                <td name="products" class="{{$cat->id}}"><div class='box'>
                  <div class='front' style='border-radius:50px'>
                    @if($p->promotion>0)
                      <div class="ribbon ribbon-top-right"><span>%{{$p->promotion}} Promotion</span></div>
                    @endif
                    @if($p->photo !=null && file_exists(public_path()."/img/$p->photo"))
                      <img src="{{asset('img/'.$p->photo)}}" class="photoOfProduct" style='' alt='' id="">
                    @endif
                  </div>
                  <div class='back' >
                    <br>
                    <a href="product/{{$p->id}}" style='font-size:30px;color:#8e221a;padding:0 0 0 0;font-style:italic' id='iname'><b>{{$p->name}}</b></a>
                    <br>
                    <p><b>Size:</b> {{$p->height}} x {{$p->width}}<br></p>
                    @if($p->promotion>0)
                    @php
                      $pr=$p->price;
                      $pro=$p->promotion;
                      $newprice=$pr-(($pro/100)*$pr);
                    @endphp
                      <p style='color:#618685;'><span style="text-decoration: line-through;">${{$p->price}}</span> <span style="width:10px">  </span> <span style="font-weight:600">${{$newprice}}</span> <input type="number" id="prodPrice_{{$p->id}}" value="{{$newprice}}" style="display:none">
                    @else
                      <p style='color:#618685;'>$ {{$p->price}}<input type="number" id="prodPrice_{{$p->id}}" value="{{$p->price}}" style="display:none">
                    @endif
                    @if($client)
                      @php
                        $fav=DB::table('favorites')->where('productId','=',$p->id)->where('clientId','=',$c->id)->first();
                      @endphp
                      <button type="button" class="fav" onclick="addFav({{$p->id}})" id="fav{{$p->id}}" style="@if($fav!=null)color:red @else color:#ddc @endif" value="">&#x2764;</button>
                    @else
                      @php $fav=''; @endphp
                    @endif
                  </div>
                </div>
                <img id="added_{{$p->id}}" src="{{asset('css/icons/check-mark.png')}}" style="display:none;margin-top: -20px;float:left;margin-left: 50px;width:34px;height:36px"><img onclick="addToCart({{$p->id}})" src="{{asset('css/icons/addcart.png')}}" style="margin-top: -20px;float:right;margin-right: 50px;cursor:pointer;width:34px;height:36px"></td>
                  @if($loop->iteration % 4 == 0)
                    </tr><tr>
                  @endif
                @endforeach
              </table></center>
            </div>

            @foreach($categories as $cat)
              @php
                $products=DB::select("select * from product where categoryId=$cat->id and stock>0");
              @endphp
              <div class="productsdiv" id="cat_{{$cat->id}}" style="display:none">
                <center>
                <table align="center"><tr>
                    @foreach($products as $p)
                      <td name="products"><div class='box'>
                        <div class='front' style='border-radius:50px'>
                          @if($p->promotion>0)
                            <div class="ribbon ribbon-top-right"><span>%{{$p->promotion}} Promotion</span></div>
                          @endif
                          @if($p->photo !=null && file_exists(public_path()."/img/$p->photo"))
                            <img src="{{asset('img/'.$p->photo)}}" class="photoOfProduct" alt='' id="">
                          @endif
                        </div>
                        <div class='back'>
                          <br>
                          <a href="product/{{$p->id}}" style='font-size:30px;color:#8e221a;padding:0 0 0 0;font-style:italic' id='iname'><b>{{$p->name}}</b></a>
                          <br>
                          <p><b>Size:</b> {{$p->height}} x {{$p->width}}<br></p>
                          @if($p->promotion>0)
                          @php
                            $pr=$p->price;
                            $pro=$p->promotion;
                            $newprice=$pr-(($pro/100)*$pr);
                          @endphp
                            <p style='color:#618685;'><span style="text-decoration: line-through;">${{$p->price}}</span> <span style="width:10px">  </span> <span style="font-weight:600">${{$newprice}}</span>
                          @else
                            <p style='color:#618685;'>$ {{$p->price}}
                          @endif
                        </div>
                      </div>
                      <img id="added_{{$p->id}}" src="{{asset('css/icons/check-mark.png')}}" style="display:none;margin-top: -20px;float:left;margin-left: 50px;width:34px;height:36px"><img id="addtocart" src="{{asset('css/icons/addcart.png')}}" style="margin-top: -20px;float:right;margin-right: 50px;cursor:pointer;width:34px;height:36px"></td>
                        @if($loop->iteration % 4 == 0)
                          </tr><tr>
                        @endif
                      @endforeach
                    </table></center>
                  </div>
            @endforeach
      </section>
    </form>

      <section id="join-section" style="margin:30px;">
          <div class="join-section" style="border:1px solid #8e221a;background-color:white;border-radius:5px">
            <center>
              <span style='font-size:35px;font-weight:700;letter-spacing:;border-bottom: 2px solid #aac;'>Subscribe here</span></center>

        <table style="width:" align=left>
            <tr>
              <td><img src="{{asset('css/icons/join-us.jpeg')}}" class="image-joinus" style="opacity:0.8"></td>
            </tr>
          </table>

          <table  style="margin-right:30px">
            <tr>
              <td>
                <div class="" style="">
                  <center>
                  <div class="client" style="">
                    @if(session()->has('clientregistered'))
                    <div class="alert alert-success" style="width:40%;">
                      {{ session()->get('clientregistered') }}
                    </div>
                    @endif
                      @if($client == null)
                          <div id="myModal" class="">
                            <form class="" action="{{route('signup')}}" method="POST">
                              @csrf
                              <div class="" style="padding-top:5px;background-color:white;border-radius:6px" id="clientSignup">
                                <h2 style="color:#777" id="caption">Sign up</h2>
                                <table align="center" class="">
                                  <tr>
                                    <td id="nameTD"> <input type="text" name="name" value="" placeholder="Your Name" class="form-control" id="name"></td>
                                  </tr>
                                  <tr>
                                     <td><input type="email" name="email" value="" placeholder="Email address" class="form-control" id="email"></td>
                                   </tr>
                                   <tr>
                                    <td ><input type="password" name="password" value="" placeholder="Password" id="password" class="form-control"></td>
                                    <td id="confirmpassTD"><input type="password" name="password_confirmation" value="" placeholder="Confirm Password" id="password_confirmation" class="form-control"></td>
                                  </tr>
                                  <tr>
                                    <td id="phoneTD"><input type="text" name="phone" value="" id="phone" placeholder="Phone Number" class="form-control"></td>
                                 </tr>
                                 <tr>
                                   <td id="addressTD"><input type="text" name="address" value="" id="address" placeholder="Address" class="form-control"></td>
                                </tr>
                                 <tr>
                                   <td><button type="submit" name="signup" id="btnsignup" class="form-control" style="cursor:pointer;width:90px;background-color:#eee" >Sign Up</button>
                                     <button type="button" name="Login" id="btnLogin" class="form-control" style="cursor:pointer;width:90px;background-color:#eee;display:none" >Login</button></td>
                                   <td><button type="button" name="login" id="btnlogin" class="form-control" style="font-weight: 900;cursor:pointer;width:auto;background-color:transparent;border:0" >Already have an account?</button></td>
                                   <td><button type="button" name="register" id="btnregister" class="form-control" style="font-weight: 900;cursor:pointer;width:auto;background-color:transparent;border:0;display:none" >Register with a new account</button></td>
                                 </tr>
                                 <tr>
                                   <div class="alert alert-danger" style="background-color:rgba(0,120,0,0.8)" id="clientalert">

                                  </div>
                                 </tr>
                                </table>
                              </div>
                            </form>
                          </div>
                      </center>
                     @endif
                  </div>
                </div>
            </td>
          </tr>

          <tr>
            <td style="align-items:right">
              <div class="registerInJoinUs" style="margin-bottom:20px;display:inline-grid;">
                <span class="registerspan">
                  Become a part of this site.<br> Join other suppliers and bring your creative ideas.</span><br>
                  <center><a href="{{ route('register-user') }}" class='btn btncreate' style='width:30%;margin-top:10px'  name='btncreate'>Create an account</a></center>
                </div>
              </td>
          </tr>

        </table>
      </div>
    </section>

      <section id="orders-section" style="background-image: url(css/back.jpg);background-size:cover;padding:100px 0 100px 0">
        <section class="ftco-section">
          <div class="container" style="margin-top:20px">
            <div class="row justify-content-center">
              <div class="col-md-6 text-center mb-5">
                <span style='font-size:35px;font-weight:700;letter-spacing:;border-bottom: 2px solid #aac;'>Contact Us</span>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-lg-10">
                <div class="wrapper img" style="background-color:rgb(245,245,255);border-radius:5px">
                  <div class="row">
                    <div class="col-md-9 col-lg-7">
                      <div class="contact-wrap w-100 p-md-5 p-4">
                        <h3 class="mb-4">Get in touch with us</h3>
                        <div id="form-message-warning" class="mb-4"></div>
                        <div id="mailSuccess" class="mb-4" style="display:none;border-bottom:1px solid #aad">
                          <p><strong>Your message was sent, thank you!</strong></p>
                        </div>
                        <form method="POST">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="label" for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="contactName" placeholder="Name">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="label" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="contactEmail" placeholder="Email">
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="label" for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="contactSubject" placeholder="Subject">
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="label" for="#">Message</label>
                                <textarea name="message" class="form-control" id="contactMessage" cols="30" rows="4" placeholder="Message"></textarea>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="contact()">Send Message</button>
                                <div class="submitting"></div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </section>
      </section>


      <section class="footer-section">
        <div class="footer">
          <span> &copy; <script> document.write(new Date().getFullYear()); </script> - Developed by Bilal Kaderi</span>

        </div>
      </section>

  </body>
</html>
