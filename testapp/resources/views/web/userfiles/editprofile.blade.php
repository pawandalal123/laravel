@extends('layout.default')
@section('content')
<section class="">
  <div class="container">
      <div class="row">
        <div class="col s12 m12 l12 card step">
        <div class="card-body">
           @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>

        @endif
          <div id="rootwizard1" class="form-wizard form-wizard-horizontal">
              <div class="form-wizard-nav">
                <div class="progress" style="width: 75%;">
                <div class="progress-bar progress-bar-primary" style="width: 0%;"></div></div>
                <ul class="nav nav-justified nav-pills">
                  <li class="active">
                    <a href="{{URL::to('editprofile')}}">
                    <span class="step">1</span> 
                    <span class="title">Basic Info</span></a>
                  </li>
                    <li class="active">
                    <a href="{{URL::to('editprofile/social')}}">
                    <span class="step">2</span> 
                    <span class="title">Social Info</span></a>
                  </li>
                 
                </ul>
              </div>
              <div class="tab-content clearfix">
               @if($pagename=='social')
                 @include('includes.web.userfiles.editsocial')
               @elseif($pagename=='attachment')
                @include('includes.web.userfiles.editattachment')
                @elseif($pagename=='employment')
                @include('includes.web.userfiles.editemployment')
               @else
               @include('includes.web.userfiles.editbasicdetails')
               @endif
          </div>
        </div>
      </div>  
    </div>
  </div>
</section>

@stop