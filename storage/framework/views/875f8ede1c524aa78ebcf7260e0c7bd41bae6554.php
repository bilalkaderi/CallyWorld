<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $users=DB::select("select * from users");
  $usersNb=DB::table('users')->count();

  $clients=DB::select("select * from client");
  $clientsNb=DB::table('client')->count();
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
// $(document).ready(function(){
//   $("#changePasswordUserAdmin_"+id).click(function(e){
//     e.preventDefault();
//     $.ajaxSetup({
//         headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
//     });
//     var newpassword=$("#newpassword").val();
//       $.ajax({
//           url:"<?php echo e(route('changePasswordUserAdmin')); ?>",
//           method:'POST',
//           data: {id:id,newpassword:newpassword},
//           contentType: false, //multipart/form-data
//           processData: false,
//           success: function(output){
//             $(".alerts").text('');
//             $(".alerts").append(output.success);
//             $(".alerts").fadeIn();
//             location.reload();
//             }
//   });
//   });
//
// });

function changePasswordUserAdmin(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var newpassword=$("#newpassword").val();
    $.ajax({
        url:"<?php echo e(route('changePasswordUserAdmin')); ?>",
        method:'POST',
        data: {id:id,newpassword:newpassword},
        success: function(output){
          $(".alerts").text('');
          $(".alerts").append(output.success);
          $(".alerts").fadeIn();
          location.reload();
          }
});
}

function changePasswordClientAdmin(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var newpassword=$("#passwordClient").val();
    $.ajax({
        url:"<?php echo e(route('changePasswordClientAdmin')); ?>",
        method:'POST',
        data: {id:id,newpassword:newpassword},
        success: function(output){
          $(".clientalerts").text('');
          $(".clientalerts").append(output.success);
          $(".clientalerts").fadeIn();
          location.reload();
          }
});
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
        <h1 class="h3 mb-0 text-gray-800">Manage Passwords</h1>
      </div>
      <hr>
      <div class="alerts" style="display:none" ></div>
      <hr>
      <h6>Total: <?php echo e($usersNb); ?> </h6>
      <table class="table table-sm" id="catsTable">
        <thead>
          <tr>
            <th scope="col">Suppliers</th>
          </tr>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Updated</th>
            <th scope="col">New Password</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <form class="dsfsd" action="" method="post">
          <?php echo csrf_field(); ?>
        <tbody>
          <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="user_<?php echo e($u->id); ?>">
              <th scope="row"><?php echo e($u->id); ?></th>
              <td><?php echo e($u->name); ?></td>
              <td><?php echo e($u->email); ?></td>
              <td><?php echo e($u->updated_at); ?></td>
              <td>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">New Password</span>
                  </div>
                  <input type="password" id="newpassword" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                </div>
              </td>
              <td>
                <button type="button" class="btn btn-outline-success" id="" onclick="changePasswordUserAdmin(<?php echo e($u->id); ?>)" value="">Save</button>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </form>
    </div>

  <div class="row mb-3" id="containerFluid2">
    <div class="alertsClient" style="display:none" ></div>
    <hr>
    <div class="clientalerts" style="display:none" ></div>
    <hr>
    <h6>Total: <?php echo e($clientsNb); ?> </h6>
    <table class="table table-sm" id="clientsTable">
      <thead>
        <tr>
          <th scope="col">Clients</th>
        </tr>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Updated</th>
          <th scope="col">New Password</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <form class="dsfsd" action="" method="post">
        <?php echo csrf_field(); ?>
      <tbody>
        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr id="client_<?php echo e($c->id); ?>">
            <th scope="row"><?php echo e($c->id); ?></th>
            <td><?php echo e($c->name); ?></td>
            <td><?php echo e($c->email); ?></td>
            <td><?php echo e($c->updated_at); ?></td>
            <td>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default-2">New Password</span>
                </div>
                <input type="password" id="passwordClient" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
              </div>
            </td>
            <td>
              <button type="button" class="btn btn-outline-success" onclick="changePasswordClientAdmin(<?php echo e($c->id); ?>)" value="">Save</button>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </form>
  </div>
  </div>
</div>
  <!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</div>

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/password.blade.php ENDPATH**/ ?>