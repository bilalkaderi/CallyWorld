@include('admin.bars.tops')
@php
  $rates=DB::select("select rate.id,rate.rate,rate.created_at,rate.userId,users.name,client.name as clientname from rate
            inner join users on rate.userId=users.id inner join client on rate.clientId=client.id
            order by rate.created_at desc");
  $ratesNb=DB::table('rate')->count();

  $prodrates=DB::select("select productsrate.id,productsrate.rate,productsrate.productId,productsrate.created_at,product.name,client.name as clientname from productsrate
            inner join product on productsrate.productId=product.id inner join client on productsrate.clientId=client.id
            order by productsrate.created_at desc");
  $prodratesNb=DB::table('productsrate')->count();
@endphp


<script type="text/javascript">
    function deleteRate(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"{{route('deleteRate')}}",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#rate_"+id).fadeOut();
                $("#alertUser").text('');
                $("#alertUser").append(output.success);
                $("#alertUser").fadeIn();
              }
    });
    }

    function deleteProdRate(id){
      $.ajaxSetup({
          headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
      });
        $.ajax({
            url:"{{route('deleteProdRate')}}",
            method:'POST',
            data: {id:id},
            success: function(output){
                $("#prodrate_"+id).fadeOut();
                $("#alertProd").text('');
                $("#alertProd").append(output.success);
                $("#alertProd").fadeIn();
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
            <h1 class="h3 mb-0 text-gray-800">Suppliers' Ratings</h1>
          </div>
          <hr>
          <div class="alerts" id="alertUser"></div>
          <hr>
          <h6>Total: {{$ratesNb}} </h6>
          <table class="table table-sm" id="usersTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Supplier Name</th>
                <th scope="col">Client Name</th>
                <th scope="col">Given Rate</th>
                <th scope="col">ON</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rates as $r)
                <tr id="rate_{{$r->id}}">
                  <th scope="row">{{$r->id}}</th>
                  <td><a href="/userprofile/{{$r->userId}}">{{$r->name}}</a></td>
                  <td>{{$r->clientname}}</td>
                  <td>{{$r->rate}}</td>
                  <td>{{$r->created_at}}</td>
                  <td>
                    <button type="button" class="btn btn-light" onclick="deleteRate({{$r->id}})">Remove</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Products' Ratings</h1>
          </div>
          <hr>
          <div class="alerts" id="alertProd"></div>
          <hr>
          <h6>Total: {{$prodratesNb}} </h6>
          <table class="table table-sm" id="prodTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Client Name</th>
                <th scope="col">Given Rate</th>
                <th scope="col">ON</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($prodrates as $pr)
                <tr id="prodrate_{{$pr->id}}">
                  <th scope="row">{{$pr->id}}</th>
                  <td><a href="/product/{{$pr->productId}}">{{$pr->name}}</a></td>
                  <td>{{$pr->clientname}}</td>
                  <td>{{$pr->rate}}</td>
                  <td>{{$pr->created_at}}</td>
                  <td>
                    <button type="button" class="btn btn-light" onclick="deleteProdRate({{$pr->id}})">Remove</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    <!-- Pages -->

  </div>
  <!-- Footer --> @include('admin.bars.footer')
  </div>
  </div>

</body>
