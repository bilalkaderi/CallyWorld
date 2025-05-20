<?php
    $dt=now()->format('Y-m-d H:i:s');
    $user = DB::table('users')->where('email','=',Auth::user()->email)->first();
    $id=Auth::user()->id;
    $userprod = DB::select("select * from product where userId=$id");
    $orders = DB::select("select orders.id,orders.productId,orders.clientId,orders.total_price,orders.quantity,orders.status,orders.created_at,orders.expecting_delivery_date,
    product.id as productId,product.name,product.photo,product.promotion,product.stock,product.expecting_delivery_time,product.name as pname,product.price,client.name as cname,client.phone from orders
          left join client on orders.clientId=client.id
          left join product on orders.productId=product.id where orders.visible='yes' and orders.userId=$id and orders.created_at >= NOW() - INTERVAL 300 DAY order by orders.id desc");

    $ordersNb=DB::table('orders')->where('userId','=',$id)->where('status','=','pending')->count();
    $subscribers=json_decode($user->subscribers, true);
    $followers=count($subscribers);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
</script>


<style media="screen">
    .chart {
    height: auto;
    width: 100%;
    align-items: center;
    justify-content: center;
    padding: 10px;
    padding-top: 20px;
    }


    .main-container {
    background: #444e63;
    color: #abafc6;
    width: 100%;

    border-radius: 10px;
    padding: 20px;
    height: auto;
    }

    .year-stats {
    white-space: nowrap;
    max-height: 770px;
    overflow: hidden;
    }

    .year-stats:hover {
    overflow-x: auto;
    }


    /* Track */
    ::-webkit-scrollbar-track {
    background: #444e80;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #abafc6;
    border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
    background: #5397d6;
    }

    .month-group {
    cursor: pointer;
    max-width: 700px;
    height: auto;
    padding: 20px;
    margin: 10px;
    display: inline-block;
    }

    .bar {
    background-color: #abafc6;
    width: 20px;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: 0.3s all;
    }

    .month-group:hover .bar,
    .selected .bar {
    background: #5397d6;
    }

    .month-group:hover p,
    .selected p {
    color: #fff;
    }

    .stats-info {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    position: relative;
    }

    .info p {
    margin-bottom: 10px;
    }
    .info strong {
      color: white;

    }

    .info span {
    color: white;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3-beta1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
<link rel="stylesheet" href="<?php echo e(asset('css/user/bootstrap.min.css')); ?>">

<title><?php echo e(Auth::user()->name); ?></title>
<meta name="_token" content="<?php echo e(csrf_token()); ?>">

<style media="screen">
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
    .box{
      width: 300px; height: 240px; position: center;
      border-radius: 20px;
      margin: 25px;
    }

    .box .front,
    .box .back{
      position: absolute;
      width: 300px; height: 240px; background: white;
      backface-visibility: hidden; cursor: default;
      border-radius: 20px;
      box-shadow: 0 5px 3px rgba(0,0,0, 0.2 ), 0 -5px 5px rgba(0,0,0, 0.200);
      transition: transform 0.4s;
      text-align: center;
    }

    .box .back{
      display: flex;
      justify-content: center;
      align-items: center; flex-direction: column;
      transform: perspective(800px) rotateX(-180deg);
      color:black;
    }
    .box .back a{

    }


    .box .back p{
      margin: 10px 20px;
      text-align: center;
    }

    .box.front img{
      width: 100%;
      height: 100%;
      border-radius:20px; position: absolute;
    }

    .btnorder{
      padding:5px 5px 5px 5px;
      background-color:rgba(100,5,0,0.6);
      border-color:white;
      border-radius:6px;
      color:white;
    }
    .btnorder:hover,
    .btnorder:focus{
      background-color: rgba(100,5,0,0.9);
      transition: 0.3s;
      color:white;
      cursor: pointer;
    }

    .box:hover .front{
      transform: perspective(800px) rotateX(180deg);
    }

    .box:hover .back{
      border-radius: 20px; transform: perspective(800px) rotateX(0);
    }
    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    nav {
      min-height: 70px;
    }

    nav .navbar-brand .logo {
      padding: 10px 15px;
      border-radius: 8px;
    }

    nav .navbar-brand .logo .h2 {
      margin: 0;
    }

    nav .navbar-brand .h4 {
      margin-bottom: 0px;
      font-weight: 900;
    }

    nav .navbar-brand .fs-6 {
      font-size: 0.88rem !important;
    }

    nav ul li {
      padding: 0 20px;
    }

    .navbar-light .navbar-nav .nav-link {
      color: #333;
    }

    .navbar-light .navbar-nav .nav-link:hover {
      color: #4e2296;
    }

    .navbar-light .navbar-nav .nav-link.active {
      color: #451296;
    }

    nav ul li a .cart {
      padding: 4px 6px;
      border-radius: 6px;
      position: relative;
      display: inline;
    }

    nav ul li a .cart::after {
      position: absolute;
      content: "";
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: #ff5252;
      top: -1px;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    #sidebar {
      padding: 15px 0px 15px 0px;
      border-radius: 10px;
    }

    #sidebar .h4 {
      font-weight: 500;
      padding-left: 15px;
    }

    #sidebar ul {
      list-style: none;
      margin: 0;
      padding-left: 0rem;
    }

    #sidebar ul li {
      padding: 10px 0;
      display: block;
      padding-left: 1rem;
      padding-right: 1rem;
      border-left: 5px solid transparent;
    }

    #sidebar ul li.active {
      border-left: 5px solid #ff5252;
      background-color: #44007c;
    }

    #sidebar ul li a {
      display: block;
    }

    #sidebar ul li a .fas,
    #sidebar ul li a .far {
      color: #ddd;
    }

    #sidebar ul li a .link {
      color: #fff;
      font-weight: 500;
    }

    #sidebar ul li a .link-desc {
      font-size: 0.8rem;
      color: #ddd;
    }

    #main-content {
      padding: 30px;
      border-radius: 15px;
    }

    #main-content .h5,
    #main-content .text-uppercase {
      font-weight: 600;
      margin-bottom: 0;
    }

    #main-content .h5+div {
      font-size: 0.9rem;
    }

    #main-content .box {
      padding: 10px;
      border-radius: 6px;
      width: 170px;
      height: 90px;
    }

    #main-content .box img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    #main-content .box .tag {
      font-size: 0.9rem;
      color: #000;
      font-weight: 500;
    }

    #main-content .box .number {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .order {
      padding: 10px 30px;
      min-height: 150px;
    }

    .order .order-summary {
      height: 100%;
    }

    .order .blue-label {
      background-color: #aeaeeb;
      color: #0046dd;
      font-size: 0.9rem;
      padding: 0px 3px;
    }

    .order .green-label {
      background-color: #a8e9d0;
      color: #008357;
      font-size: 0.9rem;
      padding: 0px 3px;
    }
    .order .red-label {
      background-color: #9b1a21;
      color: white;
      font-size: 0.9rem;
      padding: 0px 3px;
    }

    .order .fs-8 {
      font-size: 0.85rem;
    }

    .order .rating img {
      width: 20px;
      height: 20px;
      object-fit: contain;
    }

    .order .rating .fas,
    .order .rating .far {
      font-size: 0.9rem;
    }

    .order .rating .fas {
      color: #daa520;
    }

    .order .status {
      font-weight: 600;
    }

    .order .btn.btn-primary {
      background-color: #fff;
      color: #4e2296;
      border: 1px solid #4e2296;
    }

    .order .btn.btn-primary:hover {
      background-color: #4e2296;
      color: #fff;
    }

    .order .progressbar-track {
      margin-top: 20px;
      margin-bottom: 20px;
      position: relative;
    }

    .order .progressbar-track .progressbar {
      list-style: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-left: 0rem;
    }

    .order .progressbar-track .progressbar li {
      font-size: 1.5rem;
      border: 1px solid #333;
      padding: 5px 10px;
      border-radius: 50%;
      background-color: #dddddd;
      z-index: 100;
      position: relative;
    }

    .order .progressbar-track .progressbar li.green {
      border: 1px solid #007965;
      background-color: #d5e6e2;
    }

    .order .progressbar-track .progressbar li::after {
      position: absolute;
      font-size: 0.9rem;
      top: 50px;
      left: 0px;
    }

    #tracker {
      position: absolute;
      border-top: 1px solid #bbb;
      width: 100%;
      top: 25px;
    }

    #step-1::after {
      content: 'Placed';
    }

    #step-2::after {
      content: 'Accepted';
      left: -10px;
    }

    #step-3::after {
      content: 'Packed';
    }

    #step-4::after {
      content: 'Shipped';
    }

    #step-5::after {
      content: 'Delivered';
      left: -10px;
    }



    /* Backgrounds */
    .bg-purple {
      background-color: #55009b;
    }

    .bg-light {
      background-color: #f0ecec !important;
    }

    .green {
      color: #007965 !important;
    }

    /* Media Queries */
    @media(max-width: 1199.5px) {
      nav ul li {
          padding: 0 10px;
      }
    }

    @media(max-width: 500px) {
      .order .progressbar-track .progressbar li {
          font-size: 1rem;
      }

      .order .progressbar-track .progressbar li::after {
          font-size: 0.8rem;
          top: 35px;
      }

      #tracker {
          top: 20px;
      }
    }

    @media(max-width: 440px) {
      #main-content {
          padding: 20px;
      }

      .order {
          padding: 20px;
      }

      #step-4::after {
          left: -5px;
      }
    }

    @media(max-width: 395px) {
      .order .progressbar-track .progressbar li {
          font-size: 0.8rem;
      }

      .order .progressbar-track .progressbar li::after {
          font-size: 0.7rem;
          top: 35px;
      }

      #tracker {
          top: 15px;
      }

      .order .btn.btn-primary {
          font-size: 0.85rem;
      }
    }

    @media(max-width: 355px) {
      #main-content {
          padding: 15px;
      }

      .order {
          padding: 10px;
      }
    }
    .confirmed,
    .Packed,
    .Delivered{
      display: none;
    }

      .ribbon {
    width: 150px;
    height: 150px;
    overflow: hidden;
    position: absolute;
  }
  .ribbon::before,
  .ribbon::after {
    position: absolute;
    z-index: -1;
    content: '';
    display: block;
    border: 5px solid #2980b9;
  }
  .ribbon span {
    position: absolute;
    display: block;
    width: 225px;
    padding: 15px 0;
    background-color: #3498db;
    box-shadow: 0 5px 10px rgba(0,0,0,.1);
    color: #fff;
    text-shadow: 0 1px 1px rgba(0,0,0,.2);
    text-transform: uppercase;
    text-align: center;
  }

  /* top left*/
  .ribbon-top-left {
    top: -10px;
    left: -10px;
  }
  .ribbon-top-left::before,
  .ribbon-top-left::after {
    border-top-color: transparent;
    border-left-color: transparent;
  }
  .ribbon-top-left::before {
    top: 0;
    right: 0;
  }
  .ribbon-top-left::after {
    bottom: 0;
    left: 0;
  }
  .ribbon-top-left span {
    right: -25px;
    top: 30px;
    transform: rotate(-45deg);
  }

  /* top right*/
  .ribbon-top-right {
    top: -10px;
    right: -10px;
  }
  .ribbon-top-right::before,
  .ribbon-top-right::after {
    border-top-color: transparent;
    border-right-color: transparent;
  }
  .ribbon-top-right::before {
    top: 0;
    left: 0;
  }
  .ribbon-top-right::after {
    bottom: 0;
    right: 0;
  }
  .ribbon-top-right span {
    left: -25px;
    top: 30px;
    transform: rotate(45deg);
  }

  /* bottom left*/
  .ribbon-bottom-left {
    bottom: -10px;
    left: -10px;
  }
  .ribbon-bottom-left::before,
  .ribbon-bottom-left::after {
    border-bottom-color: transparent;
    border-left-color: transparent;
  }
  .ribbon-bottom-left::before {
    bottom: 0;
    right: 0;
  }
  .ribbon-bottom-left::after {
    top: 0;
    left: 0;
  }
  .ribbon-bottom-left span {
    right: -25px;
    bottom: 30px;
    transform: rotate(225deg);
  }

  /* bottom right*/
  .ribbon-bottom-right {
    bottom: -10px;
    right: -10px;
  }
  .ribbon-bottom-right::before,
  .ribbon-bottom-right::after {
    border-bottom-color: transparent;
    border-right-color: transparent;
  }
  .ribbon-bottom-right::before {
    bottom: 0;
    left: 0;
  }
  .ribbon-bottom-right::after {
    top: 0;
    right: 0;
  }
  .ribbon-bottom-right span {
    left: -25px;
    bottom: 30px;
    transform: rotate(-225deg);
  }
