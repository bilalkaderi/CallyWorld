@php
    $dt=now()->format('Y-m-d H:i:s');
    $user = DB::select("select * from users where email='Auth::user()->email'");
    $id=Auth::user()->id;
    $userprod = DB::select("select * from product where userId=$id");
    $orders = DB::select("select orders.id,orders.productId,orders.clientId,orders.total_price,orders.quantity,orders.status,product.name,product.photo,product.name as pname,product.price,client.name as cname,client.phone from orders
          left join client on orders.clientId=client.id
          left join product on orders.productId=product.id where orders.userId=$id and orders.created_at >= NOW() - INTERVAL 7 DAY");

    $messages=DB::select("select message.message,client.name from message left join client on message.clientId=client.id where message.userId=$id");
    $messNb=DB::table('message')->where('userId','=',$id)->count();
@endphp
  <head>
    <meta charset="utf-8">
    <title>{{Auth::user()->name}}</title>
    <meta name="_token" content="{{csrf_token()}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var loadFile1 = function(event) {
          var image = document.getElementById('profilephoto');
          image.src = "";
          image.src = URL.createObjectURL(event.target.files[0]);
        };
        var loadFile = function(event) {
          var image = document.getElementById('photoView');
          image.src = "";
          image.src = URL.createObjectURL(event.target.files[0]);
        };
        $(document).ready(function(){
          $("#categories").click(function(){
            $("#myModal").fadeIn();
        });
        $(".close").click(function(){
          $("#myModal").fadeOut();
          $("#myModalUpdate").fadeOut();
       });
       $(".closeprofile").click(function(){
         $(".profchart").fadeOut();
      });
       $('#chart').click(function () {
           $(".chart").toggle("slide");
           $('#chart').attr("value","X");
       });
       $('#editprofile').click(function () {
         $(".profchart").toggle("slide");
         window.css("background-color:","black");
       });

       $("#addprod").click(function(e){
         e.preventDefault();
           if($("#name").val().trim() == "" || $("#price").val().trim() == ""
                   || $("#width").val().trim() == "" || $("#height").val().trim() == "")
                   {
                     alert("Please fill all information");
                     return;
                   }
                   $.ajaxSetup({
                       headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                   });

                   var form_data=new FormData();
                   form_data.append("photo",document.getElementById('photo').files[0]);
                   form_data.append("name",$("#name").val());
                   form_data.append("stock",$("#stock").val());
                   form_data.append("type",$("#type").val());
                   form_data.append("delivery_time",$("#delivery_time").val());
                   form_data.append("delivery_date_type",$("#delivery_type").val());
                   form_data.append("price",$("#price").val());
                   form_data.append("height",$("#height").val());
                   form_data.append("width",$("#width").val());
                   form_data.append("description",$("#description").val());

           $.ajax({
               url:"{{ route('addproduct') }}",
               method:'POST',
               data:form_data,
               contentType: false, //multipart/form-data
               processData: false,
               success: function(output){
                   $("#myModal").fadeOut();
                   $("#productstable").append(output.row);
                   $("#name").val("");
                   $("#price").val("");
                   $("#stock").val("");
                   $("#height").val("");
                   $("#width").val("");
                   $("#description").val("");
              }
           });
       });

       $("#updateprod").click(function(e){
         e.preventDefault();
         $.ajaxSetup({
             headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
         });

         var form_data=new FormData();
         form_data.append("id",$("#theidU").val());
         form_data.append("photo",document.getElementById('photoU').files[0]);
         form_data.append("name",$("#nameU").val());
         form_data.append("type",$("#typeU").val());
         form_data.append("stock",$("#stockU").val());
         form_data.append("price",$("#priceU").val());
         form_data.append("height",$("#heightU").val());
         form_data.append("width",$("#widthU").val());
         form_data.append("description",$("#descriptionU").val());

           $.ajax({
               url:"{{route('updateProduct')}}",
               method:'POST',
               data:form_data,
               contentType: false, //multipart/form-data
               processData: false,
               success: function(output){
                   $("#myModalUpdate").fadeOut();
                   // $(".alerts").text('');
                   // $(".alerts").append(output.success);
                   // $(".alerts").fadeIn();
                   location.reload();
                 }
       });
       });


     });


     function returnProduct(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
         $.ajax({
             url:"{{route('returnProduct')}}",
             method:'get',
             data: {id:id},
             success: function(output){
                 // $("#user_"+id).fadeOut();
                 $("#myModalUpdate").fadeIn();
                 $("#nameU").val(output.name);
                 $("#typeU").val(output.type);
                 $("#priceU").val(output.price);
                 $("#descriptionU").val(output.description);
                 $("#widthU").val(output.width);
                 $("#heightU").val(output.height);
                 $("#stockU").val(output.stock);
                 $("#photoView").attr("src","img/"+output.photo);
                 $("#theidU").val(output.id);
               }
     });
     }

       function deleteProduct(id){
         $.ajaxSetup({
             headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
         });
           $.ajax({
               url:"{{ route('deleteProduct') }}",
               method:'get',
               data: {id:id},
               success: function(output){
                   $("#box_"+id).fadeOut();
                 }
      });
    }

    function confirmOrder(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"{{ route('confirmOrder') }}",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#confirmorder_"+id).fadeOut();
                $("#unavailable_"+id).fadeOut();
                $("#status_"+id).fadeOut();
                $("#status_"+id).text('');
                $("#status_"+id).append('confirmed');
                $("#status_"+id).fadeIn();
              }
            });
        }

     function cancelOrder(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
         $.ajax({
             url:"{{ route('cancelOrder') }}",
             method:'POST',
             data: {id:id},
             success: function(output){
                 $("#confirmorder_"+id).fadeOut();
                 $("#unavailable_"+id).fadeOut();
                 $("#status_"+id).fadeOut();
                 $("#status_"+id).text('');
                 $("#status_"+id).append('Unavailable');
                 $("#status_"+id).fadeIn();
               }
    });
    }


    function updateUser(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var form_data=new FormData();
      form_data.append("id",id);
      form_data.append("photo",document.getElementById('photo').files[0]);
      form_data.append("name",$("#updatename").val());
      form_data.append("phone",$("#updatephone").val());
      form_data.append("aboutme",$("#updateaboutme").val());
        $.ajax({
            url:"{{route('updateUser')}}",
            method:'post',
            data:form_data,
            contentType: false, //multipart/form-data
            processData: false,
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
         url:"{{route('changeUserPassword')}}",
         method:'post',
         data: {id:id,oldpassword:oldpassword,newpassword:newpassword},
         success: function(output){
            $(".alertsProf").text('');
             $(".alertsProf").append(output.success);
             $(".alertsProf").fadeIn();
           }
    });
    }


    </script>
  </head>
  <link rel="stylesheet" href="css/nav.css">
