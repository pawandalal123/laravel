@extends('layout.default')
@section('content')
<div class="container article-hire">
  <div class="row">
    <div class="col s12 m8 l8">
      <div class="row">
          <div class="article-box">
            <h5>{{ucwords($discussiondetail->title)}}</h5>
            <p> {{$discussiondetail->description}} </p>
            <div class="row">
              <div class="col s12 m6 l6"><a href="" class="share-box">Share</a></div>
              <div class="col s12 m6 l6"><a class="waves-effect waves-light btn">Comment 2</a></div>
            </div>
          </div>
          <ul class="collection">
            <li class="collection-item avatar">
              <img src="images/profile-image.jpg" alt="" class="circle responsive-img">
              <span class="title">Avinash Kumar</span>
              <p>01, Jan, 2017 <br>
                 consultant who helped me find the right position and identified the key criteria I was looking for in "I highly recommend. "I highly recommend agency as a professional and extremely competent consultant who helped me find the right position and identified the key criteria I was looking for in "I highly recommend. "I highly recommend agency as a professional and extremely competent consultant who helped
              </p>
            </li>
          </ul>
          <div class="card col s12 m12 l12">
          @if($login==1)
            <div class="input-field">
              <textarea class="materialize-textarea" name="comment" ></textarea>
              <label>Write Comment</label>
            </div>
            <a class="waves-effect waves-light btn article-hire-button cooment-butt" href="#comment-login" onclick="savecomment('{{$discussiondetail->id}}','discussion')" rel="discussion">Comment</a>
            <!-- Modal Structure -->
            @else
            <div class="input-field">
            To write comment you have to <a href="javascript:void(0)" onclick="loginbox()">login first</a>.
            </div>
            @endif
            
          </div>
        </div>
    </div>
<!--     <div class="col s12 m4 l4 left-link">
    @if(count($articlaCatlist)>0)
      <div class="article-hire-sidebar card">
        <h5>Top Category</h5>
        @foreach($articlaCatlist as $articlaCatlist)
        <div class="blog-sec-box">
        <a href=""><p><strong>{{$articlaCatlist->name}}</strong></p></a>
        </div>
        @endforeach
   @endif
      </div> -->
@if(count($getsimilararticle)>0)
      <div class="article-hire-sidebar card">
        <h5>More Discussion</h5>
        @foreach($getsimilararticle as $getsimilararticle)
        <div class="blog-sec-box">
        <a href="{{URL::to('discussiondetail/'.$getsimilararticle->discussion_url)}}"><p><strong>{{ucwords($getsimilararticle->title)}}</strong></p></a>
        </div>
        @endforeach
      
        
      </div>
      @endif
    </div>
  </div>
</div>
@stop