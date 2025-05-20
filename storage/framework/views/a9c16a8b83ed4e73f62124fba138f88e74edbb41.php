<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
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
?>

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
             url:"<?php echo e(route('editProductAdmin')); ?>",
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
    url:"<?php echo e(route('changeStatus')); ?>",
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
      url:"<?php echo e(route('changeStatus')); ?>",
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
        url:"<?php echo e(route('changeStatus')); ?>",
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
        url:"<?php echo e(route('changeStatus')); ?>",
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
        url:"<?php echo e(route('returnProducts')); ?>",
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
        url:"<?php echo e(route('returnProducts')); ?>",
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
  <!-- Sidebar --> <?php echo $__env->make('admin.bars.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
          <h6>Registered: <?php echo e($registered); ?> - Confirmed: <?php echo e($confirmed); ?> - On The Way: <?php echo e($ondelivery); ?> - Delivered: <?php echo e($delivered); ?></h6>
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
              <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                  $data = json_decode($c->items, true);
                ?>
                <tr id="cart_<?php echo e($c->id); ?>" class="<?php echo e($c->status); ?>">
                <th scope="row"><?php if($c->status=='Not Delivered'): ?> <span style="color:red"><strong>!</strong></span> <?php endif; ?><?php echo e($c->id); ?></th>
                <td><?php echo e($c->clientname); ?></td>
                <td>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                    $id=$data['id'];
                    $prod=DB::select("select * from product where id=$id");
                  ?>

                    <?php $__currentLoopData = $prod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php echo e($p->name); ?>   :   <?php echo e($data['quantity']); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </td>

                    <td>$<?php echo e($c->total_amount); ?></td>
                    <td ><?php echo e($c->payment_method); ?></td>
                    <td id="status_<?php echo e($c->id); ?>" style="border-bottom:2px solid <?php if($c->status=='On Preparing'): ?> red <?php elseif($c->status=='Not Delivered'): ?> red <?php elseif($c->status=='Registered'): ?> black <?php elseif($c->status=='On Delivery'): ?> blue <?php elseif($c->status=='Delivered'): ?> lightgreen <?php endif; ?> ;">
                      <?php if($c->status=='Not Delivered'): ?>
                        <span style="color:red;padding-right:10px;font-weight:700"><strong>!</strong></span><?php echo e($c->status); ?>

                      <?php else: ?>
                        <span class="dot" style="margin-right:20px;height: 15px;width: 15px;background-color:<?php if($c->status=='On Preparing'): ?> red <?php elseif($c->status=='Not Delivered'): ?> red <?php elseif($c->status=='Registered'): ?> black <?php elseif($c->status=='On Delivery'): ?> blue <?php elseif($c->status=='Delivered'): ?> lightgreen <?php endif; ?> ;border-radius: 50%;display: inline-block;"></span><?php echo e($c->status); ?></td>
                      <?php endif; ?>
                    <td>
                      <?php if($c->status=='Registered'): ?>
                        Date: <input type="date" name="" value="" id="delDate" class="form-control" required>
                      <?php elseif($c->status=='On Delivery'): ?>
                        <input type="time" name="" value="<?php echo e($c->delivery_hour); ?>" id="delhour" class="form-control" disabled>
                      <?php elseif($c->status=='Delivered'): ?>
                        Delivered on <?php echo e($c->updated_at); ?>

                      <?php elseif($c->status=='Not Delivered'): ?>
                        XXXXXXXX
                      <?php else: ?>
                        <?php
                          $date1 = now()->Format('Y-m-d');
                          $date2 = $c->expecting_delivery_date;
                          $d1=new DateTime($date1);
                          $d2=new DateTime($date2);

                          $days = $d2->diff($d1)->format("%a");
                        ?>
                        <?php if($d1>$d2): ?> <?php echo e($c->expecting_delivery_date); ?> Too Late <?php else: ?> <?php if($c->status=='On Preparing'): ?>You have <?php else: ?> In <?php endif; ?> <?php echo e($days); ?> Days <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <td>
                      <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                      <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" value="<?php echo e($c->id); ?>" type="button" id="dropdownMenu_<?php echo e($c->id); ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Adjust Status
                        </button>
                        <div class="dropdown-menu" id="dropdown-menu_<?php echo e($c->id); ?>" aria-labelledby="dropdownMenu<?php echo e($c->id); ?>">
                          <button class="dropdown-item" type="button" id="change1" onclick="changeStatus(<?php echo e($c->id); ?>)" value="On Preparing">On Preparing</button>
                          <button class="dropdown-item" type="button" id="change2" onclick="opentimemodel(<?php echo e($c->id); ?>)" value="On Delivery">On Delivery</button>
                          <button class="dropdown-item" type="button" id="change3" onclick="changeStatusss(<?php echo e($c->id); ?>)" value="Delivered">Delivered</button>
                          <button class="dropdown-item btn btn-danger" style="color:red" type="button" id="change4" onclick="changeStatussss(<?php echo e($c->id); ?>)" value="Not Delivered">Not Delivered</button>
                        </div>
                        <?php if($c->status=='On Delivery'): ?>
                          <button class="btn btn-light" type="button" onclick="checkDeliveryDetails(<?php echo e($c->id); ?>)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Check Details
                            <?php if($c->delivery_response!='waiting'): ?>
                            <span style="z-index:233;background-color:red;color:white;font-size:18px;padding:5px;border-radius:5px;">!!!</span>
                            <?php endif; ?>
                          </button>
                        <?php else: ?>
                          <button class="btn btn-light" type="button" onclick="returnProducts(<?php echo e($c->id); ?>)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Check Products
                          </button>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>

                  <div class="modalUser" id="delDetails_<?php echo e($c->id); ?>" style="height:40rem" role="" aria-labelledby="" aria-hidden="">
                    <div class="modal-dialog" role="document" style="height:30rem">
                      <div class="modal-content" style="width:40rem;height:auto">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
                        </div>
                        <div class="modal-body">
                          <strong>Time:</strong> <input type="time" name="" value="" id="delTime_<?php echo e($c->id); ?>" class="form-control" required>
                          <strong>Delivery Address:</strong> <?php echo e($c->pref_address); ?><br>

                          <strong>Driver:</strong><select class="form-control" id="delManDetails">
                            <?php
                              $delMen=DB::select("select * from deliveryman");
                            ?>
                            <?php $__currentLoopData = $delMen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-light" type="button" id="change2" onclick="changeStatuss(<?php echo e($c->id); ?>)" value="On Delivery">Save</button>
                          <button type="button" class="btn btn-secondary closemodal" id="">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="modalUser" id="checkingDeliveryDetails_<?php echo e($c->id); ?>" style="height:auto" role="" aria-labelledby="" aria-hidden="">
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
                                <?php
                                  $dat = json_decode($c->items, true);
                                ?>
                                <?php $__currentLoopData = $dat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <?php
                                    $id=$data['id'];
                                    $prod=DB::select("select * from product where id=$id");
                                  ?>

                                    <?php $__currentLoopData = $prod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <p><strong><?php echo e($p->name); ?></strong></p>
                                      <p><strong>Quantity requested: <?php echo e($data['quantity']); ?></strong>
                                      <br>
                                      <p>Size: <?php echo e($p->width); ?> x <?php echo e($p->height); ?></p><hr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!-- Data -->
                              </div>

                              <div class='col-lg-4 col-md-6 mb-4 mb-lg-0'>
                                <p class='text-start text-md-center'>
                                  <span style="text-decoration:underline"><strong>Delivery: More Details</strong></span>
                                  <?php if($c->delivery_response=='waiting'): ?>
                                    <p><b>Expected Delivery Time:</b> <?php echo e($c->delivery_hour); ?></p>
                                  <?php elseif($c->delivery_response=='Delivered'): ?>
                                    <span style="font-size:20px;color:green"><?php echo e($c->delivery_response); ?> </span>
                                  <?php elseif($c->delivery_response=='Not Delivered'): ?>
                                    <span style="font-size:20px;color:red"><?php echo e($c->delivery_response); ?> </span>
                                  <?php endif; ?>
                                    <p><b>Driver:</b></p>
                                      <p><?php echo e($c->dname); ?></p>
                                      <p><a href='tel:<?php echo e($c->dphone); ?>'><?php echo e($c->dphone); ?></a></p>

                                    <span style="text-decoration:underline"><strong>Client's Details</strong></span>
                                    <p><b>Client Name:</b> <?php echo e($c->clientname); ?></p>
                                    <p><b>Phone Number:</b> <a href='tel:<?php echo e($c->clientphone); ?>'><?php echo e($c->clientphone); ?></a></p><center>
                                  </p>
                                <!-- Price -->
                              </div>
                            </div>

                            <hr class='my-4' />
                            <div class="">
                              <span>
                                <strong>Total:</strong><p>$ <?php echo e($c->total_amount); ?></p>
                                <strong>Payment:</strong><p><?php echo e($c->payment_method); ?></p>
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

              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
              <?php echo e(csrf_field()); ?>

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

  <!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/cart.blade.php ENDPATH**/ ?>