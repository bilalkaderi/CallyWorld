<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $cats=DB::select("select * from categories");
  $catsNb=DB::table('categories')->count();
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

     $("#editCat").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editCat").val();
       var name=$("#CatName").val();

         $.ajax({
             url:"<?php echo e(route('editCat')); ?>",
             method:'POST',
             data: {id:id,name:name},
             success: function(output){
                 // alert(output.success);
                 $("#exampleModal").fadeOut();
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
                 location.reload();
               }
     });
     });

     $("#addCategory").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var name=$("#name").val();

         $.ajax({
             url:"<?php echo e(route('addCategory')); ?>",
             method:'POST',
             data: {name:name},
             success: function(output){
                 // alert(output.success);
                 // $(".modalUser").fadeOut();
                 $("#name").val('');
                 $("#catsTable").append(output.success);
                 // $(".alerts").fadeIn();
                 // location.reload();
               }
     });
     });


 });

function returnCat(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('returnCat')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#exampleModal").fadeIn();
            $("#CatName").val(output.name);
            $("#editCat").val(output.id);
          }
});
}

function deleteCat(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('deleteCat')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            $("#cat_"+id).fadeOut();
            $(".alerts").append(output.success);
            $(".alerts").fadeIn();
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
        <h1 class="h3 mb-0 text-gray-800">Categories</h1>
      </div>
      <hr>
      <div class="alerts" ></div>
      <hr>
      <h6>Total: <?php echo e($catsNb); ?> </h6>
      <table class="table table-sm" id="catsTable">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Total Products</th>
            <th scope="col">Created</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <form class="dsfsd" action="" method="post">
        <tbody>
          <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="cat_<?php echo e($c->id); ?>">
              <th scope="row"><?php echo e($c->id); ?></th>
              <td><?php echo e($c->name); ?></td>
              <td><?php echo e($c->totalproducts); ?></td>
              <td><?php echo e($c->created_at); ?></td>
              <td>
                <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                <button type="button" class="btn btn-info" onclick="returnCat(<?php echo e($c->id); ?>)" data-toggle="modalUser" id="togglemodal" >Edit</button>
                <button type="button" class="btn btn-light" onclick="deleteCat(<?php echo e($c->id); ?>)">Remove</button>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td colspan="2" style="text-align:center">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default">Category</span>
                </div>
                <input type="text" id="name" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
              </div>
          </td>
            <td colspan="2" style="text-align:right"><button type="button" class="btn btn-light" id="addCategory">Add Category</button></td>
          </tr>
        </tbody>
      </table>

      <!-- Modal -->
      <div class="modalUser" id="exampleModal" style="height:45rem" role="" aria-labelledby="" aria-hidden="">
        <div class="modal-dialog" role="document" style="height:30rem">
          <div class="modal-content" style="width:140rem;height:16rem">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Category Name</h5>
            </div>
            <div class="modal-body">
              <?php echo e(csrf_field()); ?>

              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <td>
                      <div class="input-group">
                        <input type="text" class="form-control" id="CatName">
                      </div>
                    </td>
                  </tr>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
              <button type="button" class="btn btn-outline-success" id="editCat" onclick="" value="">Save Changes</button>
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

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/category.blade.php ENDPATH**/ ?>