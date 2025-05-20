@include('admin.bars.tops')
@php
  $clientemail=session()->get('clientemail');
  $clientId = DB::select("select * from client where email='$clientemail'");

  $id=$user->id;
  $nbPro=DB::table('product')->where('userId','=',$id)->count();
  $count=DB::table('rate')->where('userId','=',$id)->count();
  $sum=DB::table('rate')->where('userId','=',$id)->sum('rate');
  if($count>0){
    $ratings=round($sum/$count,'1');
  }
  else{$ratings=0;}

@endphp
<head>
</head>
@php
  $subscribers=json_decode($user->subscribers, true);
  $followers=count($subscribers);
@endphp
@if($subscribers)
  @foreach($subscribers as $s)
    @if(session()->has('clientemail'))
      @foreach($clientId as $c)
        @if($s == $c->id)
          @php $subscribed='yes'; @endphp
        @else
          @php $subscribed='no'; @endphp
        @endif
      @endforeach
    @else
      @php $subscribed='no'; @endphp
    @endif
  @endforeach
@else
  @php
   $subscribed='no';
  @endphp
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta name="_token" content="{{csrf_token()}}">
<script type="text/javascript">
    $(document).ready(function(){
      $("#closemodal").click(function(){
        $("#modalAlert").fadeOut();
      });

      $(".ratingrb").click(function(){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
            });

          var form_data = new FormData();
          form_data.append("userid", $('#userid').val());
          form_data.append("clientid", $('#clientid').val());
          form_data.append("rating", $(this).val());
          $.ajax({
              url:"{{ route('rateUser') }}",
              method:'post',
              data:form_data,
              contentType: false, //multipart/form-data
              processData: false,
              success: function(output){
                $("#ratingvalue").text('');
                $("#ratingvalue").text(output.ratings);
                  //$(".alert").html(output.success);
             }
          });
      });



      $("#subscribe").click(function(){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
            });

          var form_data = new FormData();
          form_data.append("userid",$('#userid').val());
          form_data.append("clientid",$('#clientid').val());
          // var clientid=$('#clientid').val();

          $.ajax({
              url:"{{ route('subscribe') }}",
              method:'post',
              data:form_data,
              contentType: false, //multipart/form-data
              processData: false,
              success: function(output){
                $("#modalbody").text('');
                $("#modalbody").append(output.success);
                $("#modalAlert").fadeIn();
                $("#subscribe").fadeOut();
                // $("#modalfooter").text('');
             }
          });
      });

      $("#toggleNavBar").click(function(){
        $(".navbar-collapse").toggle();
      });


      $("#unsubscribe").click(function(){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
            });

          var form_data = new FormData();
          form_data.append("userid",$('#userid').val());
          form_data.append("clientid",$('#clientid').val());
          // var clientid=$('#clientid').val();

          $.ajax({
              url:"{{ route('unsubscribe') }}",
              method:'post',
              data:form_data,
              contentType: false, //multipart/form-data
              processData: false,
              success: function(output){
                $("#modalbody").text('');
                $("#modalbody").append(output.success);
                $("#modalAlert").fadeIn();
                $("#unsubscribe").fadeOut();

                // $("#modalfooter").text('');
             }
          });
      });


      $("#first-report").click(function(){
        $(".report").toggle();
        $(".reason-report").fadeIn();
      });

      $("#moreProducts").click(function(){
        $("#modalProducts").fadeIn();
      });
      $("#closemodalProducts").click(function(){
        $("#modalProducts").fadeOut();
      });
    });

    function report(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
      var clientid= $('#clientid').val();
      var reportmessage= $('#reportmessage').val();

      var form_data = new FormData();
      form_data.append("id", id);
      form_data.append("clientid", clientid);
      form_data.append("reportmessage", reportmessage);
        $.ajax({
            url:"{{ route('reportUser') }}",
            method:'post',
            data: form_data,
            contentType: false, //multipart/form-data
            processData: false,
            success: function(output){
                $(".reason-report").toggle();
                $(".block").prepend(output.success);
                $(".block").fadeIn();
              }
            });
        }

        function block(id){
          $.ajaxSetup({
              headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
          });
          var clientid= $('#clientid').val();

          var form_data = new FormData();
          form_data.append("id", id);
          form_data.append("clientid", clientid);
            $.ajax({
                url:"{{ route('blockUser') }}",
                method:'post',
                data: form_data,
                contentType: false, //multipart/form-data
                processData: false,
                success: function(output){
                  window.location.href = "{{route('home')}}";
                  }
                });
            }


            function sendEmail(id){
                $.ajaxSetup({
                    headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                  });

                var form_data = new FormData();
                form_data.append("to",$('#to').val());
                form_data.append("clientname",$('#emailname').val());
                form_data.append("clientemail",$('#email').val());
                form_data.append("subject",$('#subject').val());
                form_data.append("message",$("#message").val());
                $.ajax({
                  url:"{{ route ('sendEmail') }}",
                    method:'post',
                    data:form_data,
                    contentType: false, //multipart/form-data
                    processData: false,
                    success: function(output){
                      $("#message").val('');
                      $("#subject").val('');
                      // $("#subject").append(output.success);
                      $("#thanks").fadeIn();
                   }
                });
            }

            function remind(id){
              $.ajaxSetup({
                  headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
              });
              $.ajax({
                url:"{{route('remind')}}",
                  method:'POST',
                  data: {id:id},
                  success: function(output){
                    $("#mailSuccess").text('');
                    $("#mailSuccess").append(output.success);
                      $("#mailSuccess").fadeIn();
                    }
            });
            }

