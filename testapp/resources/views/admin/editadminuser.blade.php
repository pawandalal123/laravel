 @extends('layout.adminlayout')
 @section('content')
  <script type="text/javascript">
  $(document).ready(function()
  {
    $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});  

  });
  </script>
        <div class="content">
          <div class="breadLine">
          <ul class="breadcrumb">
          <li><a href="#">Admin</a> <span class="divider">></span></li>                
          <li class="active">User</li>
          </ul>
          </div>

            <div class="workplace">
                <div class="page-header">
                    <h1>Admin User Management</h1>
                </div> 
                <div class="row">
                @if(Session::has('message'))
                                <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ Session::get('message') }}
                                </div>
                              @endif
                                   <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Make Admin User</h1>
                        </div>
                        <div class="block-fluid">                        
        <div class="row-form clearfix">
         <form action='' method='post' id='validation' enctype='multipart/form-data' novalidate>
          <div class="form-group">
            <label for="ogn">User Name</label>
              {!! Form::text("name",$getUserdetails->name,array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Name",'maxlength'=>25)); !!}
              @if ($errors->has('name')) 
             <div style="color:red">{{ $errors->first('name') }}</div> 
             @endif
          </div>
          <div class="form-group">
            <label for="ogn">User Email</label>
              {!! Form::text("email",$getUserdetails->email,array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Email",'maxlength'=>40)); !!}
           @if ($errors->has('email')) 
             <div style="color:red">{{ $errors->first('email') }}</div> 
             @endif
          </div>
          <div class="form-group">
            <label for="ogn">User Mobile</label>
              {!! Form::text("mobile",$getUserdetails->mobile,array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Mobile",'maxlength'=>25)); !!}
         @if ($errors->has('mobile')) 
             <div style="color:red">{{ $errors->first('mobile') }}</div> 
             @endif
          </div>
         <?php
         $userTypeArray = array('1'=>'Admin','2'=>'Sub User'); 
         ?>
          <div class="form-group">
            <label for="ogn">User Type</label>
              <select class="form-control xyz" name="type" id="selectchange">
               @foreach($userTypeArray as $key=>$value)
               @if($key==$getUserdetails->type)
               <option value="{{$key}}" selected="selected">{{$value}}</option>
               @else
               <option value="{{$key}}">{{$value}}</option>
               @endif
               @endforeach
              </select>
          </div>
         
         
          <div class="text-center mt15">
             <input name="submit" type="submit" class="btn btn-default" value="Save">
          </div>
        </form>
      
      </div>
     </div>
    </div>
   </div>
  
</section>


 
<div class="mt45"></div> 
  <script type="text/javascript">
  $(document).ready(function()
  {
    $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});  

  });
  </script>

@stop