</style>
<script type="text/javascript">
    var loadFile1 = function(event) {
      var image = document.getElementById('photoNew');
      image.src = "";
      image.src = URL.createObjectURL(event.target.files[0]);
    };
    var loadFile2 = function(event) {
      var image = document.getElementById('profilephoto');
      image.src = "";
      image.src = URL.createObjectURL(event.target.files[0]);
    };
    var loadFile = function(event) {
      var image = document.getElementById('photoView');
      image.src = "";
      image.src = URL.createObjectURL(event.target.files[0]);
    };
    var loadFileVideo = function(event) {
      var video = document.getElementById('videoNew');
      video.src = "";
      video.src = URL.createObjectURL(event.target.files[0]);
    };

    $(document).ready(function(){
      $('#cardNumber').on('input', function() {
          // Remove any existing spaces
          let cardNumber = $(this).val().replace(/\s/g, '');

          // Insert a space after every four characters
          cardNumber = cardNumber.replace(/(\d{4})(?=\d)/g, '$1 ');

          // Update the input value
          $(this).val(cardNumber);
      });

      $("#addProduct").click(function(){
        $("#addModal").fadeIn();
      });
      $("#addVideo").click(function(){
        $("#addVideoModal").fadeIn();
      });
      $("#updateAccount").click(function(){
        $("#accountModal").fadeIn();
      });

      $("#orderType").change(function(){
        var type=$(this).val();
        $('.pending').fadeOut();$('.confirmed').fadeOut();$('.Packed').fadeOut();$('.Delivered').fadeOut();
        $('.'+type).fadeIn();
      });

      $(".btn-close").click(function(){
        var id=$(this).val();
        $("#modalUser"+id).toggle();
        $("#myModalUpdate").fadeOut();
        $("#addModal").fadeOut();
        $("#addVideoModal").fadeOut();
        $("#accountModal").fadeOut();
      });
      $(".btn-primary").click(function(){
        var id=$(this).val();
        // alert(id);
        $("#modalUser"+id).toggle();
      });

      $("#toggleNavBar").click(function(){
        $(".navbar-collapse").toggle();
      });

    $("#addprod").click(function(e){
      e.preventDefault();
        if($("#name").val().trim() == "" || $("#price").val().trim() == ""
                || $("#width").val().trim() == "" || $("#height").val().trim() == "")
                {
                  alert("Please fill all information");
                  return;
                }
                $.ajaxSetup({
                    headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
                });

                var form_data=new FormData();
                form_data.append("photo",document.getElementById('photo').files[0]);
                form_data.append("name",$("#name").val());
                form_data.append("stock",$("#stock").val());
                form_data.append("type",$("#type").val());
                form_data.append("delivery_time",$("#delivery_time").val());
                form_data.append("delivery_date_type",$("#delivery_type").val());
                form_data.append("price",$("#price").val());
                form_data.append("height",$("#height").val());
                form_data.append("width",$("#width").val());
                form_data.append("description",$("#description").val());

        $.ajax({
            url:"<?php echo e(route('addproduct')); ?>",
            method:'POST',
            data:form_data,
            contentType: false, //multipart/form-data
            processData: false,
            success: function(output){
                $("#addModal").fadeOut();
                $("#productstable").append(output.row);
                $("#name").val("");
                $("#price").val("");
                $("#stock").val("");
                $("#height").val("");
                $("#width").val("");
                $("#description").val("");
           }
        });
    });

    $("#updateprod").click(function(e){
      e.preventDefault();
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });

      var form_data=new FormData();
      form_data.append("id",$("#theidU").val());
      form_data.append("photo",document.getElementById('photoU').files[0]);
      form_data.append("name",$("#nameU").val());
      form_data.append("type",$("#typeU").val());
      form_data.append("stock",$("#stockU").val());
      form_data.append("price",$("#priceU").val());
      form_data.append("height",$("#heightU").val());
      form_data.append("width",$("#widthU").val());
      form_data.append("promotion",$("#promotion").val());
      form_data.append("description",$("#descriptionU").val());

        $.ajax({
            url:"<?php echo e(route('updateProduct')); ?>",
            method:'POST',
            data:form_data,
            contentType: false, //multipart/form-data
            processData: false,
            success: function(output){
                $("#myModalUpdate").fadeOut();
                // $(".alerts").text('');
                // $(".alerts").append(output.success);
                // $(".alerts").fadeIn();
                location.reload();
              }
    });
    });


  });


  function returnProduct(id){
    $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
      $.ajax({
          url:"<?php echo e(route('returnProduct')); ?>",
          method:'get',
          data: {id:id},
          success: function(output){
              // $("#user_"+id).fadeOut();
              $("#myModalUpdate").fadeIn();
              $("#nameU").val(output.name);
              $("#typeU").val(output.type);
              $("#priceU").val(output.price);
              $("#descriptionU").val(output.description);
              $("#widthU").val(output.width);
              $("#heightU").val(output.height);
              $("#stockU").val(output.stock);
              $("#promotion").val(output.promotion);
              $("#photoView").attr("src","img/"+output.photo);
              $("#theidU").val(output.id);
            }
  });
  }

    function deleteProduct(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"<?php echo e(route('deleteProduct')); ?>",
            method:'get',
            data: {id:id},
            success: function(output){
                $("#box_"+id).fadeOut();
              }
   });
 }

 function confirmOrder(id){
   $.ajaxSetup({
       headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
   });
   var delTime=$("#delTime").val();
     $.ajax({
         url:"<?php echo e(route('confirmOrder')); ?>",
         method:'POST',
         data: {id:id,delTime:delTime},
         success: function(output){
           $("#modalUser"+id).fadeOut();
             $("#alertConfirm_"+id).text('');
             $("#alertConfirm_"+id).append(output.success);
             $("#alertConfirm_"+id).fadeIn('');
           }
         });
     }

     function ready(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
         $.ajax({
             url:"<?php echo e(route('ready')); ?>",
             method:'POST',
             data: {id:id},
             success: function(output){
                $("#modalUser"+id).fadeOut();
                $("#alertConfirm_"+id).text('');
                $("#alertConfirm_"+id).append(output.success);
                $("#alertConfirm_"+id).fadeIn('');
               }
             });
         }

     function updateUser(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
       var form_data=new FormData();
       form_data.append("id",id);
       form_data.append("name",$("#updatename").val());
       form_data.append("phone",$("#updatephone").val());
       form_data.append("aboutme",$("#updateaboutme").val());
       form_data.append("address",$("#updateaddress").val());
       form_data.append("cardNumber",$("#cardNumber").val());
       // form_data.append("photo",document.getElementById("profilephoto").files[0]);
         $.ajax({
             url:"<?php echo e(route('updateUser')); ?>",
             method:'POST',
             data:form_data,
             contentType: false, //multipart/form-data
             processData: false,
             success: function(output){
                $("#alertUpdate").text('');
                 $("#alertUpdate").append(output.success);
                 $("#alertUpdate").fadeIn();
               }
     });
     }

     function changePassword(id){
     if($("#newpassword").val().trim() != $("#newpassword_confirmation").val().trim()){
      $("#alertUpdate").text('');
       $("#alertUpdate").append('Password confirmation is not correct!');
       $("#alertUpdate").fadeIn();
       return;
     }
     $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
     });
     var oldpassword=$("#oldpassword").val();
     var newpassword=$("#newpassword").val();
      $.ajax({
          url:"<?php echo e(route('changeUserPassword')); ?>",
          method:'post',
          data: {id:id,oldpassword:oldpassword,newpassword:newpassword},
          success: function(output){
             $("#alertUpdate").text('');
              $("#alertUpdate").append(output.success);
              $("#alertUpdate").fadeIn();
            }
     });
     }


   function returnSales(){
     $.ajaxSetup({
        headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
     });
     var dateFrom=$("#dateFrom").val();
     var dateTo=$("#dateTo").val();
      $.ajax({
          url:"<?php echo e(route('returnSales')); ?>",
          method:'post',
          data: {dateTo:dateTo,dateFrom:dateFrom},
          success: function(output){
             $("#salesData").text('');
              $("#salesData").append(output.success);
              $("#stockData").fadeOut();
            }
     });
     }

