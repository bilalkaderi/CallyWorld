<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<div class="d-flex my-4 flex-wrap">
    <div class="box me-4 my-1 bg-light">
      <div class="text-uppercase"><?php echo e($body); ?></div>
        <div class="d-flex align-items-center mt-2">
            <div class="tag">Subject: <strong><?php echo e($subject); ?></strong></div>
            <div class="ms-auto number">
              <center><?php echo e($text); ?></center>
          </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/layouts/email-homeContact.blade.php ENDPATH**/ ?>