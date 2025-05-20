<!DOCTYPE html>
<html lang="en">

    <head>

      <style media="screen">
        /***** Modal *****/

          .modal-backdrop.in {
            filter: alpha(opacity=7);
            opacity: 0.7;
          }

          .modal-content {
            background: none;
            border: 0;
            -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0;
            -moz-box-shadow: none; -webkit-box-shadow: none; box-shadow: none;
          }

          .modal-body {
            padding: 0 25px 25px 25px;
          }

          .modal-header {
            padding: 25px 25px 15px 25px;
            text-align: right;
          }

          .modal-header, .modal-footer {
            border: 0;
          }

          .modal-header .close {
            float: none;
            margin: 0;
            font-size: 36px;
            color: #fff;
            font-weight: 300;
            text-shadow: none;
            opacity: 1;
          }
          .video-link {
              padding-top: 70px;
          }

          .video-link a:hover,
          .video-link a:focus {
              outline: 0;
          }

          a .video-link-text {
              color: #fff;
              opacity: 0.8;
              -o-transition: all .3s; -moz-transition: all .3s; -webkit-transition: all .3s; -ms-transition: all .3s; transition: all .3s;
          }

          a:hover .video-link-text,
          a:focus .video-link-text {
              outline: 0;
              color: #fff;
              opacity: 1;
              border-bottom: 1px dotted #fff;
          }

          a .video-link-icon {
              position: relative;
              display: inline-block;
              width: 50px;
              height: 50px;
              margin-right: 10px;
              background: #e89a3e;
              color: #fff;
              line-height: 50px;
              -moz-border-radius: 50%; -webkit-border-radius: 50%; border-radius: 50%;
              -o-transition: all .3s; -moz-transition: all .3s; -webkit-transition: all .3s; -ms-transition: all .3s; transition: all .3s;
          }
          a .video-link-icon:after {
              position: absolute;
              content: "";
              top: -6px;
              left: -6px;
              width: 66px;
              height: 66px;
              background: #444;
              background: rgba(0, 0, 0, 0.1);
              z-index: -99;
              -moz-border-radius: 50%; -webkit-border-radius: 50%; border-radius: 50%;
          }

          a:hover .video-link-icon,
          a:focus .video-link-icon {
              outline: 0;
              background: #fff;
              color: #e89a3e;
          }
      </style>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Watch!</title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <script type="text/javascript">
      $(document).ready(function(){
        $('.launch-modal').on('click', function(e){
            e.preventDefault();
            $( '#' + $(this).data('modal-id') ).modal();
            var src = $(this).id();
            $("#videoPlayer").attr('src',src+'.gif');
          });
      });
    </script>

    <body>

        <!-- Top content -->
        <div class="top-content">
            <div class="container">

                <div class="row">

                </div>
                <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="row">
                      <div class="col-sm-8 col-sm-offset-2 video-link medium-paragraph">
                          <a href="#" class="launch-modal" id="videos/<?php echo e($v->id); ?>" data-modal-id="modal-video">
                              <span class="video-link-icon"><i class="fa fa-play"></i></span>
                              <span class="video-link-text">Play</span>
                          </a>
                      </div>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="modal-video" tabindex="-1" role="dialog" aria-labelledby="modal-video-label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-video">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" id="videoPlayer" src=''
                                    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>

    </body>

</html>
<?php /**PATH C:\xampp\htdocs\CallyWorld\resources\views/videos.blade.php ENDPATH**/ ?>