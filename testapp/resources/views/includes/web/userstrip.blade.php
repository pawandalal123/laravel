@if (Auth::check())
 <?php
 $userDetails = Auth::user();
 $imagePatah = URL::asset('web/images/profilePic.png');
 if($userDetails->profile_image)
 {
   $imagePatah = URL::asset($_ENV['CF_LINK'].'/user/'.$userDetails->id.'/profile/logo/'.$userDetails->profile_image);
   
 }
 $name = substr(substr($userDetails->email,0,strpos($userDetails->email,'@')),0,10);
 if($userDetails->name)
 {
   $name = substr($userDetails->name,0,10);
 }
?>
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m4 l3">
          <div class="card horizontal">
            <div class="card-image">
              <img src="{{$imagePatah}}" alt="">
            </div>
            <div class="card-stacked">
              <div class="card-action">
               {{$name}}
              </div>
              <div class="card-content">
                <p>Web Designer</p>
                <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i>
              </div>
            </div>
          </div>
      </div>
      <div class="col s12 m4 l3">
        <div class="slider profile-strip-slider">
          <ul class="slides">
            <li>
              <img src="images/profile-image.jpg" alt="">
              <div class="caption left-align">
                <h3>Amit Soni <span>Developer</span></h3>
                <a href="" class="waves-effect waves-light btn">Connect</a> <a href="" class="waves-effect waves-light btn">Skip</a>
              </div>
            </li>
            <li>
              <img src="{{URL::to('web/site/images/profile-image.jpg')}}" alt="">
              <div class="caption left-align">
                <h3>Anand<span>Designer</span></h3>
                <a href="" class="waves-effect waves-light btn">Connect</a> <a href="" class="waves-effect waves-light btn">Skip</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="col s12 m4 l6">
        <h6>Broadcast</h6>
        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
      </div>
    </div>
  </div>
</section>
@endif
