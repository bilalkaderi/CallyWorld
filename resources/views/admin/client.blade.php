@include('admin.bars.tops')
@php
  $clients=DB::select("select * from client order by name asc");

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
   $(document).ready(function(){
//     $("#togglemodal").click(function(){
//       $(".modalUser").fadeIn();
//     });
     $("#closemodal").click(function(){
       $(".modalUser").fadeOut();
     });

     $("#editClient").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editClient").val();
       var form_data=new FormData();
       var name=$("#name").val();
       var email=$("#email").val();
       var phone=$("#phone").val();
       var status=$("#status").val();

         $.ajax({
             url:"{{route('editClient')}}",
             method:'POST',
             data: {id:id,name:name,email:email,phone:phone},
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

function returnClient(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('returnClient')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $(".modalUser").fadeIn();
            $("#name").val(output.name);
            $("#email").val(output.email);
            $("#phone").val(output.phone);
            $("#status").val(output.status);
            $("#editClient").val(output.id);
          }
});
}

function deleteClient(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('deleteClient')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            $("#client_"+id).fadeOut();
            $(".alerts").append(output.success);
            $(".alerts").fadeIn();
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
              <h1 class="h3 mb-0 text-gray-800">Clients</h1>
            </div>
            <hr>
            <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
            <hr>
            <h6>Total: {{$clientsNb}} </h6>

            <table class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone number</th>
                  <th scope="col">Total Comments</th>
                  <th scope="col">Joined</th>
                  <th scope="col">Last Updated</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($clients as $c)
                  <tr id="client_{{$c->id}}">
                    <th scope="row">{{$c->id}}
                      @if($c->status=='verified')
                      <img src="{{asset('css/icons/check-mark.png')}}" style="width:30px;height:30px">
                    @endif</th>
                    <td>{{$c->name}}</td>
                    <td>{{$c->email}}</td>
                    <td>{{$c->phone}}</td>
                    <td>
                      @php
                        $nbComm=DB::table('comment')->where('clientId','=',$c->id)->count();
                        echo $nbComm;
                      @endphp
                    </td>
                    <td>{{$c->created_at}}</td>
                    <td>{{$c->updated_at}}</td>
                    <td>
                      <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                      <button type="button" class="btn btn-info"  onclick="returnClient({{$c->id}})" data-toggle="modalUser" id="togglemodal" >Edit</button>
                      <button type="button" class="btn btn-light" onclick="deleteClient({{$c->id}})">Remove</button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>


            <form class="dsfsd" action="" method="post">
            <!-- Modal -->
            <div class="modalUser" id="exampleModal" style="height:60rem" role="" aria-labelledby="" aria-hidden="">
              <div class="modal-dialog" role="document" style="height:40rem">
                <div class="modal-content" style="width:140rem;height:23rem">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Client</h5>
                  </div>
                  <div class="modal-body">
                    {{csrf_field()}}
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <td>
                            <div class="input-group">
                              <input type="text" class="form-control" id="name">
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <th scope="col">Email</th>
                          <td>
                            <div class="input-group">
                              <input type="text" class="form-control" id="email">
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
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
                    <button type="button" class="btn btn-outline-success" id="editClient" onclick="" value="">Save Changes</button>
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
        <!-- Pages -->

        </div>
        <!-- Footer --> @include('admin.bars.footer')
        </div>
        </div>

        </body>
