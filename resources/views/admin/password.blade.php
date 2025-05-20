@include('admin.bars.tops')
@php
  $users=DB::select("select * from users");
  $usersNb=DB::table('users')->count();

  $clients=DB::select("select * from client");
  $clientsNb=DB::table('client')->count();
@endphp

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
// $(document).ready(function(){
//   $("#changePasswordUserAdmin_"+id).click(function(e){
//     e.preventDefault();
//     $.ajaxSetup({
//         headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
//     });
//     var newpassword=$("#newpassword").val();
//       $.ajax({
//           url:"{{route('changePasswordUserAdmin')}}",
//           method:'POST',
//           data: {id:id,newpassword:newpassword},
//           contentType: false, //multipart/form-data
//           processData: false,
//           success: function(output){
//             $(".alerts").text('');
//             $(".alerts").append(output.success);
//             $(".alerts").fadeIn();
//             location.reload();
//             }
//   });
//   });
//
// });

function changePasswordUserAdmin(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var newpassword=$("#newpassword").val();
    $.ajax({
        url:"{{route('changePasswordUserAdmin')}}",
        method:'POST',
        data: {id:id,newpassword:newpassword},
        success: function(output){
          $(".alerts").text('');
          $(".alerts").append(output.success);
          $(".alerts").fadeIn();
          location.reload();
          }
});
}

function changePasswordClientAdmin(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
  var newpassword=$("#passwordClient").val();
    $.ajax({
        url:"{{route('changePasswordClientAdmin')}}",
        method:'POST',
        data: {id:id,newpassword:newpassword},
        success: function(output){
          $(".clientalerts").text('');
          $(".clientalerts").append(output.success);
          $(".clientalerts").fadeIn();
          location.reload();
          }
});
}


</script>

<body id="page-top">
<div id="wrapper">
  <!-- Sidebar --> @include('admin.bars.sidebar')
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- dashboard -->
<!-- ""''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' -->
    <div class="container-fluid" style="margin-top: 100px;" id="container-user">
      <div class="row mb-3" id="containerFluid">
        <!-- page -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Passwords</h1>
      </div>
      <hr>
      <div class="alerts" style="display:none" ></div>
      <hr>
      <h6>Total: {{$usersNb}} </h6>
      <table class="table table-sm" id="catsTable">
        <thead>
          <tr>
            <th scope="col">Suppliers</th>
          </tr>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Updated</th>
            <th scope="col">New Password</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <form class="dsfsd" action="" method="post">
          @csrf
        <tbody>
          @foreach($users as $u)
            <tr id="user_{{$u->id}}">
              <th scope="row">{{$u->id}}</th>
              <td>{{$u->name}}</td>
              <td>{{$u->email}}</td>
              <td>{{$u->updated_at}}</td>
              <td>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">New Password</span>
                  </div>
                  <input type="password" id="newpassword" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                </div>
              </td>
              <td>
                <button type="button" class="btn btn-outline-success" id="" onclick="changePasswordUserAdmin({{$u->id}})" value="">Save</button>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </form>
    </div>

  <div class="row mb-3" id="containerFluid2">
    <div class="alertsClient" style="display:none" ></div>
    <hr>
    <div class="clientalerts" style="display:none" ></div>
    <hr>
    <h6>Total: {{$clientsNb}} </h6>
    <table class="table table-sm" id="clientsTable">
      <thead>
        <tr>
          <th scope="col">Clients</th>
        </tr>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Updated</th>
          <th scope="col">New Password</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <form class="dsfsd" action="" method="post">
        @csrf
      <tbody>
        @foreach($clients as $c)
          <tr id="client_{{$c->id}}">
            <th scope="row">{{$c->id}}</th>
            <td>{{$c->name}}</td>
            <td>{{$c->email}}</td>
            <td>{{$c->updated_at}}</td>
            <td>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default-2">New Password</span>
                </div>
                <input type="password" id="passwordClient" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
              </div>
            </td>
            <td>
              <button type="button" class="btn btn-outline-success" onclick="changePasswordClientAdmin({{$c->id}})" value="">Save</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </form>
  </div>
  </div>
</div>
  <!-- Footer --> @include('admin.bars.footer')
</div>
</div>

</body>
