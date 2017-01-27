@extends('layout.userdefault')
@section('content')
<?php
 $idCreate= '';
if(isset($loginid))
{
  $idCreate= $loginid;
} 
?>
@include('includes.userdashboard.userprofile-leftbar')
        <div class="row">
        <div class="col-sm-12 section_heading">
        Edit User Profile Custom tab
        </div>
      </div>
      
       <div class="row cardbg">
           @if(Session::has('message'))
            <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('message') }}
            </div>
              <script type="text/javascript">
              $(document).ready(function(){

              $onload={};
              $onload.type='update-profile';
              $onload.typeValue=null;
              $onload.action='update';
              $onload.page='edit-profile';
              $onload.element=null; 
              $onload.referrer=document.referrer;
              $onload.page_url=window.location.href;
              track($onload);


              });
              </script>
        @endif
       <form action='' method='post' id='profiledit-form' enctype='multipart/form-data' novalidate>
   
               <div class="col-sm-12 mt15">
              <div class="form-group">
                <label class="labeluppercase">Tab name</label>
              {!! Form::text('tabname', $checktabdetails->tabname,array('class' => 'form-control xyz','maxlength'=>'100','id'=>'organizer_name','placeholder'=>'Organizer Name')); !!}
          </div>
          </div>
             <div class="col-sm-12">
              <label class="labeluppercase">Tab Content</label> 
              <div class="eventdiscript">
              {!! Form::textarea('organizer_description',$checktabdetails->tabcontent,array('class' => 'form-control form-control ckeditor xyz', 'style'=>'width:80%','placeholder'=>'organization Description','maxlength'=>'600','id'=>'editor5')); !!}
            </div>
                </div>
        
          <div class="col-sm-12">
              <div class="text-center mt15">
             <input name="submit" type="submit" class="btn-primary btnsize" value="Save">
           
          </div>
            </div>
        </form>
        
      </div>
       
   
<script type="application/javascript"   src="{{ URL::asset('web/js/addorgination.js')}}"></script>
 <script type="application/javascript"   src="{{ URL::asset('web/js/ckeditor/ckeditor.js')}}"></script>
  <script type="application/javascript"   src="{{ URL::asset('web/js/jquery.validate.min.js')}}"></script>
  <script type="application/javascript"   src="{{ URL::asset('web/js/ckeditor/ckfinder.js')}}"></script>
  <script type="text/javascript">

var editor = CKEDITOR.replace( 'editor5', {
    filebrowserBrowseUrl : '{!! url() !!}/web/js/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl : '{!! url() !!}/web/js/ckfinder/ckfinder.html?type=Images',
    filebrowserFlashBrowseUrl : '{!! url() !!}/web/js/ckfinder/ckfinder.html?type=Flash',
    filebrowserUploadUrl : '{!! url() !!}/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl : '{!! url() !!}/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl : '{!! url() !!}/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  toolbarGroups: [
  
  { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },  
  { name: 'insert' }
  
  ]
});
CKFinder.setupCKEditor( editor, '../' );

</script>
@stop