</script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/nav.css') }}">
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<body style="background-color:rgb(245,245,255)">

  <div class="modal" id="modalAlert" tabindex="-1" style="">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
      </div>
      <div class="modal-body" id="modalbody">

      </div>
      <div class="modal-footer" >
        <button type="button" id="closemodal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <div id="modalfooter">
          <button type="button" class="btn btn-primary" name="button">kj</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modalProducts" tabindex="-1" style="width:100%;position:absolute">
<div class="modal-dialog" style="width:100%">
  <center>
  <div class="modal-content" style="width:200rem;margin-left:-160px">
    <div class="modal-header">
      <h5 class="modal-title">Products</h5>
    </div>
    <div class="modal-body"  id="modalProducts">
      @php
        $products=DB::table('product')
          ->select('product.id','product.name','product.photo','product.description','product.price','product.width','product.height')
          ->where('userId','=',$user->id)
          ->get();
      @endphp
      <table class="" style="">
        <tr>
        @foreach($products as $p)
          <td>
          <div class='box' style="">
              <div class='front' style='border-radius:50px'>
                @if($p->photo !=null && file_exists(public_path()."/img/$p->photo"))
                  <img src="{{asset('img/'.$p->photo)}}" style='width:300px;height:240px;border-radius:20px' alt='' id="">
                @else
                  <h2 style="font-size:20px">No Preview Photo</h2>
                @endif

              </div>
              <div class='back'>
                <a href="/product/{{$p->id}}" style='font-size:30px;color:#8e221a;padding:0 0 0 0;font-style:italic' id='iname'><b>{{$p->name}}</b></a>
                <br>
                <p><b>Size:</b> {{$p->height}} x {{$p->width}}<br></p>
                <p><strong>{{$p->price}}$</strong></p>
              </div>
            </div>
          </td>
          @if($loop->iteration % 2 == 0)
            </tr><tr>
          @endif
          @endforeach
      </table>
    </div>
    <div class="modal-footer" >
      <button type="button" id="closemodalProducts" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
