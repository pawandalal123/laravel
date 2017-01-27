<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link  href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
    </head>
    <body>
        <div class="container">
            <div class="page-header">
              <h1>Go Eventz Admin Login Panel</h1>
            </div>
            {{Session::get('massage')}}
   
           <form action='http://localhost/test/auth/login' method='post'>
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
        <script type="application/javascript"   src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>