@include('admin.bars.tops')
@php
  $carts=DB::select("select cart.id,cart.items,cart.status,cart.expecting_delivery_date,
  cart.total_amount,cart.status,cart.created_at,cart.updated_at,cart.delivery_response,cart.clientId,cart.payment_method,cart.delivery_hour,cart.delivery_details,
  cart.pref_address,client.name as clientname,client.email as clientemail,client.phone as clientphone,
  deliveryman.name as dname,deliveryman.phone as dphone
  from cart left join client on cart.clientId=client.id
  left join deliveryman on cart.delivery_details=deliveryman.id
  order by cart.id desc");


  $registered=DB::table('cart')->where('status','=','Registered')->count();
  $confirmed=DB::table('cart')->where('status','=','On Preparing')->count();
  $ondelivery=DB::table('cart')->where('status','=','On Delivery')->count();
  $delivered=DB::table('cart')->where('status','=','Delivered')->count();
@endphp

<style media="screen">
.modalUser {
  display: none;
  position: absolute;
  z-index: 20;
  padding-top: 180px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
}
.modal-content {
  margin: auto;
  display: block;
  border-radius: 15px;
  width: 80%;
  max-width: 800px;
}
</style>

<script type="text/javascript">

   $(document).ready(function(){
//     $("#togglemodal").click(function(){
//       $(".modalUser").fadeIn();
//     });
     $("#closemodal").click(function(){
       $(".modalUser").fadeOut();
     });

     $(".closemodal").click(function(){
       $(".modalUser").fadeOut();
     });

     $(".dropdown-toggle").click(function(){
       var id=$(this).val();
       $("#dropdown-menu_"+id).toggle();
     });


     $("#orderType").change(function(){
       var type=$(this).val();
       $('.Registered').fadeOut();$('.Preparing').fadeOut();$('.Delivery').fadeOut();$('.Delivered').fadeOut();$('.Not').fadeOut();
       $('.'+type).fadeIn();
     });



     $("#editProduct").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editProduct").val();
       var name=$("#name").val();
       var type=$("#category").val();
       var price=$("#price").val();
       var description= $("#description").val();
       var soldno=$("#soldno").val();

         $.ajax({
             url:"{{route('editProductAdmin')}}",
             method:'POST',
             data: {id:id,name:name,type:type,price:price,description:description,soldno:soldno},
             success: function(output){
                 // alert(output.success);
                 $(".modalUser").fadeOut();
                 $(".alerts").text('');
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
                 location.reload();
               }
     });
     });


 });

function changeStatus(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var status=$("#change1").val();
  var delDate=$("#delDate").val();
  $.ajax({
    url:"{{route('changeStatus')}}",
      method:'POST',
      data: {id:id, status:status, delDate:delDate},
      success: function(output){
          // $("#user_"+id).fadeOut();
          $("#status_"+id).text('');
          $("#status_"+id).append(output.success);
          location.reload();
          // $("#name").val(output.name);
        }
});
}


function opentimemodel(id){
  $("#delDetails_"+id).fadeIn();
}

function changeStatuss(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var status=$("#change2").val();
  var delDate=$("#delDate").val();
  var time=$("#delTime_"+id).val();
  var delDetails=$("#delManDetails").val();
    $.ajax({
      url:"{{route('changeStatus')}}",
        method:'POST',
        data: {id:id, status:status, delDate:delDate, time:time, delDetails:delDetails},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#status_"+id).text('');
            $("#status_"+id).append(output.success);
            location.reload();
            // $("#name").val(output.name);
          }
});
}
function changeStatusss(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var status=$("#change3").val();
  var delDate=$("#delDate").val();

    $.ajax({
        url:"{{route('changeStatus')}}",
        method:'POST',
        data: {id:id, status:status, delDate:delDate},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#status_"+id).text('');
            $("#status_"+id).append(output.success);
            location.reload();
            // $("#name").val(output.name);
          }
});
}

function changeStatussss(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var status=$("#change4").val();

    $.ajax({
        url:"{{route('changeStatus')}}",
        method:'POST',
        data: {id:id, status:status},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#status_"+id).text('');
            $("#status_"+id).append(output.success);
            location.reload();
            // $("#name").val(output.name);
          }
});
}

function returnProducts(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('returnProducts')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#exampleModal").fadeIn();
            $("#tableproducts").text('');
            $("#tableproducts").append(output.table);
          }
});
}

function checkDeliveryDetails(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('returnProducts')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#checkingDeliveryDetails_"+id).fadeIn();
          }
});
}

// function confirmCart(id){
//   $.ajaxSetup({
//       headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
//   });
//     $.ajax({
//         url:,
//         method:'POST',
//         data: {id:id},
//         success: function(output){
//             // $("#user_"+id).fadeOut();
//             $(".modalUser").fadeIn();
//             $("#tableproducts").append(output.table);
//           }
// });
// }
function alert(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  alert(id);
}




