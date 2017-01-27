@extends('layout.userdefault')
@section('content')
  {{--*/ $eventid = $event->id/*--}}
@if($loginid)
{{--*/ $eventid = $event->id.'/'.$loginid /*--}}
@endif

      @include('includes.userdashboard.eventlink')
      <div class="row">
              <div class="col-sm-12 section_heading">
                Add Attachment to confirmation mail<!-- <span> ( Lorem ipsum dolor sit amet, consectetur adipisicing elit.)</span> -->
              </div>
               
            </div>


<style type="text/css">
  .managecoupontable table thead tr th{
    vertical-align: top;
  }
</style>

 <div class="row cardbg">
    @if(Session::has('message'))
    <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {{ Session::get('message') }}
    </div>
      <script type="text/javascript">
      $(document).ready(function(){

      $onload={};
      $onload.type='add-mail-setting';
      $onload.typeValue=null;
      $onload.action='Add';
      $onload.page='add-mail-setting';
      $onload.element=null; 
      $onload.referrer=document.referrer;
      $onload.page_url=window.location.href;
      track($onload);


      });
      </script>
    @endif
   <form method="POST" action="{{URL::to('uploadattachement/'.$eventid)}}" accept-charset="UTF-8" novalidate="novalidate" id="image_gallery" enctype="multipart/form-data"><input name="_token" type="hidden" value="7jOlxPIyH50jonwDj4OKZJQZ72GBeFm5SZKXXvlT">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <div class="LoadeImage" style="background-size:100% 100%">
            
            <label for="exampleInputFile" class="imgloade">
             <i class="fa fa-upload mt5"style="display:block;" ></i>Select Files (only pdf, png, jpg, gif, doc files)</label>
            
             <input type="file" id="exampleInputFile" name="attachement" class="trackbutton" style="visibility:hidden" track-type-value="{!! $eventid !!}" track-type="dashboard-event" track-element="manage-event-upload-image-mail-setting">
          </div>
      
          <div class="form-group text-center mt10 submitimg">
            <input type="submit" name="upload" id="upload" value="Add File" class="btn-primary trackbutton" track-type-value="{!! $eventid !!}" track-type="dashboard-event" track-element="manage-event-update-mail-setting">
          </div>
        </div>
        </div>
     
      </form>
       </div>
      @if(count($allfiles)>0)
      <div class="row">
        <div class="col-sm-12 section_heading">
          Manage attachment for Confirmation mail<!-- <span> ( Lorem ipsum dolor sit amet, consectetur adipisicing elit.)</span> -->
        </div>
      </div>
      <div class="row cardbg">
        <div class="col-sm-12 mt15">
          <div class="table-responsive">
            <table class="table table-bordered user_dash_table">
              <thead>
                <tr>
                  <!-- <th style="width:50px;">S.No</th> -->
                  <th>Attachments</th>
                  <th>Status</th>
                  <th style="width:150px;"></th>
                </tr>
              </thead>
              <tbody>
                {{--*/ $i = 1 /*--}}
                @foreach($allfiles as $allfiles)
                {{--*/ $status = 1 /*--}}
                {{--*/ $text = 'Disable' /*--}}
                 {{--*/ $textbutton = 'Click to enable' /*--}}
               @if($allfiles->status==1)
               {{--*/ $status = 0 /*--}}
               {{--*/ $text = 'Enable' /*--}}
                {{--*/ $textbutton = 'Click to disable' /*--}}
               @endif
                <tr>
               <!--  <td>{{$i}}</td> -->
                <td><a class="marginright" href="{{$imageUrl = $_ENV['CF_LINK'].'/event/'.$allfiles->user_id.'/attachement/'.$allfiles->attachment}}" target="_blank">Click here to view file</a></td>
                <td>{{$text}}</td>
                <td>
                  <a class="marginright" href="{{URL::to('/changemailsetting/'.$allfiles->id.'/'.$status)}}" data-toggle="tooltip" data-placement="top" title="Change status">{{$textbutton}}</a>
                </td>
              </tr>
              {{--*/ $i++/*--}}
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif

  
  
<script type="application/javascript"   src="{{ URL::asset('web/js/createcoupon.js')}}"></script>
@stop
