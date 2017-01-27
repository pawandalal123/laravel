<!DOCTYPE html>
<html>
<head>
	<title>IBNC Payment Confirmation | GoEventz</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="stylesheet" type="text/css" href="{{URL::to('/web/css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{URL::to('/web/css/mobile.css')}}">
  <link rel="stylesheet" type="text/css" href="{{URL::to('/web/css/paper-bootstrap.css')}}">
  <link href="{{URL::to('/web/css/roboto.min.css')}}" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700,900' rel='stylesheet' type='text/css'>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

<header class="headerfixed">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12 headerbox">
        <ul class="list-inline lefthead">
          <li><a href="http://wtp.ibncindia.com/register.php"><img src="{{URL::to('/web/images/ibnclogo.jpg')}}"></a></li>
        </ul>
        <ul class="pull-right righthead righthead1">
					<li class=" login1">Contact Us:<a href="">+91-9555601111</a></li>
					<li class="login1">Email:<a href="">support@goeventz.com</a></li>
				</ul>  
      </div>
    </div>
  </div>
</header>

<section>
  <div class="container mt45">
    <div class="row">
      <div class="col-sm-12">
        <div class="errormessage text-center">
          <img src="{{URL::to('/web/images/successimage.png')}}" class="img-resposive">
          <h1>Thank You !</h1>
          <p>Hi {{$data['name']}}, Your payment is Successful.</p>
          <p>Your payment e-recipet has been sent on the given email: <a href="">{{$data['email']}}</a>  </p>
          <p>For any queries related to your payment. Please <a href="{{URL::to('/contactus')}}">Contact Us Here</a> or call us on  +91-9555601111.  </p>
        </div>
      </div>
    </div>
  </div>
</section>
 @include('includes.web.footer')

</body>
</html>