</script>
<body id="page-top">
<div id="wrapper">
  <!-- Sidebar --> @include('admin.bars.sidebar')
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- dashboard -->
<!-- ""''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' -->
    <div class="container-fluid" style="margin-top: 100px;" id="container-user">
      <div class="row mb-3" id="containerFluid">
        <!-- page -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Products</h1>
          </div>
          <hr>
          <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
          <h6>Registered: {{$registered}} - Confirmed: {{$confirmed}} - On The Way: {{$ondelivery}} - Delivered: {{$delivered}}</h6>
          <hr>
          <div style="width:300px" class="box me-4 my-1 bg-light">
              <div class="d-flex align-items mt-2">
                <strong>Order Status:</strong>
                  <select class="form-control" id="orderType">
                    <option value="Registered">Registered</option>
                    <option value="Preparing">On Preparing</option>
                    <option value="Delivery">On Delivery</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Not">Not Delivered</option>
                  </select>
              </div>
          </div>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Client Name</th>
                <th scope="col">Items & Quantity</th>
                <th scope="col">Total Price</th>
                <th scope="col">Payment Method</th>
                <th scope="col">Status</th>
                <th scope="col">Expecting Delivery Time</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($carts as $c)
                @php
                  $data = json_decode($c->items, true);
                @endphp
                <tr id="cart_{{$c->id}}" class="{{$c->status}}">
                <th scope="row">@if($c->status=='Not Delivered') <span style="color:red"><strong>!</strong></span> @endif{{$c->id}}</th>
                <td>{{$c->clientname}}</td>
                <td>
                @foreach($data as $data)
                  @php
                    $id=$data['id'];
                    $prod=DB::select("select * from product where id=$id");
                  @endphp

                    @foreach($prod as $p)
                      {{$p->name}}   :   {{$data['quantity']}}<br>
                    @endforeach
                @endforeach
              </td>

                    <td>${{$c->total_amount}}</td>
                    <td >{{$c->payment_method}}</td>
                    <td id="status_{{$c->id}}" style="border-bottom:2px solid @if($c->status=='On Preparing') red @elseif($c->status=='Not Delivered') red @elseif($c->status=='Registered') black @elseif($c->status=='On Delivery') blue @elseif($c->status=='Delivered') lightgreen @endif ;">
                      @if($c->status=='Not Delivered')
                        <span style="color:red;padding-right:10px;font-weight:700"><strong>!</strong></span>{{$c->status}}
                      @else
                        <span class="dot" style="margin-right:20px;height: 15px;width: 15px;background-color:@if($c->status=='On Preparing') red @elseif($c->status=='Not Delivered') red @elseif($c->status=='Registered') black @elseif($c->status=='On Delivery') blue @elseif($c->status=='Delivered') lightgreen @endif ;border-radius: 50%;display: inline-block;"></span>{{$c->status}}</td>
                      @endif
                    <td>
                      @if($c->status=='Registered')
                        Date: <input type="date" name="" value="" id="delDate" class="form-control" required>
                      @elseif($c->status=='On Delivery')
                        <input type="time" name="" value="{{$c->delivery_hour}}" id="delhour" class="form-control" disabled>
                      @elseif($c->status=='Delivered')
                        Delivered on {{$c->updated_at}}
                      @elseif($c->status=='Not Delivered')
                        XXXXXXXX
                      @else
                        @php
                          $date1 = now()->Format('Y-m-d');
                          $date2 = $c->expecting_delivery_date;
                          $d1=new DateTime($date1);
                          $d2=new DateTime($date2);

                          $days = $d2->diff($d1)->format("%a");
                        @endphp
                        @if($d1>$d2) {{$c->expecting_delivery_date}} Too Late @else @if($c->status=='On Preparing')You have @else In @endif {{$days}} Days @endif
                      @endif
                    </td>
                    <td>
                      <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                      <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" value="{{$c->id}}" type="button" id="dropdownMenu_{{$c->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Adjust Status
                        </button>
                        <div class="dropdown-menu" id="dropdown-menu_{{$c->id}}" aria-labelledby="dropdownMenu{{$c->id}}">
                          <button class="dropdown-item" type="button" id="change1" onclick="changeStatus({{$c->id}})" value="On Preparing">On Preparing</button>
                          <button class="dropdown-item" type="button" id="change2" onclick="opentimemodel({{$c->id}})" value="On Delivery">On Delivery</button>
                          <button class="dropdown-item" type="button" id="change3" onclick="changeStatusss({{$c->id}})" value="Delivered">Delivered</button>
                          <button class="dropdown-item btn btn-danger" style="color:red" type="button" id="change4" onclick="changeStatussss({{$c->id}})" value="Not Delivered">Not Delivered</button>
                        </div>
                        @if($c->status=='On Delivery')
                          <button class="btn btn-light" type="button" onclick="checkDeliveryDetails({{$c->id}})" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Check Details
                            @if($c->delivery_response!='waiting')
                            <span style="z-index:233;background-color:red;color:white;font-size:18px;padding:5px;border-radius:5px;">!!!</span>
                            @endif
                          </button>
                        @else
                          <button class="btn btn-light" type="button" onclick="returnProducts({{$c->id}})" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Check Products
                          </button>
                        @endif
                      </div>
                    </td>
                  </tr>

                  <div class="modalUser" id="delDetails_{{$c->id}}" style="height:40rem" role="" aria-labelledby="" aria-hidden="">
                    <div class="modal-dialog" role="document" style="height:30rem">
                      <div class="modal-content" style="width:40rem;height:auto">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
                        </div>
                        <div class="modal-body">
                          <strong>Time:</strong> <input type="time" name="" value="" id="delTime_{{$c->id}}" class="form-control" required>
                          <strong>Delivery Address:</strong> {{$c->pref_address}}<br>

                          <strong>Driver:</strong><select class="form-control" id="delManDetails">
                            @php
                              $delMen=DB::select("select * from deliveryman");
                            @endphp
                            @foreach($delMen as $d)
                              <option value="{{$d->id}}">{{$d->name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-light" type="button" id="change2" onclick="changeStatuss({{$c->id}})" value="On Delivery">Save</button>
                          <button type="button" class="btn btn-secondary closemodal" id="">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="modalUser" id="checkingDeliveryDetails_{{$c->id}}" style="height:auto" role="" aria-labelledby="" aria-hidden="">
                    <div class="modal-dialog" role="document" style="height:auto">
                      <div class="modal-content" style="width:70rem;height:auto">
                        <div class="modal-header">
                          <h5 class="modal-title" id="">Delivery Details</h5>
                        </div>
                        <div class="modal-body">
                          <div class='card-body'>
                            <div class='row' id='item_$p->id'>
                              <div class='col-lg-5 col-md-6 mb-4 mb-lg-0'>
                                <!-- Data -->
                                @php
                                  $dat = json_decode($c->items, true);
                                @endphp
                                @foreach($dat as $data)
                                  @php
                                    $id=$data['id'];
                                    $prod=DB::select("select * from product where id=$id");
                                  @endphp

                                    @foreach($prod as $p)
                                      <p><strong>{{$p->name}}</strong></p>
                                      <p><strong>Quantity requested: {{$data['quantity']}}</strong>
                                      <br>
                                      <p>Size: {{$p->width}} x {{$p->height}}</p><hr>
                                    @endforeach
                                @endforeach
                                <!-- Data -->
                              </div>

                              <div class='col-lg-4 col-md-6 mb-4 mb-lg-0'>
                                <p class='text-start text-md-center'>
                                  <span style="text-decoration:underline"><strong>Delivery: More Details</strong></span>
                                  @if($c->delivery_response=='waiting')
                                    <p><b>Expected Delivery Time:</b> {{$c->delivery_hour}}</p>
                                  @elseif($c->delivery_response=='Delivered')
                                    <span style="font-size:20px;color:green">{{$c->delivery_response}} </span>
                                  @elseif($c->delivery_response=='Not Delivered')
                                    <span style="font-size:20px;color:red">{{$c->delivery_response}} </span>
                                  @endif
                                    <p><b>Driver:</b></p>
                                      <p>{{$c->dname}}</p>
                                      <p><a href='tel:{{$c->dphone}}'>{{$c->dphone}}</a></p>

                                    <span style="text-decoration:underline"><strong>Client's Details</strong></span>
                                    <p><b>Client Name:</b> {{$c->clientname}}</p>
                                    <p><b>Phone Number:</b> <a href='tel:{{$c->clientphone}}'>{{$c->clientphone}}</a></p><center>
                                  </p>
                                <!-- Price -->
                              </div>
                            </div>

                            <hr class='my-4' />
                            <div class="">
                              <span>
                                <strong>Total:</strong><p>$ {{$c->total_amount}}</p>
                                <strong>Payment:</strong><p>{{$c->payment_method}}</p>
                              </span>
                            </div>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary closemodal" id="">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>

              @endforeach
            </tbody>
          </table>


            </form>
      </div>

      <div class="modalUser" id="exampleModal" style="height:50rem" role="" aria-labelledby="" aria-hidden="">
        <div class="modal-dialog" role="document" style="height:30rem">
          <div class="modal-content" style="width:140rem;height:auto">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Cart Products</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              </button>
            </div>
            <div class="modal-body">
              {{csrf_field()}}
              <table class="table table-sm" id="tableproducts">

              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- Pages -->

  </div>

  <!-- Footer --> @include('admin.bars.footer')
  </div>
</div>

</body>
