@include('admin.bars.tops')
@php
  $users=DB::select("select users.id,users.name,users.email,users.status,users.phone,users.nbOfSales,users.role,
  users.created_at,rate.rate from users left join rate on users.id=rate.userId order by users.name asc");

  $usersNb=DB::table('users')->count();
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
   $(document).ready(function(){
//     $("#togglemodal").click(function(){
//       $(".modalUser").fadeIn();
//     });
     $("#closemodal").click(function(){
       $(".modalUser").fadeOut();
     });

     $("#editUser").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editUser").val();
       var name=$("#name").val();
       var email=$("#email").val();
       var phone=$("#phone").val();
       var about= $("#about").val();
       // var sales=$("#sales").val();
       var role=$("#role").val();
       var status=$("#status").val();

         $.ajax({
             url:"{{route('editUser')}}",
             method:'POST',
             data: {id:id,name:name,email:email,phone:phone,about:about,status:status,role:role},
             success: function(output){
                 // alert(output.success);
                 $(".modalUser").fadeOut();
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
                 location.reload();
               }
     });
     });




 });

function returnUser(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('returnUser')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $("#salesData").text('');
            $("#salesData").append(output.success);
            $(".modalUser").fadeIn();
            $("#name").val(output.name);
            $("#email").val(output.email);
            $("#phone").val(output.phone);
            $("#about").val(output.about);
            $("#sales").val(output.sales);
            $("#role").val(output.role);
            $("#status").val(output.status);
            // $("#reported").val(output.reported);
            $("#editUser").val(output.id);
          }
});
}

function deleteUser(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('deleteUser')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            $(".alerts").text('');
            $("#user_"+id).fadeOut();
            $(".alerts").append(output.success);
            $(".alerts").fadeIn();
          }
});
}

// function returnSales(){
//   $.ajaxSetup({
//      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
//   });
//   var id=$(this).val();
//   var dateFrom=$("#dateFrom").val();
//   var dateTo=$("#dateTo").val();
//    $.ajax({
//        url:"{{route('returnSalesAdmin')}}",
//        method:'post',
//        data: {id:id,dateTo:dateTo,dateFrom:dateFrom},
//        success: function(output){
//           $("#salesData").text('');
//            $("#salesData").append(output.success);
//          }
//   });
//   }


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
          <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
        </div>
        <hr>
        @if(session()->has('newregistration'))
          <div class="alerts" style="color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px">
            {{session()->get('newregistration')}}
          </div>
        @endif
        <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px">

        </div>
        <hr>
        <h6>Total: {{$usersNb}} </h6>

        <table class="table table-sm" id="userstable">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name &#8595;</th>
              <th scope="col">Email</th>
              <th scope="col">Phone number</th>
              <th scope="col">Number of Sales</th>
              <th scope="col">Joined</th>
              <th scope="col">Rate</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr id="user_{{$u->id}}">
                <th scope="row">{{$u->id}}
                  @if($u->status=='verified')
                  <img src="{{asset('css/icons/check-mark.png')}}" style="width:30px;height:30px">
                @endif</th>
                <td><a href="/userprofile/{{$u->id}}">{{$u->name}}</a></td>
                <td>{{$u->email}}</td>
                <td>{{$u->phone}}</td>
                <td>{{$u->nbOfSales}}</td>
                <td>{{$u->created_at}}</td>
                <td>{{$u->rate}}</td>
                <td>
                  @if($u->role =='0')
                    <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                    <button type="button" class="btn btn-info"  onclick="returnUser({{$u->id}})" data-toggle="modalUser" id="togglemodal" >Edit</button>
                    <button type="button" class="btn btn-light" onclick="deleteUser({{$u->id}})">Remove</button>
                  @else
                    Admin
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

<form class="dsfsd" action="{{route ('addUserAdmin')}}" method="POST">
  @csrf
        <table class="table table-sm">
          <tr>
              <td colspan="8">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">New Supplier</span>
                  </div>
                </div>
              </td>
            </tr>

            <tr>
              <td colspan="">
                <div class="input-group">
                  <input type="text" class="form-control" id="newname" name="newname" placeholder="Name">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <input type="text" class="form-control" id="newemail" name="email" placeholder="Email">
                </div>
              </td>

              <td colspan="">
                <div class="input-group">
                  @include('layouts.phonecodes')
                  <input type="text" class="form-control" id="newphone" name="newphone" placeholder="Phone">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="Password">
                </div>
              </td>

              <td>
                <div class="input-group">
                  <select class="form-control" name="newstatus" id="newstatus">
                    <option class="form-control" value="pending">Pending</option>
                    <option class="" value="verified">Verified</option>
                  </select>
                </div>
              </td>

              <td colspan="">
                <div class="input-group">
                  <select class="form-control" name="newrole" id="newrole">
                    <option class="" value="1">Admin</option>
                    <option class="form-control" value="0">Supplier</option>
                  </select>
                  <button type="submit" class="btn btn-light" id="addUser" name="addUser" >Add</button>
                </div>
              </td>
          </tr>
        </table>
</form>

        <form class="dsfsd" action="" method="post">
        <!-- Modal -->
        <div class="modalUser" id="exampleModal" style="height:auto" role="" aria-labelledby="" aria-hidden="">
          <div class="modal-dialog" role="document" style="height:auto">
            <div class="modal-content" style="width:140rem;height:auto">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                {{csrf_field()}}
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th scope="col">Name and Email</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="name">
                          <input type="text" class="form-control" id="email" readonly>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="col">Phone and Status</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="phone">
                          <select class="form-control" id="status">
                            <option class="form-control" value="pending">Pending</option>
                            <option class="form-control" value="verified">Verified</option>
                          </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="col">About - Role</th>
                      <td>
                        <div class="input-group">
                          <input type="text" class="form-control" id="about">
                          <select class="form-control" id="role">
                            <option class="form-control" value="1">Admin</option>
                            <option class="form-control" value="0">Supplier</option>
                          </select>
                        </div>
                      </td>
                    </tr>
                </table>
                <h5>Sales:</h5>
                <div class="" id="salesData">

                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
                <button type="button" class="btn btn-primary" id="editUser" onclick="" value="">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
        </form>

        <!-- /////////////////////////////////////Add User////////////////////////////////////- -->


        <!-- //////////////////////////////End add user //////////////////////////////////// -->
      </div>
    </div>
    <!-- Pages -->

  </div>
    <!-- Footer --> @include('admin.bars.footer')
  </div>
</div>

</body>
