
<!DOCTYPE html>
<html>
<head>
  <title>Change Password</title>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="{{URL::asset('web/images/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/paper-bootstrap.css')}}">
  <link href="{{ URL::asset('web/css/roboto.min.css')}}" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/css/sweetalert2.css')}}" >
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>

  <script type="application/javascript"   src="{{URL::to('testapp/public/js/top.js')}}"></script>
</head>
<script type="text/javascript">
 var APIURL = '<?php echo URL::to('api/') ?>';
 var SITE_URL = {!! json_encode(url('/')) !!}+'/';
 </script>
</head>
<body>
<section>
  <header>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <ul class="list-inline lefthead">
           <li><a  href="{{URL::to('/')}}" track-type="logo" track-element="event-logo">
            <img src="{{URL::asset('web/images/logo.png')}}"></a></li>
          </ul>
         <!--  <ul class="list-inline text-center searchicon" style="display:none;">
            <a href="">dd</a>
          </ul> -->
          <ul class="list-inline pull-right righthead">
            <li><a href="#" style="font-size:16px;" track-type="login" track-element="header-login-forget-page">Login / Sign up</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
</section>

<section>
  <div class="container">
    <div class="row loginpage">
      <div class="col-sm-4 col-sm-offset-4">
        @if (session('status'))
      <div class="alert alert-success">
             {{ session('status') }}
          </div>
        @endif

        @if (count($errors) > 0)
           <div class="alert alert-danger">
           <strong>Whoops!</strong> There were some problems with your input.<br><br>
           <ul>
               @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
               @endforeach
            </ul>
            </div>
          @endif
       
        <h3 class="centerline mt30"><span>Set New Password</span></h3>

         
        {!! Form::open(['url' => '/password/email', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form','enctype'=>'multipart/form-data']) !!}
          
            {!! csrf_field() !!}
          <div class="form-group">
            <input type="email" class="form-control"  id='user_email'  name="email" value="{{ old('email') }}" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'"  >
          </div>
            <!-- @if ($errors->has('email')) 
             <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
             @endif -->
          
          <div>
          <button type="submit" class="btn btn-login btn-block btn-lg trackbutton" track-type="password-reset-link" track-element="Send-password-reset-link">Send password reset link </button>
          </div>
          <div class="checkbox">
            
            <a href="{{URL::to('/auth/login')}}" class="pull-right">Login/Sign up</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@include('includes.web.footer')
<script type="application/javascript"   src="{{URL::to('testapp/public/js/bot.js')}}"></script>


<script type="text/javascript">
  $(document).ready(function(){

  $onload={};
  $onload.type='forgrt-password-page';
  $onload.typeValue=null;
  $onload.action='viewed';
  $onload.page='forget-password';
  $onload.element=null; 
  $onload.referrer=document.referrer;
  $onload.page_url=window.location.href;
  track($onload);


});


</script>
</body>
</html>
