<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="d-flex my-4 flex-wrap">
    <div class="box me-4 my-1 bg-light">
      <div class="text-uppercase">Hello <?php echo e($to); ?></div>
        <div class="d-flex align-items-center mt-2">
            <div class="tag">New Message From <?php echo e($clientname); ?></div>
            <div class="ms-auto number">
              <?php echo e($message); ?>

          </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/layouts/email-template-client-toUser.blade.php ENDPATH**/ ?>