@include('admin.bars.tops')
@php
  $orders=DB::select("select orders.id,orders.productId,orders.cartId,orders.clientId,orders.userId,orders.total_price,orders.quantity,orders.expecting_delivery_date,
          orders.status,orders.created_at,product.id as productid,product.photo,product.name as name,product.price,product.expecting_delivery_time,
          client.name as cname,client.phone,DATE(orders.created_at) + INTERVAL product.expecting_delivery_time DAY as expDelDate from orders
      left join client on orders.clientId=client.id
      left join product on orders.productId=product.id
      order by orders.created_at desc");


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

     $(".dropdown-toggle").click(function(){
       var id=$(this).val();
       $("#dropdown-menu_"+id).toggle();
     });

     $("#orderType").change(function(){
       var type=$(this).val();
       $('.pending').fadeOut();$('.confirmed').fadeOut();$('.Delivered').fadeOut();$('.Packed').fadeOut();
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

 function alertByMail(id){
   $.ajaxSetup({
     headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
   });
   $.ajax({
     url:"{{route('remind')}}",
     method:'POST',
     data: {id:id},
     success: function(output){
       $("#mailSuccess").text('');
       $("#mailSuccess").append(output.success);
       $("#mailSuccess").fadeIn();
     }
   });
 }
function delivered(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  $.ajax({
    url:"{{route('delivered')}}",
      method:'POST',
      data: {id:id},
      success: function(output){
          // $("#user_"+id).fadeOut();
          $("#status_"+id).text('');
          $("#status_"+id).append(output.success);
          location.reload();
          // $("#name").val(output.name);
        }
});
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
            <h1 class="h3 mb-0 text-gray-800">Orders</h1>
          </div>
          <hr>
          <div class="alert alert-success" id="mailSuccess" style="display: none"></div>
          <hr>

          <div style="width:300px" class="box me-4 my-1 bg-light">
              <div class="d-flex align-items mt-2">
                <strong>Order Status:</strong>
                  <select class="form-control" id="orderType">
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="Packed">Packed</option>
                    <option value="Delivered">Delivered</option>
                  </select>
              </div>
          </div>

          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Cart #</th>
                <th scope="col">Product Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total Price</th>
                <th scope="col">Status</th>
                <th scope="col">Expecting Delivery Date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $o)
                <tr id="order_{{$o->id}}" class="{{$o->status}}">
                  <th scope="row">{{$o->id}}</th>
                <th scope="row">{{$o->cartId}}</th>
                <td>{{$o->name}}</td>
                <td>{{$o->quantity}}</td>
                <td>{{$o->total_price}}</td>
                <td>{{$o->status}}</td>
                <td>
                  @if($o->status=='pending')
                    @php
                      $dt = $o->expDelDate;
                      $now=now()->format('Y-m-d');
                    @endphp
                    <strong>@if($dt<$now) <span style="font-size:20px;color:red">!!!</span> @endif Expected Date:</strong> {{$dt}}
                  @elseif($o->status=='confirmed')
                    @php
                      $date1 = now()->Format('Y-m-d');
                      $date2 = $o->expecting_delivery_date;
                      $d1=new DateTime($date1);
                      $d2=new DateTime($date2);

                      $days = $d2->diff($d1)->format("%a");
                    @endphp
                    {{$o->expecting_delivery_date}}, <strong>@if($d1>$d2) from @else in @endif{{$days}} Days</strong>
                  @endif
                </td>
                <td>
                  @if($o->status=='Packed')
                    <button type="button" class="btn btn-success" onclick="delivered({{$o->id}})">Delivered</button>
                  @elseif($o->status=='confirmed')
                    <button type="button" class="btn btn-primary" onclick="alertByMail({{$o->id}})">Alert by Mail</button>
                  @elseif($o->status=='Delivered')
                    <span class="dot" style="margin-right:20px;height: 15px;width: 15px;background-color:lightgreen;border-radius: 50%;display: inline-block;"></span></td>
                  @endif
                  <!-- <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" value="{{$o->id}}" type="button" id="dropdownMenu_{{$o->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Adjust Status
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu_{{$o->id}}" aria-labelledby="dropdownMenu{{$o->id}}">
                      <button class="dropdown-item" type="button" id="change1" onclick="changeStatus({{$o->id}})" value="On Preparing">On Preparing</button>
                      <button class="dropdown-item" type="button" id="change2" onclick="changeStatuss({{$o->id}})" value="On Delivery">On Delivery</button>
                      <button class="dropdown-item" type="button" id="change3" onclick="changeStatusss({{$o->id}})" value="Delivered">Delivered</button>
                    </div>
                    <button class="btn btn-light" type="button" onclick="returnProducts({{$o->id}})" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Check Products
                    </button>
                  </div>
                </td> -->
              </tr>

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
