<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $id=Auth::user()->id;
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
  border-radius: 5px;
  border:0;
  background-color: #eef;
  width: 100%;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  $("#closemodal").click(function(){
    $(".modalUser").fadeOut();
    });

  });

function confirmOrder(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var delTime=$("#delTime").val();

    $.ajax({
        url:"<?php echo e(route('confirmOrder')); ?>",
        method:'POST',
        data: {id:id,delTime:delTime},
        success: function(output){
            $("#confirmorder_"+id).fadeOut();
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
         url:"<?php echo e(route('cancelOrder')); ?>",
         method:'POST',
         data: {id:id},
         success: function(output){
             $("#confirmorder_"+id).fadeOut();
             $("#status"+id).fadeOut();
             $("#status_"+id).text('');
             $("#status_"+id).append('Unavailable');
             $("#status_"+id).fadeIn();
           }
});
}
function openModal(){
  $(".modalUser").fadeIn();
}


        function ready(id){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
            $.ajax({
                url:"<?php echo e(route('ready')); ?>",
                method:'POST',
                data: {id:id},
                success: function(output){
                  $("#ready_"+id).fadeOut();
                  $("#status_"+id).fadeOut();
                  $("#status_"+id).text('');
                  $("#status_"+id).append('Packed');
                  $("#status_"+id).fadeIn();
                  }
                });
            }

</script>
<form class="asdsa" action="" method="post">

<div id="wrapper">
  <!-- Sidebar -->
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- dashboard -->
<!-- ""''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' -->
    <div class="container-fluid" style="margin-top: 100px;" id="container-user">
      <div class="row mb-3" id="containerFluid">
        <!-- page -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
          <h1 class="h3 mb-0 text-gray-800">All Orders</h1>
        </div>
        <hr>
        <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
        <hr>
        <h6>New <?php echo e($ordersnb); ?> Pending Orders</h6>

        <table class="table table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name </th>
              <th scope="col">Client</th>
              <th scope="col">Price</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total Price</th>
              <th scope="col">Status</th>
              <th scope="col">Date</th>
              <th scope="col">Expecting Delivery Date</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if($orders): ?>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr id="order_<?php echo e($o->id); ?>">
                <th scope="row">#</th>
                <td><a href="product/<?php echo e($o->productid); ?>"><?php echo e($o->pname); ?></a></td>
                <td><?php echo e($o->cname); ?></td>
                <td><?php echo e($o->price); ?></td>
                <td><?php echo e($o->quantity); ?></td>
                <td><?php echo e($o->total_price); ?></td>
                <td id="status_<?php echo e($o->id); ?>"><?php echo e($o->status); ?></td>
                <td><?php echo e($o->created_at); ?></td>
                <td>
                  <?php if($o->status =='pending'): ?>
                    <p class="text-dark"><select class="form-control" id="delTime" name="">
                      <?php
                        for($i=1;$i<=10;$i++){
                          if($i==$o->expecting_delivery_date){
                            $s='selected';
                          }
                          else{
                            $s='';
                          }
                          echo"<option value='$i' $s>$i</option>";
                        }
                      ?>
                    </select> Days</p>
                  <?php else: ?>
                    <p class="text-dark">The order should be ready in <?php echo e($o->expecting_delivery_date); ?> Days</p>
                  <?php endif; ?>
                </td>
                <td id="action_<?php echo e($o->id); ?>" class="client_<?php echo e($o->clientId); ?>">
                    <?php if($o->status =='pending'): ?>
                      <button type="button" class="btn btn-primary"  onclick="confirmOrder(<?php echo e($o->id); ?>)" id="confirmorder_<?php echo e($o->id); ?>">Confirm Order</button>
                    <?php elseif($o->status =='confirmed'): ?>
                      <button type="button" onclick="ready(<?php echo e($o->id); ?>)" id="ready_<?php echo e($o->id); ?>" class="btn btn-secondary">Ready to Deliver</button>
                    <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
          </tbody>
        </table>
        <form class="dsfsd" action="" method="post">
        <!-- Modal -->
        <div class="modalUser" id="exampleModal" style="height:40rem;" role="" aria-labelledby="" aria-hidden="">
          <div class="modal-dialog" role="document" style="height:18rem;border-radius: 6px;padding:20px;background-color:#fffb">
            <div class="modal-content" style="width:25rem;height:4rem">
              <div class="modal-header" >
                <h5 class="modal-title" id="exampleModalLabel">Why are you reporting this client?</h5>
              </div>
              <div class="modal-body">
                <?php echo e(csrf_field()); ?>

                <textarea id="reportmessage" class="form-control" cols="80"></textarea>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="closemodal()" class="btn btn-secondary" id="closemodal">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="editUser" onclick="" value="">Save Changes</button> -->
              </div>
            </div>
          </div>
        </div>
        </form>



      </div>
    </div>
    <!-- Pages -->

  </div>
    <!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>
</form>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/allOrders.blade.php ENDPATH**/ ?>