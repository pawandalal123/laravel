@extends('layout.default')
@section('content')
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
          <h1>Contact Us</h1>
      </div>
    </div>
  </div>
</section>
<div class="container mt45">
  <div class="row">
    <h1 class="mainheading">contact us</h1>
  </div>
</div>
<div class="container contact">
  <div class="row">
    <div class="col s12 m8 l8">
      <div class="row">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in elementum purus. Curabitur fermentum lacus sem, et placerat lectus facilisis at. Suspendisse vulputate eget ligula porttitor commodo. Suspendisse vitae porttitor ipsum. Nullam aliquet maximus odio, ornare vehicula felis lobortis sed. Ut in magna enim. Proin at elit convallis libero tempus finibus. </p>
          @if(Session::has('message'))
          <p class="alert alert-{{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
          @endif
        
          <form method="post" action="" id="contactUsForm">
          <div class="article-hire-sidebar contact-form">
            <div class="input-field">
               <input type="text" class="required " name="name"  placeholder="Name" title="Please enter name.">
              <label>Name</label>
            </div>
            <div class="input-field">
             <input type="email" class="form-control required" name="email" placeholder="Email"  title="Please enter email address.">
              <label>Email</label>
            </div>
             <div class="input-field">
            <input type="text" class="form-control required" name="mobile" placeholder="Mobile"  title="Please enter your mobile no." >
              <label>Mobile</label>
            </div>
            <div class="input-field">
      
                <textarea class="materialize-textarea required" name="message"  rows="2" placeholder="Message"  title="Please enter your message."></textarea>  
              <label>Enter Your Message</label>
            </div>
            {!! Form::submit('Contact', array('class' => 'waves-effect waves-light btn article-hire-button')) !!}
          </div>
           {!! Form::close() !!}
        </div>
    </div>
    <div class="col s12 m4 l4 left-link">
      <div class="sidebar-box">
        <h5>Quick Contact</h5>
        <p>Duis ullamcorper urna diam, sed convallis erat pellentesque sagittis. Duis faucibus leo sit amet ornare scelerisque. Proin vel mattis libero, et malesuada ante. Aliquam et justo finibus, aliquam lacus sit amet, maximus enim. Cras a placerat enim, id vehicula risus. Aliquam porttitor mollis est, vitae malesuada massa rhoncus suscipit.</p>
        <p><strong>Email: You@company.com </strong></p>
        <p><strong>Contact: 000-000000 </strong></p>
        <div class="socail">
          <a class="btn-floating btn-large waves-effect waves-light red"><img src="{{URL::to('web/site/images/facebook-logo.png')}}"></a>
          <a class="btn-floating btn-large waves-effect waves-light red"><img src="{{URL::to('web/site/images/twitter.png')}}"></a>
          <a class="btn-floating btn-large waves-effect waves-light red"><img src="{{URL::to('web/site/images/google-plus.png')}}"></a>
          <a class="btn-floating btn-large waves-effect waves-light red"><img src="{{URL::to('web/site/images/linkedin-logo.png')}}"></a>
        </div>
      </div>
    </div>
  </div>
</div>


  

<script type="application/javascript"   src="{{URL::to('web/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">

jQuery.validator.addMethod("checkurl", function(value, element)
{
// now check if valid url
return /^(www\.)[A-Za-z0-9_-]+\.+[A-Za-z0-9.\/%&=\?_:;-]+$/.test(value);
}, "Please enter a valid URL."
);


  $("#contactUsForm").validate({
      rules: {
                        "email": {                            
                            email: true
                        },
  
              "mobile": {                            
              minlength: 10,
                            number:true
              }
                    },
                    messages: {
                        "email": {                           
                            email: "Please enter valid email address"
                        },
         
            "mobile": {       minlength:"Please enter valid mobile number"  ,
                              // maxlength:"Please enter valid mobile number",
                              number:"Please enter only numbers"                  
                            
                        }
                    }
                
    });
</script>
@stop