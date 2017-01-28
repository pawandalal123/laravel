@extends('layout.default')
@section('content')
{{--*/ $categorylist=$catlist /*--}}
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
          <select class="browser-default catlistchange">
            <option value="" rel=''>Select</option>
           @if(count($categorylist)>0)
            @foreach($categorylist as $categorylist)
              <option value="{{str_replace(' ','-',$categorylist->name)}}" rel="{{$categorylist->id}}">{{$categorylist->name}}</option>
            @endforeach()
            @endif
          </select>
          <label>Sub Category</label>
          <select class="browser-default subcatlist">
            <option value="">Select</option>
           
          </select>
          <button class="waves-effect waves-light btn articlesearch">Search</button>
        </div>
        @if(Auth::check())
        @include('includes.partials.articleform')
        @else
         <div class="sidebar">
        @include('includes.partials.loginbox')
        </div>
        @endif
        
    </div>
    <div class="col s12 m8 l9">
      <div class="col s12 m12 l12 middle-sec">
        <div class="row">
          <div class="job-viewby-number">
            <div class="row">
              <div class="col s6 m6 l6"><input type="search" placeholder="Search" name=""></div>
              <form id="sortbyform">
              <div class="col s6 m6 l6 right-align">
                <select class="browser-default" name="sort">
                  <option value="" disabled selected>Sort by: Date</option>
                  <option value="asc">Date Asc</option>
                  <option value="desc">Date Desc</option>
                  <!-- <option value="3">Option 3</option> -->
                </select>
              </div>
              </form>
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
          <?php echo $articlelist->appends(Input::except('page'))->render();?>
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
    $("#sortbyform").change(function()
  {
    $( "#sortbyform" ).submit();
  });
  });
</script>
@stop