</script>
<section class="" style="background-color:rgb(245,245,255)">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container">
         <button class="navbar-toggler" id="toggleNavBar" type="button" data-bs-toggle="collapse" data-bs-target="#mynav"
             aria-controls="mynav" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <a class="navbar-brand" href="#">
             <div class="d-flex">
                 <div class="d-flex align-items-center logo bg-white">
                   <img src="<?php echo e(asset('css/icons/pen.png')); ?>" style="width:44px;height:46px">
                 </div>
                 <div class="ms-3 d-flex flex-column">
                   <div class="h4">CallyWorld</div>
                     <div class="fs-6">My Profile</div>
                 </div>
             </div>
         </a>
         <div class="collapse navbar-collapse" id="mynav">
             <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                 <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="<?php echo e(route('home')); ?>">Homepage<span
                             class="fas fa-th-large px-1"></span></a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#" id="addProduct">Add Product</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#" id="addVideo">Add Video</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="#" id="updateAccount">Update Account</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" style="cursor:default"><span class="fas fa-user pe-2"></span> Hello <?php echo e($user->name); ?></a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" style=""><center><?php echo e($followers); ?><div style="border-bottom:1px solid #444;">Followers</div></center></a>
                 </li>
             </ul>
         </div>
     </div>
 </nav>

