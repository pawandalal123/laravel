<!DOCTYPE html>
<html>
<head>
  <title>Admin | Page</title>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="{{URL::asset('web/images/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=3.0,width=device-width,user-scalable=yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/admin/css/stylesheets.css')}}">
  <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery-1.10.2.min.js')}}"></script>
  <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/jquery/jquery-migrate-1.2.1.min.js')}}"></script>
  <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/bootstrap.min.js')}}"></script>
  <script type='text/javascript' src="{{ URL::asset('web/admin/js/plugins/validation/languages/jquery.validationEngine-en.js')}}" charset='utf-8'></script>
  <script type='text/javascript' src="{{ URL::asset('public/admin/js/plugins/validation/jquery.validationEngine.js')}}" charset='utf-8'></script>

</head>
 <script type="text/javascript">
 var APIURL = '<?php echo URL::to('api/') ?>';
 var SITE_URL = {!! json_encode(url('/')) !!}+'/';
 </script>
<body data-spy="scroll orgbg" data-target="#navbar-example" style="background-color:#fff !important">
                @yield('content')
          
    </body>
</html>