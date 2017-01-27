<!DOCTYPE html>
<html>
<head>
  <title>Hire Me | Page</title>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="{{URL::asset('web/images/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=3.0,width=device-width,user-scalable=yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
<link href="{{ URL::asset('web/admin/css/stylesheets.css')}}" rel="stylesheet" type="text/css" />  

    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery-1.10.2.min.js')}}"></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js')}}"></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery-migrate-1.2.1.min.js')}}"></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery.mousewheel.min.js')}}"></script>
        
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/bootstrap.min.js')}}"></script>
            
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/select2/select2.min.js')}}"></script>
    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/uniform/uniform.js')}}"></script>
    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js')}}"></script>
    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/validation/languages/jquery.validationEngine-en.js')}}" charset='utf-8'></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/validation/jquery.validationEngine.js')}}" charset='utf-8'></script>
    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js')}}"></script>

    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/qtip/jquery.qtip-1.0.0-rc3.min.js')}}"></script>
    
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/cleditor/jquery.cleditor.js')}}"></script>
    
   

    <script type='text/javascript' src="{{ URL::asset('web/admin/js/actions.js')}}"></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins.js')}}"></script>
    <script type='text/javascript' src="{{ URL::asset('web/admin/js/settings.js')}}"></script>


	
</head>
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
 <script type="text/javascript">
 var APIURL = '<?php echo URL::to('api/') ?>';
 var SITE_URL = {!! json_encode(url('/')) !!}+'/';
 </script>
<body data-spy="scroll orgbg" data-target="#navbar-example" style="background-color:#fff !important">
<div class="wrapper"> 
@include('includes.web.adminheader')

@include('includes.web.adminleftbar')

 @yield('content')
 </div>

</body>
</html>