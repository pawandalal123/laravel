@extends('layout.default')
@section('content')
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m4 l3">
          <div class="card horizontal">
            <div class="card-image">
              <img src="{{URL::to('web/site/images/profile-image.jpg')}}" alt="">
            </div>
            <div class="card-stacked">
              <div class="card-action">
                Amit Soni
              </div>
              <div class="card-content">
                <p>Web Designer</p>
                <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i> <i class="material-icons dp48">star</i>
              </div>
            </div>
          </div>
      </div>

    
    </div>
  </div>
</section>
<div class="container">
  <div class="row">

       @include('includes.web.userleftbar')

    <div class="col s12 m8 l9">
      <div class="digital-locker">
       <div class="row">
          @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>

        @endif
        <div class="col s12">
          <ul class="tabs">
            <li class="tab col s3"><a  href="#Files"  class="active">Files</a></li>
            <li class="tab col s3"><a href="#Recent">Recently Shared</a></li>
            <li class="tab col s3"><a  href="#Upload">Upload</a></li>
          </ul>
        </div>
        <div id="Files" class="col s12">
        @if(count($getdocumentList)>0)
          <table class="bordered responsive-table">
            <thead>
              <tr>
                  <th data-field="sr">Sr No.</th>
                  <th data-field="document-name">Document Name</th>
                  <th data-field="uploaded-date">Uploaded Date</th>
                  <th data-field="detail">Detail</th>
                  <th data-field="action">Action</th>
                  <th data-field="share">Share</th>
              </tr>
            </thead>

            <tbody>
             {{--*/ $i=1 /*--}}
             @foreach($getdocumentList as $getdocumentList)
              <tr class="document{{$getdocumentList->id}}">
                <td>{{$i}}</td>
                <td>{{$getdocumentList->doctype}}</td>
                <td>{{$getdocumentList->created_at}}</td>
                <td>.doc</td>
                <td>
                <a href="javascript:void(0)" onclick="deletedocumnet('{{$getdocumentList->id}}')" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Delete">
                <i class="material-icons dp48">delete</i></a>
                 <a href="" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit">
                <i class="material-icons dp48">mode_edit</i></a>
                </td>
                <td><a href="javascript:void(0);" onclick="sharedocument('{{$getdocumentList->id}}')">Share</a></td>
              </tr>
               {{--*/ $i++ /*--}}
              @endforeach()
             
            </tbody>
          </table>
          @else
          <div class="text-center">
          Not uplode any document......
          </div>
          @endif
        </div>
        <div id="Recent" class="col s12">
        @if(count($sharelistArray)>0)
          <table class="bordered responsive-table">
            <thead>
              <tr>
                  <th data-field="sr">Sr No.</th>
                  <th data-field="document-name">Document Name</th>
                  <th data-field="uploaded-date">Shared on this date</th>
                  <th data-field="detail">Detail</th>
                  <th data-field="action">Action</th>
              </tr>
            </thead>

            <tbody>
            {{--*/ $i=1 /*--}}
            @foreach($sharelistArray as $sharelistArray)
              <tr>
                <td>{{$i}}</td>
                <td>{{$sharelistArray['docname']}}</td>
                <td>{{ date('D, M j ',strtotime($sharelistArray['shared_on']))}}</td>
                <td>.{{$sharelistArray['extension']}}</td>
                <td><a href="#pdf-link">View Doucument</a></td>
              </tr>
              {{--*/ $i++ /*--}}
              @endforeach
              
            </tbody>
          </table>
          @else
           <div class="col s12 m12 l12 slide-pdf-box" id="pdf-link">
            Not shared any document yet...
          </div>
          @endif
         
        </div>
        <div id="Upload" class="col s12 Upload-form">
            <form  action='' method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="input-field col s12">
                <select name="documettype">
                  <option value="" disabled selected>Choose your option</option>
                @if(count($doctypelist)>0)
                @foreach($doctypelist as $doctypelist)
                <option value="{{$doctypelist->id}}">{{$doctypelist->name}}</option>
                @endforeach

                @endif
                </select>
                <label>Doucument Type</label>
                 @if ($errors->has('documettype')) 
             <div class="alert alert-danger">{{ $errors->first('documettype') }}</div> 
             @endif
              </div>
            </div>
            <div class="row">
              <div class="file-field input-field col s6">
                <div class="btn">
                  <span>File</span>
                  <input name="documentfile" type="file">
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" placeholder="10th Certififcate" type="text">
                </div>
                  @if ($errors->has('documentfile')) 
             <div class="alert alert-danger">{{ $errors->first('documentfile') }}</div> 
             @endif
              </div>
               <div class="file-field input-field col s3">
              <button type="submit" name="uplodedocument" class="waves-effect waves-light btn article-hire-button">Uplode</button> 
              </div>
            </div>
            </form>
            </div>
  
        </div>
      </div>
        
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