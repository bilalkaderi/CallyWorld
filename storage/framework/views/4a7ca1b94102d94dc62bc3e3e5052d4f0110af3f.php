<?php echo $__env->make('admin.bars.tops', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body id="page-top">
<div id="wrapper">
  <!-- Sidebar --> <?php echo $__env->make('admin.bars.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <!-- dashboard -->
<!-- ""''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''' -->
    <div class="container-fluid" style="margin-top: 100px;" id="container-user">
      <div class="row mb-3" id="containerFluid">
        <!-- page -->
          <div class="container-fluid" style="margin-top: 100px;" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>

            <div class="row mb-3" id="containerFluid">

            <!-- Clients Card -->

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Clients</div>
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                          <?php
                            $clients=DB::table('client')->count();
                            echo $clients;
                          ?>
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-users fa-2x text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Clients Card -->

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Suppliers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                          $users=DB::table('users')->count();
                          echo $users;
                        ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                          <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                          <span>Since last month</span> -->
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Class Arm Card -->

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                          $products=DB::table('product')->count();
                          echo $products;
                        ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                          <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                          <span>Since last years</span> -->
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class=""><img src="<?php echo e(asset('css/icons/cubes.png')); ?>" style="width:44px;height:46px"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Std Att Card  -->

              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Sold Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <?php
                            $soldproducts=DB::table('product')->where('soldno','>','0')->count();
                            echo $soldproducts;
                          ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                          <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                          <span>Since yesterday</span> -->
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class=""><img src="<?php echo e(asset('css/icons/sold.png')); ?>" style="width:44px;height:46px"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Teachers Card  -->

                          <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                              <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Comments</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                      $comments=DB::table('comment')->count();
                                      echo $comments;
                                    ?></div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                      <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                      <span>Since last years</span> -->
                                    </div>
                                  </div>
                                  <div class="col-auto">
                                    <i class=""><img src="<?php echo e(asset('css/icons/comments.png')); ?>" style="width:44px;height:46px"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>


                           <!-- Session and Terms Card  -->

                          <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                              <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Rates</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                      $rates=DB::table('rate')->count();
                                      echo $rates;
                                    ?></div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                      <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                      <span>Since last years</span> -->
                                    </div>
                                  </div>
                                  <div class="col-auto">
                                    <i class=""><img src="<?php echo e(asset('css/icons/rating.png')); ?>" style="width:44px;height:46px"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>


                          <!-- Terms Card  -->

                          <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                              <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Categories</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                      $categories=DB::table('categories')->count();
                                      echo $categories;
                                    ?></div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                      <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                      <span>Since last years</span> -->
                                    </div>
                                  </div>
                                  <div class="col-auto">
                                    <i class="fas fa-th fa-2x text-info"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>


                          <!-- //////////////////////////////////////////////// -->
                          <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card h-100">
                              <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                  <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Reports</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                      $reports=DB::table('report')->count();
                                      echo $reports;
                                    ?></div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                      <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                      <span>Since last years</span> -->
                                    </div>
                                  </div>
                                  <div class="col-auto">
                                    <i class=""><img src="<?php echo e(asset('css/icons/report.png')); ?>" style="width:44px;height:46px"></i>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
            <!--Row-->


          </div>
<!---Container Fluid-->
</div>
</div>
</div>
</div>
<!-- Footer --> <?php echo $__env->make('admin.bars.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</div>

</body>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>