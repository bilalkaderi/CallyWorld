@php
  $commentsNb=DB::table('comment')->where('productId','=',$product->id)->count();
@endphp
<!DOCTYPE html>
<html lang="en">
<meta name="_token" content="{{csrf_token()}}">

<link rel="stylesheet" type="text/css" href="{{ asset('css/vendor/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/vendor/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/ruang-admin.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>

<head>
  <meta charset="utf-8">
  <title>{{$product->name}}</title>
</head>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
<script src="../vendor/chart.js/Chart.min.js"></script>
<script src="js/demo/chart-area-demo.js"></script>

<script type="text/javascript">
function deleteComment(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('removeComment')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            $("#comment_"+id).fadeOut();
            $(".alerts").text('');
            $(".alerts").append(output.success);
            $(".alerts").fadeIn();
          }
});
}


</script>

<body id="page-top">
<div id="wrapper">
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- dashboard -->
<!-- ""''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' -->
    <div class="container-fluid" style="margin-top: 100px;" id="container-user">
      <div class="" style="float:left">
        <span><img style="border-radius:10px;width:350px;height:360px" src="{{asset('img/'.$product->photo)}}"></span>

      </div>
      <div class="row mb-3" id="containerFluid">
        <!-- page -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Comments</h1>
          </div>
          <hr>
          <div class="alerts" ></div>
          <hr>
          <table class="table table-sm" id="commentsTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Client Name</th>
                <th scope="col">Comment</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <form class="dsfsd" action="" method="post">
            <tbody>
              @foreach($comments as $c)
              @php
                $id=$c->clientId;
                $username=DB::select("select * from client where id=$id");
              @endphp
                <tr id="comment_{{$c->id}}">
                  <th scope="row">{{$c->id}}</th>
                @foreach($username as $user)
                  <td>{{$user->name}}</td>
                @endforeach
                  <td>{{$c->comment}}</td>
                  <td>{{$c->created_at}}</td>
                  <td>
                    <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                    <!-- <button type="button" class="btn btn-info" onclick="returnComment({{$c->id}})" data-toggle="modalUser" id="togglemodal" >Edit</button> -->
                    <button type="button" class="btn btn-light" onclick="deleteComment({{$c->id}})">Remove</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          </form>
    </div>
</div>
<!-- Pages -->

</div>
<!-- Footer --> @include('admin.bars.footer')
</div>
</div>

</body>
