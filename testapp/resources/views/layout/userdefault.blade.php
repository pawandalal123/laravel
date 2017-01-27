<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard All Events| Page</title>
  <link rel="shortcut icon" href="http://goeventz.com/web/images/favicon.png">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/mobile.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/sweetalert2.css')}}" >
<link rel="stylesheet" type="text/css" href="{{URL::to('testapp/public/')}}{{ elixir('css/all.css') }}" >
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>
   <script type="application/javascript"   src="{{URL::to('testapp/public/js/top.js')}}"></script>
    
   <script type="application/javascript"   src="{{ URL::asset('web/js/jquery.loader.js')}}"></script>
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDPCGwAgXo_bx9IMFmaMHyCjUV_FkO068Q&sensor&sensor=false&amp;libraries=places"></script>
 <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/photogrid.css')}}">
 <link href="{{ URL::asset('web/js/dist/css/lightgallery.css')}}" rel="stylesheet">  
 <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/js/lib/fileinput.css')}}">
    <script type="text/javascript">
  var SITE_URL = {!! json_encode(url('/')) !!}+'/';
   var APIURL = '<?php echo URL::to('api/'); ?>';

  </script>
  <style>

.modal{ 
  position: fixed; 
  top: 0; 
  right: 0; 
  bottom: 0; 

  left: 0; 
  z-index: 1040; 
  overflow-y: auto; background: rgba(0, 0, 0, 0.7); -webkit-transition: opacity .15s linear; transition: opacity .15s linear; } 



/* Model */

 </style>

</head>
<body style="background-color: #E0E0E0!important;" data-target="#navbar-example" data-spy="scroll">
<!--   <div id="page_hide">
<img src="{{URL::to('web/images/AjaxLoader.gif')}}"/>
    </div> -->
    <div id="page_hide" style="display:none;"><div class="loader"></div></div>
 @include('includes.web.headernormal',array('addclass'=>'headerbg'))
 <div class="admindashboard" id="mainbox">
   @include('includes.userdashboard.left-nav')
  <div class="admin-right" id="right-section">
    <div class="adminright-inner">
     @yield('content')
  </div>
  </div>
 </div>
 <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <script src="{{ URL::asset('web/js/justifiedGallery.min.js')}}"></script>
  <script type="application/javascript"   src="{{ URL::asset('web/js/lib/fileinput.js')}}"></script>

        <script src="{{ URL::asset('web/js/dist/js/lightgallery.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-fullscreen.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-thumbnail.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-autoplay.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-zoom.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-hash.js')}}"></script>
        <script src="{{ URL::asset('web/js/dist/js/lg-pager.js')}}"></script>
        <script src="{{ URL::asset('web/js/lib/jquery.mousewheel.min.js')}}"></script>
  <script type="application/javascript"   src="{{URL::to('testapp/public/js/bot.js')}}"></script>

   <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDPCGwAgXo_bx9IMFmaMHyCjUV_FkO068Q&sensor&sensor=false&amp;libraries=places"></script>

<script>
$(document).ready(function(){
  $('.mobileicon').click(function(){
    $('.adminleft-section').toggleClass('commonnavi');
    $('.admin-left').toggleClass('commonnavi');
    $('.mobileicon').toggleClass('commonnavi');
  });
});

$(".alertcontent strong").click(function(){
    $(".commonalert").fadeOut();
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  jQuery(window).load(function () {
    $("div").removeClass("container");
  });
});
  $(document).ready(function(){

    $(".mobileicon").click(function()
    {
      if($(this).hasClass('aftermobile'))
      {
        $("#iconId").removeClass("aftermobile");
        $("#mainbox").removeClass("afteradmindashboard");
        $("#left-section").removeClass("adminleft-section");
        $("#mainbox").addClass("admindashboard");
        $("#left-section").addClass("admin-left");
        $("#iconId").addClass("mobileicon");
      }
      else
      {
        $("#iconId").addClass("aftermobile");
        $("#mainbox").addClass("afteradmindashboard");
        $("#left-section").addClass("adminleft-section");
        $("#mainbox").removeClass("admindashboard");
        $("#left-section").removeClass("admin-left");
        $("#iconId").removeClass("mobileicon");
      }
});
});
</script>

</body>
</html>
