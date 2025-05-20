<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $reports=DB::select("select report.id,report.from,report.to,report.reason,report.blocked,report.created_at from report
            where report.from LIKE '%client: %'
            order by report.created_at desc");
  $reportsNb=DB::table('report')->where('from','LIKE','%client: %')->count();

  $creports=DB::select("select report.id,report.from,report.to,report.reason,report.blocked,report.created_at from report
            where report.from LIKE '%Supplier: %'
            order by report.created_at desc");
  $creportsNb=DB::table('report')->where('from','LIKE','%Supplier: %')->count();



?>


<script type="text/javascript">
$(document).ready(function(){
  $("#dropdownMenu2").click(function(){
    $("#dropdown-menu").toggle();
  });
  $("#dropdownMenu3").click(function(){
    $("#dropdown-menu2").toggle();
  });
});
      function userReport(id) {
        $.ajaxSetup({
            headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.ajax({
            url:"<?php echo e(route('userReport')); ?>",
            method:'POST',
            data: {id:id},
            success: function(output){
                // $("#rate_"+id).fadeOut();
                $("#tbody").text('');
                $("#tbody").append(output.success);
                $("#infouser").text('');
                $("#infouser").append(output.info);
                $("#reportsTable").fadeIn();
                $("#infouser").fadeIn();
              }
    });

        }

        function clientReport(id) {
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
          $.ajax({
              url:"<?php echo e(route('clientReport')); ?>",
              method:'POST',
              data: {id:id},
              success: function(output){
                  // $("#rate_"+id).fadeOut();
                  $("#ctbody").text('');
                  $("#ctbody").append(output.success);
                  $("#infoclient").text('');
                  $("#infoclient").append(output.info);
                  $("#creportsTable").fadeIn();
                  $("#infoclient").fadeIn();
                }
      });

          }


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
            <h1 class="h3 mb-0 text-gray-800">Reported Suppliers</h1>
          </div>
          <hr>
          <div class="alerts" id="alertUser"></div>
          <hr>
          <h6>Total: <?php echo e($reportsNb); ?> </h6>
          <table class="table table-sm" id="usersTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">From</th>
                <th scope="col">Supplier Reported</th>
                <th scope="col">Reason</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="rate_<?php echo e($r->id); ?>">
                  <th scope="row"><?php echo e($r->id); ?></th>
                  <td><?php echo e($r->from); ?></td>
                  <td><?php echo e($r->to); ?></td>
                  <td><?php echo e($r->reason); ?></td>
                  <td><?php if($r->blocked=='Yes'): ?>Blocked <?php else: ?> Not Blocked <?php endif; ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

        <tr>
          <td colspan="4" style="text-align:right">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h5 mb-0 text-gray-800">Custom Reported Supplier</h1>
            </div>
        </td>
        <td colspan="4" style="">
          <form class="scdsc" method="post">
          <div class="d-sm-flex">
              <?php
                $users=DB::select("select * from users where role=0");
              ?>
              <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Select Supplier
                </button>
                <div class="dropdown-menu" id="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button class="dropdown-item" type="button" onclick="userReport(<?php echo e($u->id); ?>)"><?php echo e($u->id); ?>. <?php echo e($u->name); ?></button>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              </div>
              <hr>
            </div>
        </td>
      </form>
    </table>

      <table id='infouser' class="table table-sm" align='center' style='width:50%;border-spacing:20px;display:none'>

      </table>


          <table class="table table-sm" id="reportsTable" style="display:none">
            <thead>
              <tr>
                <th scope="col">From</th>
                <th scope="col">Reason</th>
                <th scope="col">Blocked</th>
              </tr>
            </thead>

            <tbody id="tbody">

            </tbody>

          </table>


          <!-- page -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4" style="border-top:3px solid #eee">
              <h1 class="h3 mb-0 text-gray-800">Reported Clients</h1>
            </div>
            <hr>
            <div class="alerts" id="alertClient"></div>
            <hr>
            <h6>Total: <?php echo e($creportsNb); ?> </h6>
            <table class="table table-sm" id="clientsTable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">From</th>
                  <th scope="col">Client Reported</th>
                  <th scope="col">Reason</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $creports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr id="rate_<?php echo e($r->id); ?>">
                    <th scope="row"><?php echo e($r->id); ?></th>
                    <td><?php echo e($r->from); ?></td>
                    <td><?php echo e($r->to); ?></td>
                    <td><?php echo e($r->reason); ?></td>
                    <td><?php if($r->blocked=='Yes'): ?>Blocked <?php else: ?> Not Blocked <?php endif; ?></td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>

          <tr>
            <td colspan="4" style="text-align:right">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h5 mb-0 text-gray-800">Custom Reported Client</h1>
              </div>
          </td>
          <td colspan="4" style="">
            <form class="scdsc" method="post">
            <div class="d-sm-flex">
                <?php
                  $clients=DB::select("select * from client");
                ?>
                <div class="dropdown">
                  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select Client
                  </button>
                  <div class="dropdown-menu" id="dropdown-menu2" aria-labelledby="dropdownMenu2">
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <button class="dropdown-item" type="button" onclick="clientReport(<?php echo e($c->id); ?>)"><?php echo e($c->id); ?>. <?php echo e($c->name); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </div>
                <hr>
              </div>
          </td>
        </form>
      </table>

        <table id='infoclient' class="table table-sm" align='center' style='width:50%;border-spacing:20px;display:none'>

        </table>


            <table class="table table-sm" id="creportsTable" style="display:none">
              <thead>
                <tr>
                  <th scope="col">From</th>
                  <th scope="col">Reason</th>
                  <th scope="col">Blocked</th>
                </tr>
              </thead>

              <tbody id="ctbody">

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
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/report.blade.php ENDPATH**/ ?>