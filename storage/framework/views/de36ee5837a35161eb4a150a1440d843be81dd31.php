  <head>
    <meta charset="utf-8">
    <title>Register</title>
	</head>
  <style media="screen">
      th, td {
        padding: 5px;
      }
  </style>
  <link rel="stylesheet" href="<?php echo e(asset('css/main.css')); ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
      var loadFile = function(event) {
        var image = document.getElementById('profilephoto');
        image.src = "";
        image.src = URL.createObjectURL(event.target.files[0]);
      };
  </script>
  <style media="screen">
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
  *{
    font-family: 'Poppins', sans-serif;
  }
  </style>
  <body>
  <form class="" action="<?php echo e(route('register.custom')); ?>" method="POST" enctype="multipart/form-data"><center>
    <?php echo csrf_field(); ?>
		<div class="register" align="center" style="">
      <table style="" align="center" style="width:90%;border-spacing:20px">
        <tr>
              <td rowspan="8" style="padding:35px;border-radius: 5px;background-color:#eef">
                <label for='photoprofile'><img src='img/profile.png' class='photo' name='photo' style="cursor:pointer;width:185px;height:190px;border-radius:4px" title='Choose photo' id='profilephoto'/></label><br>
                <input type='file' name='photoprofile' style='display:none' accept='' id='photoprofile' onchange='loadFile(event)' >
            </td>
      </tr>
				<tr>
          <td>
          <input type="text" name="name"class="form-control" placeholder="Name" id="name" value="<?php echo e(old('name')); ?>">
        </td></tr>

				<tr><td>
          <input type="text" name="email" placeholder="Email"class="form-control" id="email" value="<?php echo e(old('email')); ?>" >
        </td></tr>
        <tr><td>
          <input type="password" name="password" placeholder="Password" id="password"class="form-control">
        </td></tr>
        <tr><td>
          <input type="password" name="password_confirmation" placeholder="Confirm Password"class="form-control" id="password_confirmation" >
        </td></tr>
        <tr><td>
          <input type="text" name="address" placeholder="Address" class="form-control" id="address" >
        </td></tr>
        <tr><td>
          <?php echo $__env->make('layouts.phonecodes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <input name="phone" type="text" id="phone" placeholder="Phone Number" class="form-control"/>

        </td></tr>

      </div>
    </table>
    <hr width="70%">
        <center><input type="submit" id="reg" value="Register" name="btnadd" class="btn">
          <br><button type="button" id="btnhide" style="border:0;" class="btn">Already registered?</button>
        </center>

		  </div></center>
    </form>
	</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/auth/registration.blade.php ENDPATH**/ ?>