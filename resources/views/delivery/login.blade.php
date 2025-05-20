<head>
    <meta charset="utf-8">
    <title>Account</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <meta name="_token" content="{{csrf_token()}}">
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
      $("#dellogin").click(function(e){
        e.preventDefault();
        if($("#username").val().trim() == "" || $("#password").val().trim() == "")
                {
                  $("#clientalert").text('');
                  $("#clientalert").text('Fill all information!');
                  $("#clientalert").fadeIn();
                  return;
                }
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
            });

          var username = $("#username").val();
          var password = $("#password").val();
          $.ajax({
              url:"{{ route('deliveryLogin') }}",
              method:'POST',
              data:{username:username, password:password},
              success: function(output){
                if(output.success == 'Logged in successfully!'){
                  location.reload();
                }
                else{
                  $("#clientalert").text('');
                  $("#clientalert").append(output.success);
                  $("#clientalert").fadeIn();
                }
             }
          });

      });
    });
</script>
  <body>


    <center>
          <br/><hr style="color:#bba174"><div style="background-color:brown"><hr></div>
          <h2 class="text-center" style="color:#bba174;font-family:Tangerine_Regular;font-size:100px">Cally Home</h2>
            <div class="alert alert-danger" id="clientalert" style="width:30%;display:none">
              @if(session()->has('deliveryman'))
              {{ session()->get('deliveryman') }}
              @endif
            </div>
      <div style="background-color:#eef" id="loginPanel">
        <div class="login-form" style="width:25%">
          <form action="" method="post">
            @csrf
            <b><p class="hint-text" style="color:#bba174;">DeliveryMan Login</p></b>
            <div class="form-group">
              <input type="text" name="username" id="username" class="form-control" placeholder="Username"  value="">
            </div><hr>

            <div class="form-group">
              <input type="password" name="password" id="password" class="form-control" placeholder="Password"  value="">
            </div><hr><br/>

            <div class="form-group">
              <button type="button" id="dellogin" name="btnlogin" class="btn" style="border-radius:4px;background-color:#fff">Login</button>
            </div>

          </form>
          </div>
        </div>
    </center>
  </body>
</html>
