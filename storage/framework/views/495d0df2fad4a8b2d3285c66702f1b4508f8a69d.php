<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
  div, h2{
    float:center;
    align-content: center;
  }
</style>
<section style="margin-left:300px">
<div class="d-flex my-4 flex-wrap" style="float:center">
  <center><h2 class="tag">Email Verification</h2>
    <div class="box me-4 my-1 bg-light" style="border-radius:4px;padding:50px;float:center;">
        <div class="d-flex align-items-center mt-2" style="float:center">
            <div class="ms-auto number" style="float:center;background-color:rgba(210,210,210,0.2);padding:70px;border-radius:10px">
              <div class="alert alert-success" style="background-color:rgba(0,140,0,0.5);border:0.1px solid green;color:white;float:center">
                Thank you for verifying your E-Mail, now you can return to our website with a verified account.
              </div>
              <strong>Return to </strong><a href="http://127.0.0.1:8000">CallyWorld</a>
          </div>
        </div>
    </div>
</div>
</section>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/layouts/verifyEmail.blade.php ENDPATH**/ ?>