<!-- -------------------------------------Update Account-------------------------------------- -->
<div id="accountModal" class="modal" style="margin-top:5px">
  <span id="spanU" class="btn-close">&times;</span>
  <form class="" action="" method="post">
    <?php echo e(csrf_field()); ?>

    <center>
      <div class="" style="padding:40px;background-color:rgba(245,240,250,0.9);border-radius:6px" id="editprofile">
        <div class="profchart"  style="padding:70px;width:auto;background-color:white;border-radius:6px">
           <div class="">
             <div class="">
              <div class='' style=''>
                <div class=''>
                  <p><span>Edit profile</span></p>
                  <span>
                      <div class="alert alert-danger" id="alertUpdate" style="display:none"></div>
                        <center>
                          <table style='width:100%'>
                          <tr>
                            <td></td>
                            <td></td>
                            <td style="text-align:right" colspan="3" rowspan="5">
                              <label for='photo'><img src="<?php echo e(asset('profiles/'.$user->photo)); ?>" class='photo' name='photo' title='Choose photo' style="border-radius:100px;height:300px;width:300px" id='profilephoto'/></label><br>
                              <input type='file' name='photo' style='visibility: hidden' accept='image/*' id='photo' onchange='loadFile2(event)' >
                            </td>
                          </tr>
                          <th>My Name</th><th>Phone Number</th>
                          <tr>
                            <td><input id='updatename' class='form-control' type='text' value='<?php echo e($user->name); ?>' name='clientname' /></td>
                            <td><input id='updatephone' class='form-control' type='text' value='<?php echo e($user->phone); ?>' name='clientphone' /></td>
                          </tr>
                          <tr>
                            <th>My Address</th>
                            <td><input id='updateaddress' class='form-control' placeholder="My Address" type='text' value='<?php echo e($user->address); ?>' name='clientaddress' /></td>
                          </tr>
                          <tr>
                            <th>Card Number</th>
                            <td>
                              <input type="text" class="form-control" id="cardNumber" maxlength="19" value="<?php echo e($user->card_number); ?>" />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2"><textarea id='updateaboutme' class='form-control' type='text' style="height:200px;width:100%" placeholder="Add some info about yourself!" name='clientphone' ><?php echo e($user->aboutme); ?></textarea></td>
                          </tr>
                        </table>
                        </center>
                        <center><button type='button' onclick='updateUser(<?php echo e($user->id); ?>)' id='buttonupdate' style="color:green" class='btn btn-light'>Save &#10004;</button></center></span>
                      </div>
                      <div class='' style=""><p>Do you want to change your password?</p>
                        <span class='month'><input class='form-control' type='password' value='' id='oldpassword' placeholder='Old Password' style="float:left;width:35%"/></span>
                        <span class='month'><input type='password' class='form-control' value='' id='newpassword' placeholder='New Password' style="float:left;width:35%"/></span>
                        <span class='month'><input type='password' class='form-control' value='' id='newpassword_confirmation' placeholder='Confirm New Password' style="float:left;width:35%"/></span><br>
                        <button type='button' id='changepassword' style="" class='btn btn-light' onclick="changePassword(<?php echo e($user->id); ?>)">Change Password</button>
                      </div>
                      <div style="float:right;margin-top:30px">
                        <a href="<?php echo e(route('signout')); ?>" value='Log out' name='logout' style="" class='logout'/>Signout</a></center>
                      </div>
                  </div>

            </div>
          </div>
      </div>
    </div>
  </center>
  </form>