</center>
<header id="header" style="position: fixed;width:100%;z-index: 1000;top:0;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" >
       <div class="container" >
           <button class="navbar-toggler" id="toggleNavBar" type="button" data-bs-toggle="collapse" data-bs-target="#mynav"
               aria-controls="mynav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>
           <a class="navbar-brand" href="#">
               <div class="d-flex">
                   <div class="d-flex align-items-center logo bg-white">
                     <img src="{{asset('css/icons/pen.png')}}" style="width:44px;height:46px">
                   </div>
                   <div class="ms-3 d-flex flex-column">
                     <div class="h4">{{$user->name}}</div>
                       <div class="fs-6">Profile</div>
                   </div>
               </div>
           </a>
           <div class="collapse navbar-collapse" id="mynav">
               <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                   <li class="nav-item">
                       <a class="nav-link active" aria-current="page" href="{{route('home')}}">Homepage
                         <span class="fas fa-th-large px-1"></span></a>
                   </li>
                   <li class="nav-item">
                     @if(session()->has('clientemail'))
                     @if($subscribed=='no')
                        <a class="nav-link" id="subscribe" style="cursor:pointer">Follow</a>
                      @else
                        <a class="nav-link" id="unsubscribe" style="cursor:pointer">Unfollow</a>
                      @endif
                      @endif
                   </li>

                   @if(session()->has('clientemail'))
                   <li class="nav-item">
                       <form action="post">
                         <div class="alerts" style="display:none" ></div>
                         <table width="100%">
                           <tr>
                            <td style="text-align:center">
                             <div class="form-group">
                               <div class="report">
                                 <a  class="nav-link" id="first-report" style="cursor:pointer">Report Supplier</a>
                               </div>
                               <div class="reason-report" style="display:none">
                                 <textarea placeholder="Why reporting?" id="reportmessage" style="width:100%" class="form-control"></textarea>
                                 <button type="button" onclick="report({{$user->id}})" class="btn btn-danger" id="last-report" style="background-color:#dc3545;color:#fff;cursor:pointer">Report</button>
                               </div>
                               <div class="block" style="display:none">
                                 <h4>Do you want to block this supplier? You will be returned to homepage.</h4>
                                 <button type="button" onclick="block({{$user->id}})" class="btn btn-info" style="background-color:#17a2b8;color:#fff;cursor:pointer">Block</button>
                                 <button type="button" id="cancel-block" class="btn btn-light">No</button>
                               </div>
                             </div>
                           </td>
                          </tr>
                       </table>
                     </form>
                   </li>
                   @endif

                   <li class="nav-item" style="float:right">
                     <form class="" action="" method="post">
                       @csrf
                       <center>
                       <a class="nav-link"><div class="ratingDiv" style="margin-top:">Rate {{$user->name}}:
                           <div class="rating">
                             <input type="radio" class="ratingrb" name="rating" value="5" id="5"><label for="5">☆</label>
                             <input type="radio" name="rating" class="ratingrb" value="4" id="4"><label for="4">☆</label>
                             <input type="radio" name="rating" class="ratingrb" value="3" id="3"><label for="3">☆</label>
                             <input type="radio" name="rating" class="ratingrb" value="2" id="2"><label for="2">☆</label>
                             <input type="radio" name="rating" class="ratingrb" value="1" id="1"><label for="1">☆</label>
                           </div>
                       </div></a>
                     </center>
                     </form>
                   </li>
               </ul>
           </div>
         </div>
       </nav>
      </div>
    </header>


    <center>
        <div class="" style="float:center;margin-top:130px">
          <div class="" style="width:650px;padding:0  30px 30px 30px; background-color:#fff; border-radius:10px; ">
            <div class="" style="background-color:;border-radius:5px;padding:20px;">
              <button id="userid" style="display:none" value="{{$user->id}}" disabled></button>
              <div class="" style="width:500px; margin-top:-15px">
                <div class="" style="background-color:#444;height:130px;border-radius:10px;">
                  <img src="{{asset('profiles/'.$user->photo)}}" alt="" style="margin-top: 30px;width:250px;height:230px;border-radius:130px;box-shadow: 0 5px 3px rgba(0,0,0, 0.2 ), 0 -5px 5px rgba(0,0,0, 0.200);">
                </div>
              </div>
              <div class="" style="margin-top:200px; ">
                <div class="" style=" margin-left: -350px;">
                  <h4>About</h4>
                </div>
                <div class="" style="background-color:rgb(240,240,240); padding: 40px; width:400px; border-radius: 5px;">
                  <center><p style="color:black;font-weight: 600;font-size:18px">{{$user->aboutme}}</p></center>
                </div>
              </div>
            </div>
            <div class="" style=" width: 450px;display: flex;">
              <div class="" style="flex:1;">
                <h5>Followers</h5>
                <p>{{$followers}}</p>
              </div>
              <div class="" style="flex: 1;">
                <h5>Products</h5>
                <p>{{$nbPro}}</p>
              </div>
              <div class="" style="flex: 1;">
                <h5>Rating</h5>
                <p>{{$ratings}}</p>
              </div>
            </div>

          </div>

          <div class="" style="display:none">
            @if($clientId)
            @foreach($clientId as $c)
              <button disabled value="{{$c->id}}" id="clientid"></button>
            @endforeach
            @endif
          </div>
        </div>
    </center>


    <div class="" style="border-radius:10px;padding:0 350px;margin:6px;float:center; display: flex;align-items: center;overflow-x: auto; scroll-behavior: auto;;" >
        @foreach($userproducts as $pro)
          @php
            $prod=DB::table('product')
              ->select('product.id','product.name','product.photo','product.description','product.price','product.width','product.height')
              ->where('id','=',$pro->id)
              ->get();
          @endphp
          @foreach($prod as $p)
            <div class='box' style="flex:1;">
                <div class='front' style='border-radius:50px'>
                  @if($p->photo !=null && file_exists(public_path()."/img/$p->photo"))
                    <img src="{{asset('img/'.$p->photo)}}" style='width:300px;height:240px;border-radius:20px' alt='' id="">
                  @else
                    <h2 style="font-size:20px">No Preview Photo</h2>
                  @endif

                </div>
                <div class='back'>
                  <a href="/product/{{$p->id}}" style='font-size:30px;color:#8e221a;padding:0 0 0 0;font-style:italic' id='iname'><b>{{$p->name}}</b></a>
                  <br>
                  <p><b>Size:</b> {{$p->height}} x {{$p->width}}<br></p>
                  <p><strong>{{$p->price}}$</strong></p>
                </div>
              </div>
            @endforeach
          @endforeach
        </div>
          @if($nbPro>'5')
            <div class="" style="float:right">
              <div class="">
                <button type="button" id="moreProducts" style='margin-left:5px;padding:10px;border:0;background-color:transparent;color:blue'>View More</button>
              </div>
            </div>
          @endif



  <section class="ftco-section" style="padding:50px 0 50px 0;">
    <div class="contact-form" style="width:100%" >
      <center>
          <div class="container" style="background-image: url({{ asset('css/back.jpg') }});background-size:cover;width:90%;padding:">
            <div class="row justify-content-center">
              <div class="col-md-6 text-center mb-5">
                <span style='font-size:35px;font-weight:700;letter-spacing:;border-bottom: 2px solid #aac;'>Contact Us</span>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col-lg-10">
                <div class="wrapper img" style="background-color:rgb(245,245,255);border-radius:5px">
                  <div class="row">
                    <div class="col-md-9 col-lg-7">
                      <div class="contact-wrap w-100 p-md-5 p-4">
                        <h3 class="mb-4">Get in touch with us</h3>
                        <div id="form-message-warning" class="mb-4"></div>
                        <div class="mb-4" id="thanks" style="display:none;border-bottom:1px solid #aad">
                          <p><strong>Your message was sent, thank you!</strong></p>
                        </div>
                        <form action="" method="post">
                          @csrf
                          <div class="row">
                            <input type="email" name="to" id="to" placeholder="Recipient's Email" value="{{$user->email}}" style="display:none">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="label" for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="emailname" placeholder="Name">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="label" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="label" for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="label" for="#">Message</label>
                                <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message"></textarea>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <button type="button" value="Send Message" onclick="sendEmail({{$user->id}})" class="btn btn-secondary">Send Message</button>
                                <div class="submitting"></div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>

      <div>
        <div class="form-header text-center">
      </div>
      <center>

  </div>
  <br>

  </center>




</body>



<footer class="sticky-footer bg-white" style="border-top: 1px solid #aaa;text-align: center;padding: 25px;background-color:#fff;font-weight:700">
       <div class="container my-auto">
         <div class="copyright text-center my-auto">
           <span> &copy; <script> document.write(new Date().getFullYear()); </script> - Developed by Bilal Kaderi</span>
         </div>
       </div>
     </footer>
