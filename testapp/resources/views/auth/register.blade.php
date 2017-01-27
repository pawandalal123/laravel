<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
<link rel="shortcut icon" href="{{URL::asset('web/images/favicon.png')}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<link  href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link  href="{{ URL::asset('css/font-awesome.css')}}" rel="stylesheet" type="text/css">
<link  href="{{ URL::asset('css/colorbox.css')}}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
</head>
    </head>
    <body>
        <div class="container">
            <div class="page-header">
              <h1>Go Eventz</h1>
            </div>
            {{Session::get('massage')}}
   
           <form action='http://dev.goeventz.com/auth/register' method='post'>
            {!! csrf_field() !!}
                
                <div class="form-group">
                <label for='first_name' >Email Id</label>
                <input type="text" class="form-control" name='email' id='user_email' placeholder='Enter Email'>
              </div>
             @if ($errors->has('email')) 
             <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
             @endif
              <div class="form-group">
                <label for='password' >Password</label>
                <input type="password" class="form-control" name='password' id='password' placeholder='Enter password'>
              </div>
              @if ($errors->has('password')) 
              
              <div class="alert alert-danger">{{ $errors->first('password') }}</div> 
              @endif
             
              <button name="submit" class="btn btn-default">Login</button>
              
                </form>
        </div>
        @include('includes.footer')
        </body>
</html>