<body>
  <div class="profchart"  style="padding:20px;width:900px;background-color:rgba(240,240,240,0.6)">
     <div class="">
       <span id="span" class="closeprofile" style="position:absolute">&times;</span>
       <div class="">
        <div class='' style=''>
          <div class=''>
            <p><span>Edit profile</span></p>
            <span>
                @foreach($userdata as $u)
                <div class="alertsProf" style="text-align: center;display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
                  <center>
                    <table style='width:100%'>
                    <tr>
                      <td></td>
                      <td></td>
                      <td style="text-align:right" colspan="3" rowspan="5">
                        <label for='photo'><img src="{{asset('profiles/'.$u->photo)}}" class='photo' name='photo' title='Choose photo' style="border-radius:100px;height:300px;width:300px" id='profilephoto'/></label><br>
                        <input type='file' name='photo' style='visibility: hidden' accept='image/*' id='photo' onchange='loadFile1(event)' >
                      </td>
                    </tr>
                    <th>Your name</th><th>Phone number</th>
                    <tr>
                      <td><input id='updatename' class='updatetext' type='text' value='{{$u->name}}' name='clientname' /></td>
                      <td><input id='updatephone' class='updatetext' type='text' value='{{$u->phone}}' name='clientphone' /></td>
                    </tr>
                    <tr>
                      <td colspan="2"><input id='updateaboutme' class='updatetext' type='text' value='{{$u->aboutme}}' style="height:200px;width:90%" placeholder="Add some info about yourself!" name='clientphone' /></td>
                    </tr>
                  </table>
                  </center>
                  <center><button type='button' onclick='updateUser({{$u->id}})' id='buttonupdate' class='buttonupdate'>Save &#10004;</button></center></span>
                </div>
                <div class='info'><p>Do you want to change your password?</p>
                  <span class='month'><input class='updatetext' type='password' value='' id='oldpassword' placeholder='Old Password'/></span>
                  <span class='month'><input type='password' class='updatetext' value='' id='newpassword' placeholder='New Password'/></span>
                  <span class='month'><input type='password' class='updatetext' value='' id='newpassword_confirmation' placeholder='Confirm New Password'/></span>
                  <button type='button' id='changepassword' style="color:white" class='btn btnchange' onclick="changepassword({{$u->id}})">Change Password</button>
                </div>
                <div style="float:right;margin-top:30px">
                  <a href="{{route('signout')}}" value='Log out' name='logout' style="" class='logout'/>Signout</a></center>
                @endforeach
                </div>
            </div>

      </div>
    </div>
