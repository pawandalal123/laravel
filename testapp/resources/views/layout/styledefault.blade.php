<!DOCTYPE html>
<html lang="en">
<head>
  {!! SEO::generate() !!}
  <meta charset="utf-8">
  <link rel="shortcut icon" href="{{URL::asset('web/images/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=3.0,width=device-width,user-scalable=yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/mobile.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/paper-bootstrap.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/roboto.min.css')}}">
</head>
 <body data-spy="scroll" data-target="#navbar-example" style="padding-bottom:0px!important;">
   @yield('content')
</body>
</html>