</div>
<!-- -------------------------------------End Update Account-------------------------------------- -->

 <div id="addModal" class="modal" style="margin-top:55px">
   <span id="span" class="btn-close">&times;</span>
   <center>
     <form class="" action="" method="post">
     <?php echo e(csrf_field()); ?>

     <div class="" style="padding:40px;background-color:rgba(245,240,250,0.9);border-radius:6px" id="addproduct">
       <table align="center" class="table" style="width:70%">
         <tr>
           <td colspan="3" style="font-weight:900;font-size:26px;color:#8e221a;">New Product</td>
         </tr>
         <tr>
           <td><input type="text" name="name" value="" placeholder="Name of Product" class="form-control" id="name" required></td>
              <input type="text" name="theid" value="" style="visibility:hidden" readonly>
            <td><input type="number" name="price" value="" placeholder="Price $" class="form-control" id="price" required></td>
            <td><select class="form-control" name="type" id="type" required>
               <?php $cats=DB::select("select * from categories");?>
               <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <option class="option" value="<?php echo e($cats->id); ?>"><?php echo e($cats->name); ?></option>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <option class="optionother" value="3">Other</option>
             </select>
            </td>
          </tr>
          <tr>
           <td><input type="number" name="height" value="" placeholder="Height" id="height" class="form-control"></td>
           <td><input type="number" name="width" value="" placeholder="Width" id="width" class="form-control"></td>
           <td><input type="number" name="stock" value="" placeholder="In-Stock" id="stock" class="form-control"></td>
           <tr><td style="font-weight:800;font-size:23px;color:#8e221a">Delivery Time</td>
               <td style="align-items:right"><select id="delivery_time" class="form-control" style="width:50px">
               <?php
                 for($i=1;$i<=10;$i++){
                   echo"<option value='$i'>$i</option>";
                 }
               ?>
             </select>
           </td>
             <td><select id="delivery_type" class="form-control" style="width:auto">
               <option value='Day'>Day</option>
               <option value='Week'>Week</option>
             </select>
           </td>
         </tr>
         </tr>
         <tr>
           <td>
              <label for='photonew' class='form-label'>
                <img  class='photo' name='photoNew' title='Choose photo' id='photoNew' style="width:120px;height:130px;border:0;border-radius:4px"/>
              </label>
              <input type='file' name='photonew' style='visibility:hidden' id='photonew' onchange='loadFile1(event)' class='form-control'>
           </td>
         </tr>
         <tr>
           <th style="font-weight:800;font-size:23px;color:#8e221a;" >Description</th>
         </tr>
         <tr>
           <td colspan="2"><input type="text" name="description" value="" id="description" placeholder="Describe the product with few words." class="form-control"></td>
           <td><button type="button" name="addprod" id="addprod" class="form-control" style="cursor:pointer;width:60" >Add</button></td>
        <tr>
       </table>
     </div>
   </form></center>
 </div>

 <!-- ////////////////////Update -->
 <div id="myModalUpdate" class="modal" style="margin-top:55px">
   <span id="spanU" class="btn-close">&times;</span>
   <form class="" action="" method="post">
     <?php echo e(csrf_field()); ?>

     <center><div class="" style="padding:40px;background-color:rgba(245,240,250,0.9);border-radius:6px" id="updateproduct">
       <table align="center" class="table" style="width:70%">
         <tr>
           <td style="color:#8e221a;font-weight:900;font-size:26px;" colspan="3">Update Product</td>
         </tr>
         <tr>
           <td><input type="text" value="" placeholder="Name of Product" class="form-control" id="nameU" required></td>
              <input type="text" id="theidU" value="" style="visibility:hidden" readonly>
            <td><input type="number" value="" placeholder="Price $" class="form-control" id="priceU" required></td>
            <td><select class="form-control" id="typeU" required>
              <?php $cat=DB::select("select * from categories");?>
              <?php $__currentLoopData = $cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option class="option" value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <option class="optionother" value="Other">Other</option>
             </select>
            </td>
          </tr>
          <tr>
           <td><input type="number"  value="" placeholder="Height" id="heightU" class="form-control"></td>
           <td><input type="number"  value="" placeholder="Width" id="widthU" class="form-control"></td>
           <td><input type="number"  value="" placeholder="In-Stock" id="stockU" class="form-control"></td>
         </tr>
         <tr>
           <td>
              <label for='photoU' class='form-label'>
                <img  class='photo' name='photoView' title='Choose photo' id='photoView' style="width:120px;height:130px;border:0;border-radius:4px"/>
              </label><input type='file' name='photoU' style='visibility:hidden' id='photoU' onchange='loadFile(event)' class='form-control'>
           </td>
         </tr>
         <tr>
           <th style="color:#8e221a">Promotion %</th>
           <td><input type="number" value="" class="form-control" id="promotion"></td>
         </tr>
         <tr>
           <th style="color:#8e221a">Description</th>
         </tr>
         <tr>
           <td colspan="2"><input type="text"  value="" id="descriptionU" placeholder="Describe the product with few words." class="form-control"></td>
           <td><button type="button" id="updateprod" class="form-control" style="cursor:pointer;width:80px" >Update</button></td>
        <tr>
       </table>
     </div></center>
   </form>
 </div>
 <!-- //////////////////////ENDUPDATE///////////////////////////////// -->

 <div id="addVideoModal" class="modal" style="margin-top:55px">
   <span id="span" class="btn-close">&times;</span>
   <center><form class="" action="<?php echo e(route('addvideo')); ?>" method="post" enctype="multipart/form-data">
     <?php echo csrf_field(); ?>
     <div class="" style="padding:40px;background-color:rgba(245,240,250,0.9);border-radius:6px" id="addvideo">
       <table align="center" class="table" style="width:70%">
         <tr>
           <td colspan="3" style="font-weight:900;font-size:26px;color:#8e221a;">Publish a New Video</td>
         </tr>
         <tr>
           <td>
             <label for='videonew' class='form-label'>
               <img  class='photo' name='videoNew' title='Choose Video' id='videoNew' style="width:120px;height:130px;border:0;border-radius:4px"/>
             </label>
             <input type='file' name='video' accept="video/mp4, video/quicktime, image/gif" style='visibility:hidden' id='videonew' onchange='loadFileVideo(event)' class='form-control'>
           </td>
         </tr>
         <tr>
           <td><input type="text" name="caption" value="" placeholder="Caption" id="caption" class="form-control"></td>
         </tr>
         <tr>
           <td>
             <button type="submit" id="updateprod" class="form-control" style="cursor:pointer;width:80px" >Publish</button>
           </td>
        <tr>
       </table>
     </div>
   </form></center>
 </div>


 <div class="" id="my_products-section" style="padding-top:50px;padding-bottom:100px">
   <center><?php if($userprod): ?>
      <table id="productstable"><tr>
        <?php $__currentLoopData = $userprod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $commentsNb=DB::table('comment')->where('productId','=',$p->id)->count();
          ?>
          <td id='box_<?php echo e($p->id); ?>'><div class='box' id='box' style='display:relative'>
            <div class='front'>
              <?php if($p->promotion>0): ?>
                <div class="ribbon ribbon-top-right"><span>%<?php echo e($p->promotion); ?> Promotion</span></div>
              <?php endif; ?>
              <?php if($p->photo != null && file_exists(public_path()."/img/$p->photo")): ?>
                <img src="<?php echo e(asset('img/'.$p->photo)); ?>" style='width:300px;height:240px;border-radius:20px' alt=''>
              <?php else: ?>
                <h2 style="font-size:20px">No Preview Photo</h2>
              <?php endif; ?>
            </div>
            <button type="button" onclick="deleteProduct(<?php echo e($p->id); ?>)" class="btn" id="deleteProduct_<?php echo e($p->id); ?>" style='color:red;font-weight:800;position:absolute;margin-top:-35px;padding:3px 5px;font-size:24px' name='remove'>&times</button>
            <div class='back'>
              <h1><?php echo e($p->name); ?></h1>
              <h5 style='color:#618685;'><?php echo e($p->description); ?></h5>
              <?php if($p->promotion>0): ?>
              <?php
                $pr=$p->price;
                $pro=$p->promotion;
                $newprice=$pr-(($pro/100)*$pr);
              ?>
                <p style='color:#618685;'><span style="text-decoration: line-through;">$<?php echo e($p->price); ?></span> <span style="width:10px">  </span> <span style="font-weight:600">$<?php echo e($newprice); ?></span>
              <?php else: ?>
                <p style='color:#618685;'>$ <?php echo e($p->price); ?>

              <?php endif; ?>
              <button type="button" onclick="returnProduct(<?php echo e($p->id); ?>)" style='margin-left:5px;padding:10px;border:0;background-color:transparent'><a class="nav-link">Edit</a></button></p>
              <?php if($commentsNb>0): ?>
                <a href='/Comments/<?php echo e($p->id); ?>' class="nav-link" style=''>View comments</a>
              <?php endif; ?>
              </div>
            </div></td>
            <?php if($loop->iteration % 4 == 0): ?>
              </tr><tr>
            <?php endif; ?></center>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
      <center><div style='border-radius:4px;height:300px;width:90%'><h2 align='center'>No products.</h2>
              <p class='art' style='color:black;font-size:24px'>Start releasing your products here.</p></div></center>
    <?php endif; ?>
    </table>
  </div>

