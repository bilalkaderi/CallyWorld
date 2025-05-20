<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
  $comments=DB::select("select comment.id,comment.comment,comment.created_at,client.name,product.name as productname from comment
            inner join client on comment.clientId=client.id
            inner join product on comment.productId=product.id order by comment.created_at desc");
  $commentsNb=DB::table('comment')->count();
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

     $("#editComment").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editComment").val();
       var comment=$("#commentVal").val();

         $.ajax({
             url:"<?php echo e(route('editComment')); ?>",
             method:'POST',
             data: {id:id,comment:comment},
             success: function(output){
                 // alert(output.success);
                 $("#exampleModal").fadeOut();
                 $(".alerts").text('');
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
                 location.reload();
               }
     });
     });

 });

function returnComment(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('returnComment')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#exampleModal").fadeIn();
            $("#commentVal").val(output.comment);
            $("#editComment").val(output.id);
          }
});
}

function deleteComment(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"<?php echo e(route('deleteComment')); ?>",
        method:'POST',
        data: {id:id},
        success: function(output){
            $("#comment_"+id).fadeOut();
            $(".alerts").text('');
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
            <h1 class="h3 mb-0 text-gray-800">Comments</h1>
          </div>
          <hr>
          <div class="alerts" ></div>
          <hr>
          <h6>Total: <?php echo e($commentsNb); ?> </h6>
          <table class="table table-sm" id="commentsTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Comment</th>
                <th scope="col">Client Name</th>
                <th scope="col">Product Name</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <form class="dsfsd" action="" method="post">
            <tbody>
              <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="comment_<?php echo e($c->id); ?>">
                  <th scope="row"><?php echo e($c->id); ?></th>
                  <td><?php echo e($c->comment); ?></td>
                  <td><?php echo e($c->name); ?></td>
                  <td><?php echo e($c->productname); ?></td>
                  <td><?php echo e($c->created_at); ?></td>
                  <td>
                    <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                    <button type="button" class="btn btn-info" onclick="returnComment(<?php echo e($c->id); ?>)" data-toggle="modalUser" id="togglemodal" >Edit</button>
                    <button type="button" class="btn btn-light" onclick="deleteComment(<?php echo e($c->id); ?>)">Remove</button>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>

          <!-- Modal -->
          <div class="modalUser" id="exampleModal" style="height:45rem" role="" aria-labelledby="" aria-hidden="">
            <div class="modal-dialog" role="document" style="height:30rem">
              <div class="modal-content" style="width:140rem;height:16rem">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
                </div>
                <div class="modal-body">
                  <?php echo e(csrf_field()); ?>

                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th scope="col">Comment</th>
                        <td>
                          <div class="input-group">
                            <input type="text" class="form-control" id="commentVal">
                          </div>
                        </td>
                      </tr>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
                  <button type="button" class="btn btn-outline-success" id="editComment" onclick="" value="">Save Changes</button>
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
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/comment.blade.php ENDPATH**/ ?>