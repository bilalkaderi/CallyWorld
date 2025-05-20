@include('admin.bars.tops')
<style media="screen">
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap');
*{
  font-family: 'Poppins', sans-serif;
}
</style>
<div class="d-flex my-4 flex-wrap">
    <div class="box me-4 my-1 bg-light">
      <div class="text-uppercase">{{$body}}</div>
        <div class="d-flex align-items-center mt-2">
            <div class="tag"><center>Subject: <strong>{{$subject}}</strong></center></div>
            <div class="ms-auto number">
              {{$text}}
          </div>
        </div>
    </div>
</div>
