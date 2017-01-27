
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
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
</head>
<body>
<section>
  <header>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <ul class="list-inline lefthead">
           <li><a  href="{{URL::to('/')}}">
            <img src="{{URL::asset('web/images/logo.png')}}"></a></li>
          </ul>
          <ul class="list-inline text-center searchicon" style="display:none;">
            <a href="">dd</a>
          </ul>
          <ul class="list-inline pull-right righthead">
            <li><a href="#" style="font-size:16px;">Login / Sign up</a></li>
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
         {{Session::get('massage')}}
         
        {!! Form::open(['url' => '/password/reset', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form','enctype'=>'multipart/form-data']) !!}
          
            {!! csrf_field() !!}
           
            <input type="hidden" name="token" value="{{ $token }}">
          <div class="form-group">
            <input type="email" class="form-control"  id='user_email' readonly  name="email" value="{{ @$data->email }}" placeholder="Enter Email Id" >
          </div>
            <!-- @if ($errors->has('email')) 
             <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
             @endif
 -->
             <div class="form-group">
            <input type="password" class="form-control"   name="password" placeholder="new password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'New password'" >
          </div>

          <div class="form-group">
            <input type="password" class="form-control"   name="password_confirmation" placeholder="Confirm new password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirm new password'"  >
          </div>          
          
          <div>
          <button type="submit" class="btn btn-login btn-block btn-lg">Set Password</button>
          </div>
          <div class="checkbox">
            
            <a href="{{URL::to('/auth/login')}}" class="pull-right">Login / Sign up</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

@include('includes.web.footer')
        </body>
</html>