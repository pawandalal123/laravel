@extends('layout.default')
@section('content')
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
          <h1>Article Hire</h1>
      </div>
    </div>
  </div>
</section>
<section class="user-listing">
  <div class="container user-profile">
  <div class="row">
    <div class="col s12 m4 l3">
        <div class="sidebar">
          <p><strong>Refine Results</strong></p>
          <label>Category</label>
          <select class="browser-default">
            <option value="" disabled="" selected="">Select</option>
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
          </select>
          <label>Sub Category</label>
          <select class="browser-default">
            <option value="" disabled="" selected="">Select</option>
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
          </select>
          <button class="waves-effect waves-light btn">Search</button>
        </div>
        @if(Auth::check())
        @include('includes.partials.articleform')
        @else
        @include('includes.partials.loginbox')
        @endif
        
    </div>
    <div class="col s12 m8 l9">
      <div class="col s12 m12 l12 middle-sec">
        <div class="row">
          <div class="job-viewby-number">
            <div class="row">
              <div class="col s6 m6 l6"><input type="search" placeholder="Search" name=""></div>
              <div class="col s6 m6 l6 right-align">
                <select class="browser-default">
                  <option value="" disabled selected>Sort by: Date</option>
                  <option value="1">Option 1</option>
                  <option value="2">Option 2</option>
                  <option value="3">Option 3</option>
                </select>

              </div>

            </div>
          </div>

         @if(count($articlelist)>0)
         @foreach($articlelist as $article)
          <div class="article-box card">
            <h5>{{ucwords($article->title)}}</h5>
            <p><?php echo substr($article->description,0,300);?></p>
            <div class="row">
              <div class="col s12 m6 l6"><a href="" class="share-box">Share</a></div>
              <div class="col s12 m6 l6 comment-view">
              <span class="comment">Comment 2</span> 
              <a href="{{URL::to('articledetail/'.$article->article_url)}}" class="waves-effect waves-light btn">Read More</a>
              </div>
            </div>
          </div>
           @endforeach()
          <?php echo $articlelist->render();?>
          @else
          <div class="text-center">No article found......</div> 
         @endif
        
        </div>
      </div>
    </div>
  </div>
</div>
</section>



<script type="text/javascript">
   $(document).ready(function() {
    $('select').material_select();
  });
</script>
@stop