<?php
  $username=session()->get('deliveryman');
  $dman=DB::table("deliveryman")->where("username","=",$username)->first();

  $orders=DB::select("select cart.id, cart.clientId,cart.items,cart.status,cart.total_amount,cart.expecting_delivery_date,cart.created_at,
  cart.updated_at,cart.payment_method,cart.pref_address,cart.delivery_details,cart.delivery_hour,cart.delivery_response,client.name,client.phone
  from cart inner join client on cart.clientId=client.id where delivery_details= $dman->id order by cart.id desc");

  $count=DB::table("cart")->where("delivery_details","=",$dman->id)->where("status","=","On Delivery")->count();
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
<title>Delivery</title>
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
    .order .progressbar-track .progressbar li.red {
      border: 1px solid red;
      background-color: rgba(60,0,0,0.3);
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
      content: 'On Delivery';
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

    $(document).ready(function(){
      $("#addProduct").click(function(){
        $("#addModal").fadeIn();
      });
      $("#updateAccount").click(function(){
        $("#accountModal").fadeIn();
      });

      $("#orderType").change(function(){
        var type=$(this).val();
        if(type=="all"){
          $('.waiting').fadeIn();$('.Delivered').fadeIn();
        }
        else{
          $('.waiting').fadeOut();$('.Delivered').fadeOut();
          $('.'+type).fadeIn();
        }
      });

      $(".btn-close").click(function(){
        var id=$(this).val();
        $("#modalUser"+id).toggle();
        $("#myModalUpdate").fadeOut();
        $("#addModal").fadeOut();
        $("#accountModal").fadeOut();
      });
      $(".btn-primary").click(function(){
        var id=$(this).val();
        // alert(id);
        $("#modalUser"+id).toggle();
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


     function delivered(id){
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });
       var response='Delivered';
         $.ajax({
             url:"<?php echo e(route('deliveryResponse')); ?>",
             method:'POST',
             data: {id:id,response:response},
             success: function(output){
                $("#modalUser"+id).fadeOut();
                $("#alertConfirm_"+id).text('');
                $("#alertConfirm_"+id).append(output.success);
                $("#alertConfirm_"+id).fadeIn('');
               }
             });
         }

         function notdelivered(id){
           $.ajaxSetup({
               headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
           });
           var response='Not Delivered';
             $.ajax({
                 url:"<?php echo e(route('deliveryResponse')); ?>",
                 method:'POST',
                 data: {id:id,response:response},
                 success: function(output){
                    $("#modalUser"+id).fadeOut();
                    $("#alertConfirm_"+id).text('');
                    $("#alertConfirm_"+id).append(output.success);
                    $("#alertConfirm_"+id).fadeIn('');
                   }
                 });
             }


</script>
<section class="" style="background-color:rgb(245,245,255)">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container">
         <a class="navbar-brand" href="#">
             <div class="d-flex">
                 <div class="d-flex align-items-center logo bg-white">
                   <img src="<?php echo e(asset('css/icons/pen.png')); ?>" style="width:44px;height:46px">
                 </div>
                 <div class="ms-3 d-flex flex-column">
                   <div class="h4">CallyWorld</div>
                     <div class="fs-6">Delivery Profile</div>
                 </div>
             </div>
         </a>
         <div class="collapse navbar-collapse" id="mynav">
             <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                 <li class="nav-item">
                     <a class="nav-link active" aria-current="page" href="<?php echo e(route('home')); ?>">Homepage<span
                             class="fas fa-th-large px-1"></span></a>
                 </li>
             </ul>
         </div>
     </div>
 </nav>


<div class="container mt-4">
     <div class="row">
         <div class="col-lg-9 my-lg-0 my-1">
             <div id="main-content" class="bg-white border">
                 <div class="d-flex flex-column">
                     <div class="h5">Hello <?php echo e($dman->name); ?>,</div>
                     <div>Logged in as: <?php echo e($dman->username); ?></div>
                 </div>
                 <div class="d-flex my-4 flex-wrap">
                     <div class="box me-4 my-1 bg-light">
                         <img src="<?php echo e(asset('css/icons/delivery.png')); ?>" style="width:55px;height:45px">
                         <div class="d-flex align-items-center mt-2">
                             <div class="tag">To be Delivered:</div>
                             <div class="ms-auto number"><?php echo e($count); ?></div>
                         </div>
                     </div>
                     <div style="width:300px" class="box me-4 my-1 bg-light">
                         <div class="d-flex align-items mt-2">
                           <strong>Order Status:</strong>
                             <select class="form-control" id="orderType">
                               <option value="all" >All</option>
                               <option value="waiting" selected>On Delivery</option>
                               <option value="Delivered">Delivered</option>
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="text-uppercase">Orders to be delivered:</div>
                 <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <div class="order my-3 bg-light <?php echo e($o->delivery_response); ?>" style="padding:50px" id="order_<?php echo e($o->id); ?>">
                         <div class="row">
                             <div class="col-lg-4">
                               <div class="green-label ms-auto ">Client Name: <?php echo e($o->name); ?></div>
                                 <div class="justify-content-between order-summary">
                                     <div class="align-items-center">
                                       <div class="text-uppercase">Cart no. #<?php echo e($o->id); ?></div>
                                     </div>

                                     <div class="fs-8"><?php echo e($o->pref_address); ?></div>
                                     <button class="btn btn-primary text-uppercase" value="<?php echo e($o->id); ?>">order info</button>
                                 </div>
                             </div>
                             <div class="col-lg-8">
                               <div class="status">Status : <?php echo e($o->status); ?></div>
                                 <div class="align-items-sm-start justify-content-sm-between">
                                     <?php if($o->status=='On Delivery'): ?>
                                       <?php
                                          $timeValue=$o->delivery_hour;
                                          $currentTime=date('h:i:s');
                                          $timeValueObj = new DateTime($timeValue);
                                          $currentTimeObj = new DateTime('now', new DateTimeZone('Europe/Berlin'));

                                          $timeValueTimestamp = $timeValueObj->getTimestamp();
                                          $currentTimeTimestamp = $currentTimeObj->getTimestamp();

                                          $timeDiffSeconds = abs($currentTimeTimestamp - $timeValueTimestamp);
                                          $timeDiffHours = floor($timeDiffSeconds / 3600);
                                          $timeDiffMinutes = floor(($timeDiffSeconds / 60) % 60);

                                       ?>
                                       <?php if($o->delivery_response=='waiting'): ?>
                                        <?php if($timeDiffHours>0): ?>
                                          <div class="red-label ms-auto text-uppercase"><?php echo e($timeDiffHours-2); ?> H and <?php echo e($timeDiffMinutes); ?> min</div>
                                        <?php else: ?>
                                          <div class="red-label ms-auto text-uppercase">Time Passed</div>
                                        <?php endif; ?>
                                        <div class="ms-auto text-uppercase">Del. Time:  <?php echo e($o->delivery_hour); ?></div>
                                      <?php endif; ?>
                                      <?php endif; ?>
                                 </div>

                                 <div class="progressbar-track">
                                   <?php if($o->delivery_response=='waiting'): ?>
                                     <ul class="progressbar">
                                         <li id="step-4" class="text-muted green">
                                             <span class="fas fa-truck"></span>
                                         </li>
                                         <span style="margin-top:20px"><?php echo e($o->pref_address); ?></span>
                                         <li id="step-5" class="text-muted">
                                             <span class="fas fa-box-open"></span>
                                         </li>
                                     </ul>
                                     <div id="tracker"></div>
                                   <?php elseif($o->delivery_response=='Delivered'): ?>
                                     <ul class="progressbar">
                                       <li id="step-4" class="text-muted green">
                                           <span class="fas fa-truck"></span>
                                       </li>
                                       <span style="margin-top:20px"><?php echo e($o->pref_address); ?></span>
                                         <li id="step-5" class="text-muted green">
                                             <span class="fas fa-box-open"></span>
                                         </li>
                                     </ul>
                                     <div id="tracker"></div>
                                     <?php elseif($o->delivery_response=='Not Delivered'): ?>
                                       <ul class="progressbar">
                                         <li id="step-4" class="text-muted red">
                                             <span class="fas fa-truck"></span>
                                         </li>
                                         <span style="margin-top:20px"><?php echo e($o->pref_address); ?></span>
                                           <li id="step-5" class="text-muted ">
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
                                               <img src=""
                                               style="border-top-left-radius: 15px; border-top-right-radius: 15px;" class="img-fluid"
                                               alt="" />
                                               <a>
                                                 <div class="mask"></div>
                                               </a>
                                             </div>
                                             <div class="card-body pb-0">
                                               <div class="d-flex justify-content-between">
                                                 <div>
                                                   <?php
                                                     $data = json_decode($o->items, true);
                                                   ?>
                                                   <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                     <?php
                                                       $id=$data['id'];
                                                       $prod=DB::select("select * from product where id=$id");
                                                     ?>
                                                     <?php $__currentLoopData = $prod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                      <p><a href="product/<?php echo e($p->id); ?>" class="text-dark"><?php echo e($p->name); ?> : <?php echo e($data['quantity']); ?> items</a></p>
                                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                 </div>
                                                 <div>
                                                 </div>
                                               </div>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body pb-0">
                                               <div class="d-flex justify-content-between">
                                                 <p><a class="text-dark">Total Price:</a></p>
                                                 <p class="text-dark">$<?php echo e($o->total_amount); ?></p>
                                               </div>
                                               <p>Payment Method: <?php echo e($o->payment_method); ?></p>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body pb-0">
                                               <div class="justify-content-between">
                                                 <p><a class="text-dark">Client's Info:</a></p>
                                                 <p class="text-dark">Name: <?php echo e($o->name); ?></p>
                                                 <p class="text-dark">Phone: <a href="tel:<?php echo e($o->phone); ?>"><?php echo e($o->phone); ?></a></p>
                                               </div>
                                             </div>
                                             <hr class="my-0" />
                                             <div class="card-body">
                                               <div class="d-flex justify-content-between align-items-center pb-2 mb-1">
                                                <?php if($o->status=='On Delivery'): ?>
                                                  <button type="button" onclick="delivered(<?php echo e($o->id); ?>)" class="btn btn-success">Delivered</button>
                                                  <button type="button" onclick="notdelivered(<?php echo e($o->id); ?>)" class="btn btn-danger">Not Delivered</button>
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
     <div class="">
       <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     </div>
   </div>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/delivery/profile.blade.php ENDPATH**/ ?>