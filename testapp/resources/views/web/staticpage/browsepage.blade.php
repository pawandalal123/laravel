@extends('layout.default')
@section('content')

<section>
  <div class="container mt45">
    <div class="row">
      <div class="col-sm-12 text-center featured">
        <h3 class="featur-heading">
         @if($iscountry!='')
        Browse by city


        @else
       Browse by country
        @endif
</h3>
        <div class="borderB"></div>
      </div>
    </div>
    <div class="row div-min-height">
      @if(count($cityData)>0)
          @foreach($cityData as $cityData)
          @if($iscountry!='')
            <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="city-browse">
              <a track-type="event-browse" track-element="event-listing-country"   href="{{URL::to('browse/'.str_replace(' ','-',strtolower($cityData->country)).'/'.str_replace(' ','-',strtolower($cityData->city)))}}" class="citytext"> {{$cityData->city}}</a>
            </div>
          </div>
     
          @else
         <div class="col-sm-6 col-md-3">
          <div class="country-city">
            <a track-type="event-browse" track-element="event-listing-country"   href="{{URL::to('browse/'.str_replace(' ','-',strtolower($cityData->country)))}}" class="countrytext"><!-- <img class="" src="http://localhost/php/go2/GEsite/web/images/flag/India-Flag-24.png" alt="India Flag"> --> {{$cityData->country}}</a>
            <a track-type="event-browse" track-element="event-listing-country"   href="{{URL::to('directory/countries/'.str_replace(' ','-',strtolower($cityData->country)))}}" class="pull-right">browse cities</a>
          </div>
        </div>
           @endif
          @endforeach
         @endif
    
    </div>
  </div>
</section>



@include('includes.web.usefulllink')

@stop




