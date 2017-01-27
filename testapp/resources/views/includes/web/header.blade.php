<div class="top-header">
  <div class="container">
   <div class="row">
   <div class="col s12 m6 6 header-search">
   <a href="#" class="brand-logo"><img src="{{URL::to('web/site/images/logo.png')}}" alt=""></a>
   </div>
    <div class="col s12 m6 6 header-search"> 
      <nav>
        <div class="nav-wrapper">
          <form>
            <div class="input-field">
              <input id="search" type="search" placeholder="Search.." required>
              <label for="search"><i class="material-icons">search</i></label>
              <i class="material-icons">close</i>
            </div>
          </form>
        </div>
      </nav>
    </div>
  </div>
</div>
</div>

<nav class="main-nav">
  <div class="nav-wrapper container">
    
    <ul id="nav-mobile" class="hide-on-med-and-down">
         <li><a href="{{URL::to('/')}}">Home</a></li>
          @if (Auth::check())
          <li><a href="{{URL::to('/profile')}}">Profile</a></li>
          <li><a href="{{URL::to('/articles')}}">Articles</a></li>
          <li><a href="{{URL::to('/discussions')}}">Discussions</a></li>
          <li><a href="digital-locker">Digital locker</a></li>
          @else
           <li><a href="{{URL::to('/articles')}}">Articles</a></li>
           <li><a href="{{URL::to('/discussions')}}">Discussions</a></li>
          <li><a href="{{URL::to('auth/login')}}">Digital locker</a></li>
          @endif
          <li><a href="{{URL::to('joblisting')}}">Job search</a></li>
          <li><a href="{{URL::to('/contactus')}}">Help & Support </a></li>
          <li><a href="">Connect with users</a></li>
    </ul>
  </div>
</nav>