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
    <div class="col s12 m4 l3">
        <div class="sidebar card">
          <div class="card horizontal">
            <div class="card-image">
              <img src="{{$imagePatah}}" alt="">
            </div>
            <div class="card-stacked">
              <div class="card-action">
                {{ucwords($name)}}
              </div>
              <div class="card-content">
                <p>Web Designer</p>
                <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i>
              </div>
            </div>
          </div>

          <div class="switch">
            <label>
              Deactivate
              <input type="checkbox" checked="">
              <span class="lever"></span>
               Activate Profile
            </label>
          </div>
          
          <div class="status-box">
            Profile Status
            <label class="status-percent">70%</label>
            <div class="progress">
                <div class="determinate" style="width: 70%"></div>
            </div>
          </div>

          <a class="waves-effect waves-light btn">Become a Job Owner</a>
          <a href="{{URL::to('/profile')}}" class="waves-effect waves-light btn active">Basic Info</a>
          <a href="{{URL::to('/digital-locker')}}" class="waves-effect waves-light btn">Digital Locker</a>
           <a class="waves-effect waves-light btn" href="{{URL::to('profile/articles')}}">Articles</a>
          <a class="waves-effect waves-light btn" href="{{URL::to('profile/discussions')}}">Discussion Forum</a>
          <a class="waves-effect waves-light btn" href="{{URL::to('profile/invitediscussion')}}">Invite to Join Discussion Forum</a>
          <a class="waves-effect waves-light btn" href="javascript:void(0)" onclick="connectmailbox()">Connect Via Mail</a>
            <!-- Modal Structure -->
           
        </div>
    </div>
    @endif