<head>
    <meta charset="utf-8">
    <title>Account</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/main.css')); ?>">
  </head>
</style>
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#btnshow").click(function(){
      $("#loginPanel").toggle();
      $("#registerPanel").fadeIn();
    });
    $("#btnhide").click(function(){
      $("#registerPanel").toggle();
      $("#loginPanel").fadeIn();
    });


  });
</script>
  <body>


    <center>
          <br/><hr style="color:#bba174"><div style="background-color:brown"><hr></div>
          <h2 class="text-center" style="color:#bba174;font-family:Tangerine_Regular;font-size:100px">Cally Home</h2>
          <?php if(session()->has('notverified')): ?>
            <div class="alert alert-danger" style="width:30%;">
                <?php echo e(session()->get('notverified')); ?>

            </div>
          <?php endif; ?>
          <?php if(session()->has('registered')): ?>
            <div class="alert alert-success" style="width:40%;">
                <?php echo e(session()->get('registered')); ?>

            </div>
          <?php endif; ?>
          <?php if(session()->has('notvalid')): ?>
            <div class="alert alert-danger" style="width:40%;">
                <?php echo e(session()->get('notvalid')); ?>

            </div>
          <?php endif; ?>
          <?php if(session()->has('verified')): ?>
            <div class="alert alert-success" style="width:30%;">
                <?php echo e(session()->get('verified')); ?>

            </div>
          <?php endif; ?>
      <div style="background-color:#eef" id="loginPanel">
        <div class="login-form" style="width:25%">
          <form action="<?php echo e(route('login.custom')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <b><p class="hint-text" style="color:#bba174;">Login Panel</p></b>
            <div class="form-group">
              <input type="text" name="email" id="email" class="form-control" placeholder="Email"  value="<?php echo e(old('email')); ?>">
            </div><hr>

            <div class="form-group">
              <input type="password" name="password" id="password" class="form-control" placeholder="Password"  value="">
            </div><hr><br/>

            <div class="form-group">
              <button type="submit" id="btnlogin" name="btnlogin" class="btn" style="border-radius:4px;background-color:#fff">Login</button>
            </div>

            <button type="button" id="btnshow" style="border:0;" class="btn">You don't have an account? Register Here.</button>
          </div><br>
          </form>
        </div>
    </center>
  </body>
<div class="" style="display:none" id="registerPanel">
  <?php echo $__env->make('auth.registration', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</html>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/auth/login.blade.php ENDPATH**/ ?>