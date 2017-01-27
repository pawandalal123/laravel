<!DOCTYPE html>
<html>
<head>
	<title>GoEventz</title>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,width=device-width,user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">	
	
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<table width="100%" height="100%" align="center" cellspacing="0" cellpadding="0" border="0"  style="border-spacing:0; border-collapse:collapse; vertical-align:top; height:100%;width:100%;font-family:'HelveticaNeue-Light','Helvetica Neue Light','Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif;font-weight:normal;line-height:20px;font-size:14px;margin:0;padding:0;background-image:url({{URL::asset('web/images/background.jpg')}})">
<tbody>
<tr>
<td>
  <table align="center" cellspacing="0" cellpadding="0" border="0"style="border-collapse:separate!important; background-color:transparent; max-width:650px; padding:5px!important;  color:#fff;">

    <tr>
      <td>
        <table width="100%" align="center"cellspacing="0" cellpadding="0"  border="0" style="border-collapse:separate!important; background-color:transparent; max-width:640px;  color:#fff;">
          <tbody>
            <tr>
              <td align="left">
                <img src="{{URL::asset('web/images/logowh.png')}}"style="width:100%; max-width:110px; display: block;margin-bottom: 5px;">
              </td>
               <td align="right">
              <div style="margin-bottom: 5px;">
                <a href="https://www.facebook.com/goeventz" style="width: 22px;height: 22px;text-align: center;display: inline-block;line-height: 22px;font-size: 18px;background-color: #ccc;margin-left: 4px; color:#555;"><img src="{{URL::asset('web/images/facebook22.jpg')}}"></a>
                <a href="https://twitter.com/goeventz" style="width: 22px;height: 22px;text-align: center;display: inline-block;line-height: 22px;font-size: 18px;background-color: #ccc;margin-left: 4px;color:#555;"><img src="{{URL::asset('web/images/twitter22.jpg')}}"></a>
                <a href="http://blog.goeventz.com/" style="width: 22px;height: 22px;text-align: center;display: inline-block;line-height: 22px;font-size: 18px;background-color: #ccc;margin-left: 4px; color:#555;"><img src="{{URL::asset('web/images/blog-22.jpg')}}"></a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" align="center" border="0" style="border-collapse:separate!important; background-color:#fff; padding:2px 10px!important; max-width:640px;  color:#444;">
          <tbody>
            <tr>
              <td>
              <p>Seems like you are having trouble login. We’re here to help you! .</p>

Click here to reset your password: {{ url('password/reset/'.$token) }}
<p>After clicking the link, you will be prompted to create a new password for your goeventz.com account.

<p>Remember, you can always change your password by clicking the "Change Password" tab.</p>

<p>If you have not asked for your password to be reset, please contact us. Thank you for using 

goeventz.com!</p>

<p>You are most welcome to contact us for any query, comments and suggestions at 

support@goeventz.com</p>
			 
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
   

    <tr>
      <td align="center">
        <div style="background-color: rgba(0, 80, 141, 0.9); border: 2px solid #5294CA;margin-top: 5px;padding: 5px;  font-size:12px;">
        
        <span style="display: block;line-height: 20px;">GoEventz | 345 3<sup>rd</sup> Floor, Tower B2, Spaze I-Tech Park, Sector-49, Sohna Road, Gurgaon-122018, Haryana, India</span></div>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  </tbody>
</table> 

</body>
</html>

        