</div>

    <div style="margin-top:-10px">
     <header id="header">
         <nav class="nav" id="navbar" style="background-color:rgba(0,0,0,0.05)">
           <div class="logo">
             <button style="border:0;background:transparent;border-radius:5px;font-size:40px" id="logo"><h1 class="logoh1">
               <img src="{{asset('css/icons/pen.png')}}" style="width:44px;height:46px">CallyWorld</h1>
           </button>
         </div>
           <ul class="nav_list" id="navlinkitems" style="margin-top:-110px">
             <li class="nav_item">
               <a href="{{route('home')}}" class="nav_link" id="home">Homepage</a>
             </li>
             <li class="nav_item">
               <a href="#order-section" class="nav_link" id="orders">Orders</a>
             </li>
             <li class="nav_item">
               <a class="nav_link" name="btnaddproduct" id="categories" style="background-color:rgba(0,0,0,0.2);border-radius:6px">Add New Product</a>
             </li>
             <li class="nav_item">
               <a class="nav_link" name="btnaddproduct" id="editprofile" style="background-color:rgba(0,0,0,0.2);border-radius:6px">Edit Profile</a>
             </li>
             @if($u->role == 1)
                 <li class="nav_item">
                   <a href="{{route('admin')}}" class="nav_link" id="" style="background-color:rgba(6,40,120,0.2);border-radius:6px">Administrator Dashboard</a>
                 </li>
            @endif
            </ul>
         </nav>
       </header>
     </div>

       <form method="post" action="">
         <section style="padding-top:100px">
           <input type="button" id="chart" class="dash" value="&#8614;"/>
           <div class="chart">
              <div class="main-container">
               <div class="year-stats">
                  @foreach($userprod as $up)
                      @if($up->soldno > '0')
                        <div class='month-group'>
                         <div class='bar' style='height:{{$up->soldno}}0%' title='{{$up->soldno}}'></div>
                         <p class='month'>{{$up->name}}</p>
                       </div>
                       @endif
                  @endforeach

                       @php $sp=DB::table('product')->where('userId','=',$id)->where('soldno','>','0')->count();@endphp
                       @if($sp == 0)
                      <div class='info'>
                             <p><span>You don't have any sold products yet.</span></p>
                      </div>
                      @endif

                    @php
                      $prodNum=DB::table('product')->where('userId','=',$id)->count();
                      $sales=DB::select("select sum(price*soldno) as sales from product where userId=$id");@endphp
                    </div>
                     <div class='info'>
                       <p><span>You have {{$prodNum}} products shared here.</span></p>
                       <p>Total sales :<span>   $ @foreach($sales as $s){{$s->sales}}@endforeach</span></p>
                     </div>
                     <div class="info">
                       <center><p>In-Stock</p></center><table style="">
                         <tr>
                           <td></td>
                           @foreach($userprod as $us)
                            <td style="color:white;text-align:center;padding:10px;border-bottom:1px solid #eee;">{{$us->name}}<br>{{$us->stock}}</td>
                           @endforeach
                         </tr>
                       </table>
                     </div>
               </div>
             </div>

         <div class="" id="my_products-section" style="padding-top:50px;padding-bottom:100px">
           <center>@if($userprod)
              <table id="productstable"><tr>
                @foreach($userprod as $p)
                  @php
                    $commentsNb=DB::table('comment')->where('productId','=',$p->id)->count();
                  @endphp
                  <td id='box_{{$p->id}}'><div class='box' id='box' style='display:relative'>
                    <div class='front'>
                      @if($p->photo != null && file_exists(public_path()."/img/$p->photo"))
                        <img src="{{asset('img/'.$p->photo)}}" style='width:300px;height:240px;border-radius:20px' alt=''>
                      @else
                        <h2 style="font-size:20px">No Preview Photo</h2>
                      @endif
                    </div>
                    <button type="button" onclick="deleteProduct({{$p->id}})" id="deleteProduct_{{$p->id}}" style='font-weight:800;position:absolute;margin-top:-35px;padding:3px 5px;font-size:24px' class='cancel' name='remove'>&times</button>
                    <div class='back'>
                      <h1 style='font-family:Calibri;color:#618685'>{{$p->name}}</h1>
                      <h5 style='font-family:Calibri;color:#618685;font-family:bold;font-size:20px'>{{$p->description}}</h5>
                      <p style='font-family:Calibri;color:#618685;font-size:20px'>{{$p->price}} $
                      <button type="button" onclick="returnProduct({{$p->id}})" style='margin-left:5px;padding:10px;border:0;background-color:transparent'><img src='img/edit.png' style='border-radius:6px;background-color:lightblue;height:30px;width:33px;opacity:0.5;cursor:pointer;float:right'></button></p>
                      @if($commentsNb>0)
                        <a href='/Comments/{{$p->id}}' class="atop" style=''>View comments</a>
                      @endif
                      </div>
                    </div></td>
                    @if($loop->iteration % 4 == 0)
                      </tr><tr>
                    @endif</center>
                  @endforeach
          @else
              <center><div style='border-radius:4px;height:300px;width:90%'><h2 align='center'>No products.</h2>
                      <p class='art' style='color:black;font-size:24px'>Start releasing your products here.</p></div></center>
            @endif
            </table>
          </div>
        </section>
      </form>

      <div id="myModal" class="modal">
        <span id="span" class="close">&times;</span>
        <center><form class="" action="" method="post">
          {{csrf_field()}}
          <div class="" style="padding-top:40px;background-color:rgba(240,240,250,0.6);border-radius:6px" id="addproduct">
            <table align="center" class="table">
              <caption style="font-weight:900;font-size:26px;color:#ffe;font-family:calibri">New Product's Info</caption>
              <tr>
                <td><input type="text" name="name" value="" placeholder="Name of Product" class="form-control" id="name" required></td>
                   <input type="text" name="theid" value="" style="visibility:hidden" readonly>
                 <td><input type="number" name="price" value="" placeholder="Price $" class="form-control" id="price" required></td>
                 <td><select class="form-control" name="type" id="type" required>
                    @php $cats=DB::select("select * from categories");@endphp
                    @foreach($cats as $cats)
                      <option class="option" value="{{$cats->id}}">{{$cats->name}}</option>
                    @endforeach
                    <option class="optionother" value="3">Other</option>
                  </select>
                 </td>
               </tr>
               <tr>
                <td><input type="number" name="height" value="" placeholder="Height" id="height" class="form-control"></td>
                <td><input type="number" name="width" value="" placeholder="Width" id="width" class="form-control"></td>
                <td><input type="number" name="stock" value="" placeholder="In-Stock" id="stock" class="form-control"></td>
                <tr><td style="font-weight:800;font-size:23px;color:White;font-family:calibri">Delivery Time

                  <select id="delivery_time" class="form-control" style="width:45px">
                    @php
                      for($i=1;$i<=10;$i++){
                        echo"<option value='$i'>$i</option>";
                      }
                    @endphp
                  </select>
                  <select id="delivery_type" class="form-control" style="width:45px">
                    <option value='Day'>Day</option>
                    <option value='Week'>Week</option>
                  </select>
                </td>
              </tr>
              </tr>
              <tr>
                <td>
                   <label for='photo' class='form-label'>Choose photo..</label><input type='file' name='photo' style='visibility:hidden' id='photo' class='form-control'>
                </td>
              </tr>
              <tr>
                <th style="font-weight:800;font-size:23px;color:White;font-family:calibri" >Description</th>
              </tr>
              <tr>
                <td colspan="2"><input type="text" name="description" value="" id="description" placeholder="Describe the product with few words." class="form-control"></td>
                <td><button type="submit" name="addprod" id="addprod" class="form-control" style="cursor:pointer;width:60" >Add</button></td>
             <tr>
            </table>
          </div>
        </form></center>
      </div>

    <section>
      <form class="" action="" method="post">
      <div style="height:100px;background-color:#2C2C2B;">
      </div>
      <center><div class="" id="order-section" style="background-color:#2C2C2B;padding-bottom:100px">
        <div class="userorder">
          <center><span style="background-color:rgb(5,0,0);opacity: 0.8;border-radius:10px;justify-content: center;padding: 20px;width:100%;font-size:22px;font-family:Calibri;text-transform:uppercase;color:#fff">Orders from the last 7 days:</span></center>
        </div>
           <table class='tab'  align=center>
                 @foreach($orders as $o)
                    <tr><td class='tab-td'> <img src="{{asset('img/'.$o->photo)}}" alt='' class='image' id='img'>  </td>
                      <td>Ordered by {{$o->cname}}</td>
                      <td><p style='justify-content:center'>{{$o->name}}<br>{{$o->quantity}} item @if($o->quantity > 1)s, each for ${{$o->price}}<br>Total: ${{$o->total_price}} @else for ${{$o->price}}@endif</p></td>
                      <td id="" align=right style=''>
                        <p style="color:@if($o->status == 'confirmed')
                                      green
                                    @elseif($o->status == 'Sorry but the product you ordered is not available now.')
                                      red
                                    @endif" readonly id="status_{{$o->id}}">

                              @if($o->status == 'Sorry but the product you ordered is not available now.')Unavailable
                              @elseif ($o->status=='confirmed') {{$o->status}}
                              @endif
                          </p>
                       @if($o->status == 'pending')
                       <button type="button" style="border:0" name='confirmorder' id="confirmorder_{{$o->id}}" onclick="confirmOrder({{$o->id}})" class='conf'>Confirm</button>
                         <button type="button" style="border:0" id="unavailable_{{$o->id}}" name='unavailable' onclick="cancelOrder({{$o->id}})" class='cancel'>Unavailable</button></td>
                      @endif
                      <td style="padding-left:50px"> <a href="https://api.whatsapp.com/send?phone=254777225366"><img src="{{asset('css/icons/wa-logo.png')}}" style="width:34px;height:36px"></a></td>

                    </tr>
                  @endforeach

              </table>

              <span>
                <a href="/allOrders" style="font-size:20px;font-family:Calibri;color:black;background-color:white;border-radius:5px;padding:15px;font-weight:800">All Orders</a>
              </span>
          </div></center>
        </form>
      </section>

      <!-- ////////////////////Update -->
      <div id="myModalUpdate" class="modal">
        <span id="spanU" class="close">&times;</span>
        <form class="" action="" method="post">
          {{csrf_field()}}
          <center><div class="" style="padding-top:40px;background-color:white;border-radius:6px" id="updateproduct">
            <table align="center" class="table">
              <caption style="color:#8e221a">Update Product</caption>
              <tr>
                <td><input type="text" value="" placeholder="Name of Product" class="form-control" id="nameU" required></td>
                   <input type="text" id="theidU" value="" style="visibility:hidden" readonly>
                 <td><input type="number" value="" placeholder="Price $" class="form-control" id="priceU" required></td>
                 <td><select class="form-control" id="typeU" required>
                   @php $cat=DB::select("select * from categories");@endphp
                   @foreach($cat as $cat)
                     <option class="option" value="{{$cat->id}}">{{$cat->name}}</option>
                   @endforeach
                   <option class="optionother" value="Other">Other</option>
                  </select>
                 </td>
               </tr>
               <tr>
                <td><input type="number"  value="" placeholder="Height" id="heightU" class="form-control"></td>
                <td><input type="number"  value="" placeholder="Width" id="widthU" class="form-control"></td>
                <td><input type="number"  value="" placeholder="In-Stock" id="stockU" class="form-control"></td>
              </tr>
              <tr>
                <td>
                   <label for='photoU' class='form-label'>
                     <img  class='photo' name='photoView' title='Choose photo' id='photoView' style="width:120px;height:130px;border:0;border-radius:4px"/>
                   </label><input type='file' name='photoU' style='visibility:hidden' id='photoU' onchange='loadFile(event)' class='form-control'>
                </td>
              </tr>
              <tr>
                <th style="color:#8e221a">Description</th>
              </tr>
              <tr>
                <td colspan="2"><input type="text"  value="" id="descriptionU" placeholder="Describe the product with few words." class="form-control"></td>
                <td><button type="submit" id="updateprod" class="form-control" style="cursor:pointer;width:80px" >Update</button></td>
             <tr>
            </table>
          </div></center>
        </form>
      </div>
      <!-- //////////////////////ENDUPDATE///////////////////////////////// -->


     <section class="footer-section">
       <div class="footer">
         <h5>&copy 2022 Cally World. All Rights Reserved &reg</h5>
       </div><input type="radio" style="display:none" name="delivery_type" id="delivery_type" class="">Day
       <label for="delivery_type" style="border-radius:5px;background-color:#eee;padding:8px;">Day</label>
       <input type="radio" name="delivery_type" id="delivery_type2" class="">Week
     </section>


     <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynav"
                aria-controls="mynav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <div class="d-flex">
                    <div class="d-flex align-items-center logo bg-purple">
                        <div class="fab fa-joomla h2 text-white"></div>
                    </div>
                    <div class="ms-3 d-flex flex-column">
                        <div class="h4">Furfection</div>
                        <div class="fs-6">My pet App</div>
                    </div>
                </div>
            </a>
            <div class="collapse navbar-collapse" id="mynav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Categories <span
                                class="fas fa-th-large px-1"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Exclusive</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Collections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <div class="cart bg-purple">
                                <span class="fas fa-shopping-cart text-white"></span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"> <span class="fas fa-user pe-2"></span> Hello Jhon</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-3 my-lg-0 my-md-1">
                <div id="sidebar" class="bg-purple">
                    <div class="h4 text-white">Account</div>
                    <ul>
                        <li class="active">
                            <a href="#" class="text-decoration-none d-flex align-items-start">
                                <div class="fas fa-box pt-2 me-3"></div>
                                <div class="d-flex flex-column">
                                    <div class="link">My Account</div>
                                    <div class="link-desc">View & Manage orders and returns</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none d-flex align-items-start">
                                <div class="fas fa-box-open pt-2 me-3"></div>
                                <div class="d-flex flex-column">
                                    <div class="link">My Orders</div>
                                    <div class="link-desc">View & Manage orders and returns</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none d-flex align-items-start">
                                <div class="far fa-address-book pt-2 me-3"></div>
                                <div class="d-flex flex-column">
                                    <div class="link">Address Book</div>
                                    <div class="link-desc">View & Manage Addresses</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none d-flex align-items-start">
                                <div class="far fa-user pt-2 me-3"></div>
                                <div class="d-flex flex-column">
                                    <div class="link">My Profile</div>
                                    <div class="link-desc">Change your profile details & password</div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none d-flex align-items-start">
                                <div class="fas fa-headset pt-2 me-3"></div>
                                <div class="d-flex flex-column">
                                    <div class="link">Help & Support</div>
                                    <div class="link-desc">Contact Us for help and support</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 my-lg-0 my-1">
                <div id="main-content" class="bg-white border">
                    <div class="d-flex flex-column">
                        <div class="h5">Hello Jhon,</div>
                        <div>Logged in as: someone@gmail.com</div>
                    </div>
                    <div class="d-flex my-4 flex-wrap">
                        <div class="box me-4 my-1 bg-light">
                            <img src="https://www.freepnglogos.com/uploads/box-png/cardboard-box-brown-vector-graphic-pixabay-2.png"
                                alt="">
                            <div class="d-flex align-items-center mt-2">
                                <div class="tag">Orders placed</div>
                                <div class="ms-auto number">10</div>
                            </div>
                        </div>
                        <div class="box me-4 my-1 bg-light">
                            <img src="https://www.freepnglogos.com/uploads/shopping-cart-png/shopping-cart-campus-recreation-university-nebraska-lincoln-30.png"
                                alt="">
                            <div class="d-flex align-items-center mt-2">
                                <div class="tag">Items in Cart</div>
                                <div class="ms-auto number">10</div>
                            </div>
                        </div>
                        <div class="box me-4 my-1 bg-light">
                            <img src="https://www.freepnglogos.com/uploads/love-png/love-png-heart-symbol-wikipedia-11.png"
                                alt="">
                            <div class="d-flex align-items-center mt-2">
                                <div class="tag">Wishlist</div>
                                <div class="ms-auto number">10</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-uppercase">My recent orders</div>
                    <div class="order my-3 bg-light">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="d-flex flex-column justify-content-between order-summary">
                                    <div class="d-flex align-items-center">
                                        <div class="text-uppercase">Order #fur10001</div>
                                        <div class="blue-label ms-auto text-uppercase">paid</div>
                                    </div>
                                    <div class="fs-8">Products #03</div>
                                    <div class="fs-8">22 August, 2020 | 12:05 PM</div>
                                    <div class="rating d-flex align-items-center pt-1">
                                        <img src="https://www.freepnglogos.com/uploads/like-png/like-png-hand-thumb-sign-vector-graphic-pixabay-39.png"
                                            alt=""><span class="px-2">Rating:</span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="far fa-star"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-sm-flex align-items-sm-start justify-content-sm-between">
                                    <div class="status">Status : Delivered</div>
                                    <div class="btn btn-primary text-uppercase">order info</div>
                                </div>
                                <div class="progressbar-track">
                                    <ul class="progressbar">
                                        <li id="step-1" class="text-muted green">
                                            <span class="fas fa-gift"></span>
                                        </li>
                                        <li id="step-2" class="text-muted green">
                                            <span class="fas fa-check"></span>
                                        </li>
                                        <li id="step-3" class="text-muted green">
                                            <span class="fas fa-box"></span>
                                        </li>
                                        <li id="step-4" class="text-muted green">
                                            <span class="fas fa-truck"></span>
                                        </li>
                                        <li id="step-5" class="text-muted green">
                                            <span class="fas fa-box-open"></span>
                                        </li>
                                    </ul>
                                    <div id="tracker"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="order my-3 bg-light">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="d-flex flex-column justify-content-between order-summary">
                                    <div class="d-flex align-items-center">
                                        <div class="text-uppercase">Order #fur10001</div>
                                        <div class="green-label ms-auto text-uppercase">cod</div>
                                    </div>
                                    <div class="fs-8">Products #03</div>
                                    <div class="fs-8">22 August, 2020 | 12:05 PM</div>
                                    <div class="rating d-flex align-items-center pt-1">
                                        <img src="https://www.freepnglogos.com/uploads/like-png/like-png-hand-thumb-sign-vector-graphic-pixabay-39.png"
                                            alt=""><span class="px-2">Rating:</span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="fas fa-star"></span>
                                        <span class="far fa-star"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="d-sm-flex align-items-sm-start justify-content-sm-between">
                                    <div class="status">Status : Delivered</div>
                                    <div class="btn btn-primary text-uppercase">order info</div>
                                </div>
                                <div class="progressbar-track">
                                    <ul class="progressbar">
                                        <li id="step-1" class="text-muted green">
                                            <span class="fas fa-gift"></span>
                                        </li>
                                        <li id="step-2" class="text-muted">
                                            <span class="fas fa-check"></span>
                                        </li>
                                        <li id="step-3" class="text-muted">
                                            <span class="fas fa-box"></span>
                                        </li>
                                        <li id="step-4" class="text-muted">
                                            <span class="fas fa-truck"></span>
                                        </li>
                                        <li id="step-5" class="text-muted">
                                            <span class="fas fa-box-open"></span>
                                        </li>
                                    </ul>
                                    <div id="tracker"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </body>
</html>
