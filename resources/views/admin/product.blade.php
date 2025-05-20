@include('admin.bars.tops')
@php
  $products=DB::select("select product.id,product.name,product.userId,product.price,product.description,product.our_stock,
  product.soldno,product.created_at,users.name as username,users.phone as userphone,users.address as address,categories.name as type from product
  left join users on product.userId=users.id
  left join categories on product.categoryId=categories.id order by product.id asc");


  $productsNb=DB::table('product')->count();
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

     $("#editProduct").click(function(e){
       e.preventDefault();
       $.ajaxSetup({
           headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
       });

       var id= $("#editProduct").val();
       var name=$("#name").val();
       var type=$("#category").val();
       var price=$("#price").val();
       var description= $("#description").val();
       var suppStock= $("#suppStock").val();
       var ourStock= $("#ourStock").val();
       var validation= $("#validation").val();
       var promotion= $("#promotion").val();
       var soldno=$("#soldno").val();

         $.ajax({
             url:"{{route('editProductAdmin')}}",
             method:'POST',
             data: {id:id,name:name,type:type,price:price,validation:validation,description:description,soldno:soldno,ourStock:ourStock,suppStock:suppStock,promotion:promotion},
             success: function(output){
                 // alert(output.success);
                 $(".modalUser").fadeOut();
                 $(".alerts").text('');
                 $(".alerts").append(output.success);
                 $(".alerts").fadeIn();
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
        url:"{{route('returnProductAdmin')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            // $("#user_"+id).fadeOut();
            $(".modalUser").fadeIn();
            $("#name").val(output.name);
            $("#category").val(output.type);
            $("#price").val(output.price);
            $("#description").val(output.description);
            $("#suppStock").val(output.suppStock);
            $("#ourStock").val(output.ourStock);
            $("#validation").val(output.validation);
            $("#soldno").val(output.soldno);
            $("#promotion").val(output.promotion);
            $("#editProduct").val(output.id);
          }
});
}

function deleteProduct(id){
  $.ajaxSetup({
      headers:{'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
  });
    $.ajax({
        url:"{{route('deleteProductAdmin')}}",
        method:'POST',
        data: {id:id},
        success: function(output){
            $(".alerts").text('');
            $("#product_"+id).fadeOut();
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
            <h1 class="h3 mb-0 text-gray-800">Products</h1>
          </div>
          <hr>
          <div class="alerts" style="display: none;color:white;background-color:rgba(10,140,40,0.3);padding:6px;border-radius:5px"></div>
          <hr>
          <h6>Total: {{$productsNb}}</h6>

          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Supplier Name</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Category</th>
                <th scope="col">Sell Number</th>
                <th scope="col">Our Stock</th>
                <th scope="col">Created</th>
                <th scope="col">Rating</th>
                <th scope="col">Comments</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $p)
                @php
                  $commNb=DB::table('comment')->where('productId','=',$p->id)->count();
                @endphp
                <tr id="product_{{$p->id}}">
                  <th scope="row">{{$p->id}}</th>
                  <td><a href="/product/{{$p->id}}">{{$p->name}}</a></td>
                  <td>
                      <a href="/userprofile/{{$p->userId}}">{{$p->username}}</a>
                    </td>
                  <td style="width:20%">{{$p->description}}</td>
                  <td>{{$p->price}}</td>
                  <td>{{$p->type}}</td>
                  <td>{{$p->soldno}}</td>
                  <td>{{$p->our_stock}}</td>
                  <td>{{$p->created_at}}</td>
                  <td>@php
                        $ratings=DB::table('productsrate')->where('productId','=',$p->id)->sum('rate');
                        $numberofrates=DB::table('productsrate')->where('productId','=',$p->id)->count();
                      @endphp
                      @if($numberofrates==0)
                        -
                      @else
                        {{$ratings / $numberofrates}}
                      @endif
                  </td>
                  <td>{{$commNb}}</td>
                  <td>
                    <!-- <button type="button" class="btn btn-primary">Primary</button> -->
                    <button type="button" class="btn btn-info"  onclick="returnProduct({{$p->id}})" data-toggle="modalUser" id="togglemodal" >Edit</button>
                    <button type="button" class="btn btn-light" onclick="deleteProduct({{$p->id}})">Remove</button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>


          <form class="dsfsd" action="" method="post">
          <!-- Modal -->
          <div class="modalUser" id="exampleModal" style="height:auto" role="" aria-labelledby="" aria-hidden="">
            <div class="modal-dialog" role="document" style="height:auto">
              <div class="modal-content" style="width:140rem;height:auto">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                </div>
                <div class="modal-body">
                  {{csrf_field()}}
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th scope="col">Name and Category</th>
                        <td>
                          <div class="input-group">
                            <input type="text" class="form-control" id="name">
                            <select id="category" name="category" class="custom-select" style="height:43px">
                              @php
                                $cats=DB::select("select * from categories")
                              @endphp
                              @foreach($cats as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th scope="col">Price and Number of Sells</th>
                        <td>
                          <div class="input-group">
                            <input type="number" class="form-control" id="price">
                            <input type="number" class="form-control" id="soldno">
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th scope="col">Stock and Promotion</th>
                        <td>
                          <div class="input-group">
                            Our Stock<input type="number" class="form-control" id="ourStock">
                            Supplier's Stock<input type="number" class="form-control" id="suppStock">
                            Promotion<input type="number" class="form-control" id="promotion">
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th scope="col">Description</th>
                        <td colspan="2">
                          <div class="input-group">
                            <input type="text" class="form-control" id="description">
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th scope="col">Validation</th>
                        <td colspan="2">
                          <div class="input-group">
                            <select class="form-control" id="validation">
                              <option value="yes">Yes</option>
                              <option value="no">No</option>
                            </select>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th scope="col">Supplier's Details</th>
                        <td colspan="">
                          <div class="input-group">
                            <span style="padding-right:10px"><strong>Name:</strong> {{$p->username}}</span>
                            <span style="padding-right:10px"><strong>Phone:</strong> {{$p->userphone}}</span>
                            <span style="padding-right:10px"><strong>Address:</strong> {{$p->address}}</span>
                          </div>
                        </td>
                      </tr>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
                  <button type="button" class="btn btn-primary" id="editProduct" onclick="" value="">Save Changes</button>
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
