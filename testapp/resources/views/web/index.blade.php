@extends('layout.default')
@section('content')
@include('includes.web.userstrip')
<div class="container">
  <div class="row">
  
   @include('includes.web.userleftbar')
    <div class="col s12 m4 l6">
      <div class="middle-sec">
        <div class="row">
          <div class="col s12">
            <ul class="tabs">
              <li class="tab col s6 m6 l6"><a class="active waves-effect" href="#test1">Read Articles</a></li>
              <li class="tab col s6 m6 l6"><a class="waves-effect" href="#test2">Apply For Jobs</a></li>
            </ul>
          </div>
          <div id="test1" class="col s12 tab-content">
          @if(count($articlelist)>0)
           @foreach($articlelist as $article)
          <div class="article-box">
              <h5>{{ucwords($article->title)}}</h5>
              <p><?php echo substr($article->description,0,189);?> </p>
              <a href="{{URL::to('articledetail/'.$article->article_url)}}" class="waves-effect waves-light btn">Read Article</a>
            </div>
            @endforeach()
            <?php echo $articlelist->render();?>
            @else
            <div class="text-center">No article found......</div> 
           @endif
          </div>
          <div id="test2" class="col s12 tab-content">
            Apply For Jobs
          </div>
        </div>
      </div>
    </div>
    <div class="col s12 m4 l3">
      <div class="company-feedback-sidebanner waves-effect waves-block waves-light">
        <a href=""><img src="{{URL::to('web/site/images/compay-feedback-banner.jpg')}}" class="" alt=""></a>
      </div>

      <div class="sidebar-box">
        <h6>Appointment History & Schedule</h6>
        <p>"I highly recommend agency as a professional and extremely competent consultant who helped me find the right position criteria I was .</p>
      </div>

      <div class="sidebar-box">
        <h6>Explore </h6>
        <p>"I highly recommend agency as a professional and extremely competent consultant who helped me find the right position the key criteria I was .</p>
      </div>
    </div>
  </div>
</div>

@stop