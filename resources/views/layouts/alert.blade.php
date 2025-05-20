<style media="screen">
.modalUser {
  display: ;
  position: absolute;
  z-index: 2220;
  margin-top: 180px;
  margin-left: 550px;
  left: 0;
  top: 0;
  width: 30%;
  height: auto;
  overflow: auto;
}
.modal-content {
  margin: auto;
  display: block;
  border-radius: 15px;
  width: 50%;
  max-width: 800px;
  font-size: 25px;

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
});
</script>
<center>
<div class="modalUser" id="exampleModal" style="padding: 20px; background-color:white;border-radius:10px" role="" aria-labelledby="" aria-hidden="">
  <div class="modal-dialog" role="document" style="padding: 20px; background-color:#fffb;border-radius:10px">
    <div class="modal-content" style="padding: 20px;background-color:#eee;border-radius:10px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
            {{ session()->get('alert') }}
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closemodal">Close</button>
      </div>
    </div>
  </div>
</div>
</center>
