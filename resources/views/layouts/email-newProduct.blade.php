@include('admin.bars.tops')
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<div class="container">
  <div class="tag" style="font-weight:800">{{$subject}}</div>
    <div class="box me-4 my-1 bg-light">
      <div class="text-uppercase">{{$body}}</div>
        <div class="d-flex align-items-center mt-2">
            <div class="ms-auto number">
              <div class="">
                <img src="{{asset('img/'.$photo)}}" style="border-radius:5px;width:200px;height:220px" alt="">
              </div>
              <div class="">
                <strong>{{$price}}</strong>
                <strong>{{$newprice}}</strong>
              </div>
              <div class="" style="font-style:italic">
                <center>{{$description}}</center>
              </div>
            </div>
        </div>
    </div>
</div>