<div class="container mt-4">
     <div class="row">
         <div class="col-lg-9 my-lg-0 my-1">
             <div id="main-content" class="bg-white border">
                 <div class="d-flex flex-column">
                     <div class="h5">Hello <?php echo e($user->name); ?>,</div>
                     <div>Logged in as: <?php echo e($user->email); ?></div>
                 </div>
                 <div class="d-flex my-4 flex-wrap">
                     <div class="box me-4 my-1 bg-light">
                         <img src="<?php echo e(asset('css/icons/box.png')); ?>" style="width:55px;height:45px">
                         <div class="d-flex align-items-center mt-2">
                             <div class="tag">New Orders</div>
                             <div class="ms-auto number"><?php echo e($ordersNb); ?></div>
                         </div>
                     </div>
                     <div style="width:300px" class="box me-4 my-1 bg-light">
                         <div class="d-flex align-items mt-2">
                           <strong>Order Status:</strong>
                             <select class="form-control" id="orderType">
                               <option value="pending">Pending</option>
                               <option value="confirmed">Confirmed</option>
                               <option value="Packed">Packed</option>
                               <option value="Delivered">Delivered</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="text-uppercase">My recent orders</div>
                 <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <div class="order my-3 bg-light <?php echo e($o->status); ?>" id="order_<?php echo e($o->id); ?>">
                         <div class="row">
                             <div class="col-lg-4">
                                 <div class="d-flex flex-column justify-content-between order-summary">
                                     <div class="d-flex align-items-center">
                                       <div class="text-uppercase">Product <?php echo e($o->name); ?></div>
                                         <div class="green-label ms-auto text-uppercase"><?php echo e($o->cname); ?></div>
                                     </div>
                                     <div class="fs-8"><?php echo e($o->created_at); ?></div>
                                     <button class="btn btn-primary text-uppercase" value="<?php echo e($o->id); ?>">order info</button>
                                 </div>
                             </div>
                             <div class="col-lg-8">
                                 <div class="d-sm-flex align-items-sm-start justify-content-sm-between">
                                     <div class="status">Status : <?php echo e($o->status); ?></div>
                                     <?php if($o->status=='pending'||$o->status=='confirmed'): ?>
                                       <?php
                                         $date1 = now()->Format('Y-m-d');
                                         $date2 = $o->expecting_delivery_date;
                                         $d1=new DateTime($date1);
                                         $d2=new DateTime($date2);

                                         $days = $d2->diff($d1)->format("%a");
                                       ?>
                                       <div class="red-label ms-auto text-uppercase"><?php if($d1>$d2): ?> Too Late <?php else: ?> <?php if($o->status=='confirmed'): ?>You have <?php else: ?> In <?php endif; ?> <?php echo e($days); ?> Days <?php endif; ?></div>
                                      <?php endif; ?>
                                 </div>
                                 <div class="progressbar-track">
                                   <?php if($o->status=='confirmed'): ?>
                                     <ul class="progressbar">
                                         <li id="step-1" class="text-muted green">
                                             <span class="fas fa-gift"></span>
                                         </li>
                                         <li id="step-2" class="text-muted green">
                                             <span class="fas fa-check"></span>
                                         </li>
                                         <li id="step-3" class="text-muted ">
                                           <span class="fas fa-box"></span>
                                         </li>
                                         <li id="step-5" class="text-muted">
                                             <span class="fas fa-box-open"></span>
                                         </li>
                                     </ul>
                                     <div id="tracker"></div>
                                   <?php elseif($o->status=='Packed'): ?>
                                     <ul class="progressbar">
                                         <li id="step-1" class="text-muted green">
                                             <span class="fas fa-gift"></span>
                                         </li>
                                         <li id="step-2" class="text-muted green">
                                             <span class="fas fa-check"></span>
                                         </li>
                                         <li id="step-3" class="text-muted green">
                                           <span class="fas fa-box"></span>
                                         </li>
                                         <li id="step-5" class="text-muted">
                                             <span class="fas fa-box-open"></span>
                                         </li>
                                     </ul>
                                     <div id="tracker"></div>
                                    <?php elseif($o->status=='Delivered'): ?>
                                      <ul class="progressbar">
                                          <li id="step-1" class="text-muted green">
                                              <span class="fas fa-gift"></span>
                                          </li>
                                          <li id="step-2" class="text-muted green">
                                              <span class="fas fa-check"></span>
                                          </li>
                                          <li id="step-3" class="text-muted green">
                                            <span class="fas fa-box"></span>
                                          </li>
                                          <li id="step-5" class="text-muted green">
                                              <span class="fas fa-box-open"></span>
                                          </li>
                                      </ul>
                                      <div id="tracker"></div>
                                     <?php else: ?>
                                       <ul class="progressbar">
                                           <li id="step-1" class="text-muted green">
                                               <span class="fas fa-gift"></span>
                                           </li>
                                           <li id="step-2" class="text-muted">
                                               <span class="fas fa-check"></span>
                                           </li>
                                           <li id="step-3" class="text-muted ">
                                             <span class="fas fa-box"></span>
                                           </li>
                                           <li id="step-5" class="text-muted">
                                               <span class="fas fa-box-open"></span>
                                           </li>
                                       </ul>
                                       <div id="tracker"></div>
                                    <?php endif; ?>
                                 </div>
                             </div>
                             <div class="alert alert-success" id="alertConfirm_<?php echo e($o->id); ?>" style="display:none;margin-top:15px"></div>
                           </div>
                         </div>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         <!-- Modal -->
                         <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <div class="modal" id="modalUser<?php echo e($o->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                                 <div class="modal-dialog" style="">
                                   <div class="modal-content" style="width:500px">
                                     <div class="modal-header">
                                       <h5 class="modal-title" id="exampleModalLabel">Order Info</h5>
                                       <button type="button" class="btn-close" data-mdb-dismiss="modal" value="<?php echo e($o->id); ?>" aria-label="Close"></button>
                                     </div>
                                     <div class="modal-body" style="width:600px">
                                       <div class="container py-5" style="width:500px">
                                         <div class="row justify-content-center" style="width:400px">
                                           <div class="col-md-8 col-lg-6 col-xl-4" style="width:450px">
                                             <div class="card" style="border-radius: 15px;width:auto">
                                               <div class="bg-image hover-overlay ripple ripple-surface ripple-surface-light"
                                               data-mdb-ripple-color="light">
                                               <img src="<?php echo e(asset('img/'.$o->photo)); ?>"
                                               style="border-top-left-radius: 15px; border-top-right-radius: 15px;" class="img-fluid"
                                               alt="" />
                                               <a>
                                                 <div class="mask"></div>
                                               </a>
                                             </div>
                                             <div class="card-body pb-0">
                                               <div class="d-flex justify-content-between">
                                                 <div>
                                                   <p><a href="product/<?php echo e($o->productId); ?>" class="text-dark"><?php echo e($o->pname); ?></a></p>
                                                   <p class="small text-muted">$<?php echo e($o->price); ?></p>
                                                   <?php if($o->promotion>0): ?>
                                                    <p class="small text-muted">%<?php echo e($o->promotion); ?> Promotion</p>
                                                   <?php endif; ?>
                                                 </div>
                                                 <div>
                                                   <p><a class="text-dark">Qunatity: 0<?php echo e($o->quantity); ?></a></p>
                                                 </div>
                                               </div>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body pb-0">
                                               <div class="d-flex justify-content-between">
                                                 <p><a class="text-dark">Total Price:</a></p>
                                                 <p class="text-dark">$<?php echo e($o->total_price); ?></p>
                                               </div>
                                               <?php if($o->status!='Packed' && $o->status!='Delivered'): ?>
                                               <div class="d-flex justify-content-between">
                                                 <p><a class="text-dark">Delivery Date:</a></p>
                                                 <p class="text-dark">
                                                   <input type="date" class="form-control" id="delTime" name="" value="<?php echo e($o->expecting_delivery_date); ?>"></p>
                                               </div><?php endif; ?>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body pb-0">
                                               <div class="d-flex justify-content-between">
                                                 <p><a class="text-dark">Stock:</a></p>
                                                 <p class="text-dark"><?php echo e($o->stock); ?> Items</p>
                                               </div>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body">
                                               <div class="d-flex justify-content-between align-items-center pb-2 mb-1">
                                                <?php if($o->status=='pending'): ?>
                                                  <button type="button" onclick="confirmOrder(<?php echo e($o->id); ?>)" class="btn btn-primary">Confirm</button>
                                                <?php elseif($o->status=='confirmed'): ?>
                                                  <button type="button" onclick="ready(<?php echo e($o->id); ?>)" class="btn btn-danger">Ready to Deliver</button>
                                                <?php endif; ?>
                                               </div>
                                             </div>
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </section>
                                 </div>
                               </div>
                             </div>
                           </div>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           </div>
         </div>
     </div>

   </div>
   <center><div class="chart" style="padding:50px;align-self:center">
      <div class="main-container">
        <div style="width:300px;height:auto;position:absolute" class="box me-4 my-1 bg-light">
          <strong>Select Dates:</strong>
            <div class="align-items mt-2">
                <input type="date" id="dateFrom" value="" class="form-control">
                <input type="date" id="dateTo" value="" class="form-control">
            </div>
            <button type="button" id="salesDates" onclick="returnSales()" class="btn btn-light">Filter</button>
        </div>
       <div class="year-stats" id="salesData">
          <?php $__currentLoopData = $userprod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $up): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php if($up->soldno > '0'): ?>
                <div class='month-group'>
                  <p class='month'><?php echo e($up->name); ?></p>
                 <div class='bar' style='height:<?php echo e($up->soldno); ?>0px' title='<?php echo e($up->soldno); ?>'></div><?php echo e($up->soldno); ?>

               </div>
               <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

               <?php $sp=DB::table('product')->where('userId','=',$id)->where('soldno','>','0')->count();?>
               <?php if($sp == 0): ?>
              <div class='info'>
                     <p><span>You don't have any sold products yet.</span></p>
              </div>
              <?php endif; ?>

            <?php
              $prodNum=DB::table('product')->where('userId','=',$id)->count();
              $sales=DB::select("select sum(price*soldno) as sales from product where userId=$id");?>
        </div>
        <div class='info' id="stockData">
               <p><span>You have <?php echo e($prodNum); ?> products shared here.</span></p>
               <p>Total sales :<span>   $ <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($s->sales); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span></p>
          </div>
          <div class="info">
               <center><strong>In-Stock</strong></center>
               <table style="">
                 <tr>
                   <td></td>
                   <?php if($userprod): ?>
                     <?php $__currentLoopData = $userprod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $us): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <td style="color:white;text-align:center;padding:10px;border-bottom:1px solid #eee;"><?php echo e($us->name); ?><br><?php echo e($us->stock); ?></td>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php else: ?>
                    <center>You have nothing in stock right now!</center>
                  <?php endif; ?>
                 </tr>
               </table>
             </div>
       </div>
     </div>

 <div class="">
   <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 </div>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/user2.blade.php ENDPATH**/ ?>