@extends('layout.default')
@section('content')
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
          <h1>Discussion Hire</h1>
      </div>
    </div>
  </div>
</section>
<div class="container article-hire">
  <div class="row">
    <div class="col s12 m8 l8">
      <div class="row">
      @if(count($duscussionlist)>0)
         @foreach($duscussionlist as $duscussion)
          <div class="article-box">
            <h5>{{ucwords($duscussion->title)}}</h5>
            <p><?php echo $duscussion->title;?> </p>
            <a class="waves-effect waves-light btn" href="{{URL::to('discussiondetail/'.$duscussion->discussion_url)}}">Read More</a>
          </div>
          @endforeach()
          <?php echo $duscussionlist->render();?>
          @else
          <div class="text-center">No discussion found......</div> 
         @endif
        </div>
    </div>
     <div class="col s12 m4 l4 left-link">
      <div class="article-hire-sidebar card">
    @if(Auth::check())
    @include('includes.partials.discussionform')
    @else
    @include('includes.partials.loginbox')
    @endif
    </div>
</div>
  </div>
</div>
<script type="text/javascript">
   $(document).ready(function() {
    $('select').material_select();
  });
</script>
@stop