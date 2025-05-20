<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<div class="container">
  <div class="tag" style="font-weight:800"><?php echo e($subject); ?></div>
    <div class="box me-4 my-1 bg-light">
      <div class="text-uppercase"><?php echo e($body); ?></div>
        <div class="d-flex align-items-center mt-2">
            <div class="ms-auto number">
              <div class="">
                <!-- <img src="<?php echo e(asset('img/'.$photo)); ?>" style="border-radius:5px;width:200px;height:220px" alt=""> -->
                <strong>Product: <?php echo e($productName); ?></strong>
              </div>
              <div class="">
              </div>
              <div class="" style="font-style:italic">
                <center>
                  <strong>Expected to be ready on: <?php echo e($expDel); ?></strong>
                </center>
              </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/layouts/email-alert.blade.php ENDPATH**/ ?>