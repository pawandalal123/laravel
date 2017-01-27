<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title> Thanks - Goeventz</title>
   <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/landingpage/css/paper-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/landingpage/css/landing.css')}}">

  <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/landingpage/css/owl.carousel.css')}}">

    <script type="application/javascript"   src="{{URL::to('web/landingpage/js/jquery-3.1.1.min.js')}}"></script>
      <script type="application/javascript"   src="{{URL::to('web/landingpage/js/owl.carousel.js')}}"></script>
     <script type="application/javascript"   src="{{URL::to('web/landingpage/js/bootstrap.min.js')}}"></script>
</head>
<style>

</style>
<body data-smooth-scroll="true">
  <div class="top-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 col-xs-5 divpadding"><a href="http://goeventz.com/"><img src="{{URL::to('web/landingpage/images/logo1.png')}}"></a></div>
        <div class="col-sm-6 col-xs-7 divpadding rightdiv-text">  
          <span><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;support@goeventz.com</span>
          <span><i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;9555 60 1111</span> 
        </div>
        </div>
      </div>
    </div>
  </div>

<section class="section-bg-img">
  <div class="container-fluid">
    <div class="row mobile-text text-center">
      <img src="{{URL::to('web/landingpage/images/party.jpg')}}" class="img-responsive">
      <div class="col-sm-12 text-on-img">
        <h4> Let's Party Together!</h4>
        <h5>This New Year Double Your Ticket Sales with Goeventz.</h5>
      </div>
    </div>
    <div class="row landing-w-text landing-thanks">
      <div class="col-md-9 bg-banner-box">
        <div class="bg-banner-text text-center">
          <h1> Let's Party Together!</h1>
          <h3>This New Year Double Your Ticket Sales with Goeventz.</h3>
        </div>
      </div>
      <div class="form-div">
        <div class="thanks-img">
          <img src="{{URL::to('web/landingpage/images/Thanks.png')}}">
        </div>
        <div class="thanks-text">
          Thanks for submitting the form.<br> We'll get back to you soon.
        </div>
        <div class="web-text">
          <a href="http://goeventz.com/">www.goeventz.com</a>
        </div>
      </div>
    </div>
    <div class="div-with-icon-box">
      <div class="col-sm-12">
        <div class="owl-carousel ge-landing">
          <div class="item">
            <div class="div-with-icon">
              <span class="first-img"><img src="{{URL::to('web/landingpage/images/Do-it-Yourself.svg')}}"></span>
              <p>DIY Platform</p>
            </div>
          </div>
          <div class="item">
            <div class="div-with-icon">
              <span><img src="{{URL::to('web/landingpage/images/Online-Payment.svg')}}"></span>
              <p>Online Payment</p>
            </div>
          </div>
          <div class="item">
            <div class="div-with-icon">
              <span><img src="{{URL::to('web/landingpage/images/Secure-Payment-Gateway1.svg')}}"></span>
              <p>Secure Payment Gateway</p>
            </div>
          </div>
          <div class="item">
            <div class="div-with-icon">
              <span><img src="{{URL::to('web/landingpage/images/Track-Ticket-Sale.svg')}}"></span>
              <p>Track Ticket Sales</p>
            </div>
          </div>
          <div class="item">
            <div class="div-with-icon">
              <span><img src="{{URL::to('web/landingpage/images/Payment-Collection.svg')}}"></span>
              <p>Payment Collection</p>
            </div>
          </div>
          <div class="item">
            <div class="div-with-icon">
              <span><img src="{{URL::to('web/landingpage/images/Real-Time-Notification.svg')}}"></span>
              <p>Real Time Notification</p>
            </div>
           </div>
        </div>     
      </div>
    </div>   
  </div>
</section>
<script>
$(document).ready(function() {
  var owl = $('.owl-carousel');
  owl.owlCarousel({
    margin: 10,
    loop:true,
    slideBy:1, 
    touchDrag:true,
    autoplay:true,
    autoplayTimeout:2000,
    startPosition:0,
    responsiveClass:true,
    responsive: {
      0: {
        items: 2,
      },
      640: {
        items: 3,
      },
      768: {
        items: 4,
      },
      1024: {
        items: 5,
      },
      1170: {
        items: 6,
      }
    }
  })
})
</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68822274-1', 'auto');
  ga('send', 'pageview');

</script>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 942678037;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "mmXkCJWR9GAQlcDAwQM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/942678037/?label=mmXkCJWR9GAQlcDAwQM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

</body>
</html>
