<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $rates=DB::select("select rate.id,rate.rate,rate.created_at,rate.userId,users.name,client.name as clientname from rate
            inner join users on rate.userId=users.id inner join client on rate.clientId=client.id
            order by rate.created_at desc");
  $ratesNb=DB::table('rate')->count();

  $prodrates=DB::select("select productsrate.id,productsrate.rate,productsrate.productId,productsrate.created_at,product.name,client.name as clientname from productsrate
            inner join product on productsrate.productId=product.id inner join client on productsrate.clientId=client.id
            order by productsrate.created_at desc");
  $prodratesNb=DB::table('productsrate')->count();
?>


<script type="text/javascript">
    function deleteRate(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"<?php echo e(route('deleteRate')); ?>",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#rate_"+id).fadeOut();
                $("#alertUser").text('');
                $("#alertUser").append(output.success);
                $("#alertUser").fadeIn();
              }
    });
    }

    function deleteProdRate(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"<?php echo e(route('deleteProdRate')); ?>",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#prodrate_"+id).fadeOut();
                $("#alertProd").text('');
                $("#alertProd").append(output.success);
                $("#alertProd").fadeIn();
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
            <h1 class="h3 mb-0 text-gray-800">Suppliers' Ratings</h1>
          </div>
          <hr>
          <div class="alerts" id="alertUser"></div>
          <hr>
          <h6>Total: <?php echo e($ratesNb); ?> </h6>
          <table class="table table-sm" id="usersTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Supplier Name</th>
                <th scope="col">Client Name</th>
                <th scope="col">Given Rate</th>
                <th scope="col">ON</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="rate_<?php echo e($r->id); ?>">
                  <th scope="row"><?php echo e($r->id); ?></th>
                  <td><a href="/userprofile/<?php echo e($r->userId); ?>"><?php echo e($r->name); ?></a></td>
                  <td><?php echo e($r->clientname); ?></td>
                  <td><?php echo e($r->rate); ?></td>
                  <td><?php echo e($r->created_at); ?></td>
                  <td>
                    <button type="button" class="btn btn-light" onclick="deleteRate(<?php echo e($r->id); ?>)">Remove</button>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Products' Ratings</h1>
          </div>
          <hr>
          <div class="alerts" id="alertProd"></div>
          <hr>
          <h6>Total: <?php echo e($prodratesNb); ?> </h6>
          <table class="table table-sm" id="prodTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Client Name</th>
                <th scope="col">Given Rate</th>
                <th scope="col">ON</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $prodrates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="prodrate_<?php echo e($pr->id); ?>">
                  <th scope="row"><?php echo e($pr->id); ?></th>
                  <td><a href="/product/<?php echo e($pr->productId); ?>"><?php echo e($pr->name); ?></a></td>
                  <td><?php echo e($pr->clientname); ?></td>
                  <td><?php echo e($pr->rate); ?></td>
                  <td><?php echo e($pr->created_at); ?></td>
                  <td>
                    <button type="button" class="btn btn-light" onclick="deleteProdRate(<?php echo e($pr->id); ?>)">Remove</button>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>

        </div>
      </div>
    <!-- Pages -->

  </div>
  <!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>
  </div>

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/rate.blade.php ENDPATH**/ ?>