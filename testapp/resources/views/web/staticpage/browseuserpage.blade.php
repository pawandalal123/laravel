@extends('layout.default')
@section('content')
<section>
  <div class="container mt45">
    <div class="row">
      <div class="col-sm-12 text-center featured">
        <h3 class="featur-heading">
        Browse by organization
        </h3>
        <div class="borderB"></div>
      </div>
    </div>
    <div class="row div-min-height">
      <div class="col-sm-12">
        <div class="follower-list orgLikes organization-list">
          <ul class="list-inline">
             @if(count($alliuserData)>0)
               @foreach($alliuserData as $alliuserData)
            <li>
              <div class="img-organization"><a track-type="browse-page" track-element="view-user-page"   href="{{$alliuserData->profileurl}}">
                <img src="{{$alliuserData->logo}}"></a></div>
              <i><a track-type="browse-page" track-element="view-user-page"   href="{{$alliuserData->profileurl}}">{{$alliuserData->name}}</a></i>
            </li>
    
            @endforeach
            @endif
          </ul>
        </div>        
      </div>
    </div>
  </div>
</section>




@include('includes.web.usefulllink')

@stop




