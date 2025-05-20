<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $users=DB::select("select users.id,users.name,users.email,users.status,users.phone,users.nbOfSales,users.role,
  users.created_at,rate.rate from users left join rate on users.id=rate.userId order by users.name asc");

  $usersNb=DB::table('users')->count();
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

     $("#editUser").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editUser").val();
       var name=$("#name").val();
       var email=$("#email").val();
       var phone=$("#phone").val();
       var about= $("#about").val();
       // var sales=$("#sales").val();
       var role=$("#role").val();
       var status=$("#status").val();

         $.ajax({
             url:"<?php echo e(route('editUser')); ?>",
             method:'POST',
             data: {id:id,name:name,email:email,phone:phone,about:about,status:status,role:role},
             success: function(output){
                 // alert(output.success);
                 $(".modalUser").fadeOut();
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
                 location.reload();
               }
     });
     });




 });

function returnUser(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('returnUser')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#salesData").text('');
            $("#salesData").append(output.success);
            $(".modalUser").fadeIn();
            $("#name").val(output.name);
            $("#email").val(output.email);
            $("#phone").val(output.phone);
            $("#about").val(output.about);
            $("#sales").val(output.sales);
            $("#role").val(output.role);
            $("#status").val(output.status);
            // $("#reported").val(output.reported);
            $("#editUser").val(output.id);
          }
});
}

function deleteUser(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('deleteUser')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            $(".alerts").text('');
            $("#user_"+id).fadeOut();
            $(".alerts").append(output.success);
            $(".alerts").fadeIn();
          }
});
}

// function returnSales(){
//   $.ajaxSetup({
//      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
//   });
//   var id=$(this).val();
//   var dateFrom=$("#dateFrom").val();
//   var dateTo=$("#dateTo").val();
//    $.ajax({
//        url:"<?php echo e(route('returnSalesAdmin')); ?>",
//        method:'post',
//        data: {id:id,dateTo:dateTo,dateFrom:dateFrom},
//        success: function(output){
//           $("#salesData").text('');
//            $("#salesData").append(output.success);
//          }
//   });
//   }


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
          <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
        </div>
        <hr>
        <?php if(session()->has('newregistration')): ?>
          <div class="alerts" style="color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px">
            <?php echo e(session()->get('newregistration')); ?>

          </div>
        <?php endif; ?>
        <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px">

        </div>
        <hr>
        <h6>Total: <?php echo e($usersNb); ?> </h6>

        <table class="table table-sm" id="userstable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name &#8595;</th>
              <th scope="col">Email</th>
              <th scope="col">Phone number</th>
              <th scope="col">Number of Sales</th>
              <th scope="col">Joined</th>
              <th scope="col">Rate</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr id="user_<?php echo e($u->id); ?>">
                <th scope="row"><?php echo e($u->id); ?>

                  <?php if($u->status=='verified'): ?>
                  <img src="<?php echo e(asset('css/icons/check-mark.png')); ?>" style="width:30px;height:30px">
                <?php endif; ?></th>
                <td><a href="/userprofile/<?php echo e($u->id); ?>"><?php echo e($u->name); ?></a></td>
                <td><?php echo e($u->email); ?></td>
                <td><?php echo e($u->phone); ?></td>
                <td><?php echo e($u->nbOfSales); ?></td>
                <td><?php echo e($u->created_at); ?></td>
                <td><?php echo e($u->rate); ?></td>
                <td>
                  <?php if($u->role =='0'): ?>
                    <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                    <button type="button" class="btn btn-info"  onclick="returnUser(<?php echo e($u->id); ?>)" data-toggle="modalUser" id="togglemodal" >Edit</button>
                    <button type="button" class="btn btn-light" onclick="deleteUser(<?php echo e($u->id); ?>)">Remove</button>
                  <?php else: ?>
                    Admin
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>

<form class="dsfsd" action="<?php echo e(route ('addUserAdmin')); ?>" method="POST">
  <?php echo csrf_field(); ?>
        <table class="table table-sm">
          <tr>
              <td colspan="8">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">New Supplier</span>
                  </div>
                </div>
              </td>
            </tr>

            <tr>
              <td colspan="">
                <div class="input-group">
                  <input type="text" class="form-control" id="newname" name="newname" placeholder="Name">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <input type="text" class="form-control" id="newemail" name="email" placeholder="Email">
                </div>
              </td>

              <td colspan="">
                <div class="input-group">
                  <?php echo $__env->make('layouts.phonecodes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  <input type="text" class="form-control" id="newphone" name="newphone" placeholder="Phone">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Password">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <select class="form-control" name="newstatus" id="newstatus">
                    <option class="form-control" value="pending">Pending</option>
                    <option class="" value="verified">Verified</option>
                  </select>
                </div>
              </td>

              <td colspan="">
                <div class="input-group">
                  <select class="form-control" name="newrole" id="newrole">
                    <option class="" value="1">Admin</option>
                    <option class="form-control" value="0">Supplier</option>
                  </select>
                  <button type="submit" class="btn btn-light" id="addUser" name="addUser" >Add</button>
                </div>
              </td>
          </tr>
        </table>
</form>

        <form class="dsfsd" action="" method="post">
        <!-- Modal -->
        <div class="modalUser" id="exampleModal" style="height:auto" role="" aria-labelledby="" aria-hidden="">
          <div class="modal-dialog" role="document" style="height:auto">
            <div class="modal-content" style="width:140rem;height:auto">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php echo e(csrf_field()); ?>

                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th scope="col">Name and Email</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="name">
                          <input type="text" class="form-control" id="email" readonly>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="col">Phone and Status</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="phone">
                          <select class="form-control" id="status">
                            <option class="form-control" value="pending">Pending</option>
                            <option class="form-control" value="verified">Verified</option>
                          </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="col">About - Role</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="about">
                          <select class="form-control" id="role">
                            <option class="form-control" value="1">Admin</option>
                            <option class="form-control" value="0">Supplier</option>
                          </select>
                        </div>
                      </td>
                    </tr>
                </table>
                <h5>Sales:</h5>
                <div class="" id="salesData">

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
                <button type="button" class="btn btn-primary" id="editUser" onclick="" value="">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
        </form>

        <!-- /////////////////////////////////////Add User////////////////////////////////////- -->


        <!-- //////////////////////////////End add user //////////////////////////////////// -->
      </div>
    </div>
    <!-- Pages -->

  </div>
    <!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
</div>

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/user.blade.php ENDPATH**/ ?>