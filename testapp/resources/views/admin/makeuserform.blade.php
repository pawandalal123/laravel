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
                             <form action='' method='post' id='validation'>
                                <div class="col-md-2"> 
                                {!! Form::text("name",'',array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Name",'maxlength'=>25)); !!}
                                  @if ($errors->has('name')) 
                                 <div style="color:red">{{ $errors->first('name') }}</div> 
                                 @endif</div>
                                <div class="col-md-2">
                                 {!! Form::text("email",'',array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Email",'maxlength'=>40)); !!}
                                 @if ($errors->has('email')) 
                                   <div style="color:red">{{ $errors->first('email') }}</div> 
                                   @endif
                                   </div>
                                <div class="col-md-2">{!! Form::text("mobile",'',array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User Mobile",'maxlength'=>25)); !!}
         @if ($errors->has('mobile')) 
             <div style="color:red">{{ $errors->first('mobile') }}</div> 
             @endif</div>
                                <div class="col-md-2"> {!! Form::text("password",'',array("class" => "form-control validate[required]","maxlength"=>"100","id"=>"organizer_name","placeholder"=>"User password",'maxlength'=>25)); !!}
         @if ($errors->has('password')) 
             <div style="color:red">{{ $errors->first('password') }}</div> 
             @endif</div> 
                                <div class="col-md-2"><select class="form-control xyz" name="type">
                                <option value='1'>Admin</option>
                                <option value='2'>Sub User</option>
                              </select></div>
                                <div class="col-md-2">
                                <input name="submit" type="submit" class="btn btn-default" value="Save">
                                </div>
                                </form>                         
                            </div>                                                               
                        </div>
                    </div>
                </div>
                @if(count($alluserlist)>0)
                <div class="row">
                <div class="col-md-12">                    
                <div class="head clearfix">
                <div class="isw-grid"></div>
                <h1>Admin User List</h1>      
                </div>
                <div class="block-fluid">
                   <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th><span>S.No</span>
                         Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Type</th>
                        <th>Created On</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {{--*/ $i = 1 /*--}}
            
                      @foreach($alluserlist as $alluserlist)
                     {{--*/ $type = 'admin' /*--}}
                      @if($alluserlist->type==2)
                      {{--*/ $type = 'Sub User' /*--}}
                      @endif
                      {{--*/ $status = 1 /*--}}
                        {{--*/ $text = 'Activate' /*--}}
                       @if($alluserlist->status==1)
                       {{--*/ $status = 0 /*--}}
                       {{--*/ $text = 'Deactivate' /*--}}
                       @endif
                        <tr >
                        <td><span>{{$i}} </span>{{$alluserlist->name}}</td>
                        <td>{{$alluserlist->email}}</td>
                        <td>{{$alluserlist->mobile}}</td> 
                        <td>{{$type}} </td>
                        <td>{{$alluserlist->created_at}} </td>
                        <td><a href="{{URL::to('editadminuser/'.$alluserlist->id)}}" data-toggle="tooltip" data-placement="top" >
                              <!-- <i class="fa fa-pencil"></i> -->Edit</a>&nbsp;&nbsp;
                              <a href="{{URL::to('updateuserstatus/'.$alluserlist->id.'/'.$status)}}" data-toggle="tooltip" data-placement="top" >
                              <!-- <i class="fa fa-pencil"></i> -->{{$text}}</a>
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

 </div>
</div>
@stop
