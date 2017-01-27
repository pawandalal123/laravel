 <script type="text/javascript">
  $(document).ready(function()
  {
    $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});  

  });
  </script>
<div class="row">
@if(Session::has('message'))
                                <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ Session::get('message') }}
                                </div>
                               
                              @endif
                    <div class="col-md-12">
                        <div class="head clearfix">
                            <div class="isw-documents"></div>
                            <h1>Manage Country</h1>
                        </div>
                         <!-- {!! Form::open(['url' => 'postIndex', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'validation','class'=>'form-horizontal']) !!} -->
                         <form action='' method='post' id='validation' class="form-horizontal">
                        <div class="block-fluid">                        
                                       
                            <div class="row-form clearfix">
                                <div class="col-md-3">Country:</div>
                                <div class="col-md-9">
                                <input name="country" type="text" class="form-control validate[required]" placeholder="placeholder..." value="{{@$datatoedit->country}}" />
                                </div>
                                @if ($errors->has('country')) 
                                 <div style="color:red">{{ $errors->first('country') }}</div> 
                                 @endif
                                </div>                                                               


                            <div class="footer tar">
                            @if(@$datatoedit->country)
                            <input class="btn btn-default" type="Submit" name="updatecountry" value="Update">
                            @else
                            <input class="btn btn-default" type="Submit" name="submitcountry" value="Submit">
                            @endif

                            </div>                            
                        </div>
                        </form>

                    </div>

                </div>
                @if(count($dataCuntry)>0)
                <div class="row">

                    <div class="col-md-12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Country List</h1>      
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                    <tr>                                    
                                        <th width="25%">ID</th>
                                        <th width="25%">Country Name</th>
                                        <th width="25%">Created On</th>
                                        <th width="25%">Current status</th>
                                        <th width="25%">Action</th>                                   
                                    </tr>
                                </thead>
                                <tbody>
                                {{--*/ $i=1 /*--}}
                                @foreach($dataCuntry as $dataCuntry)
                                   {{--*/ $status='active' /*--}}
                                   {{--*/ $textdisplay='Make Disable' /*--}}
                                 @if($dataCuntry->status==0)
                                   {{--*/ $status='deactive' /*--}}
                                   {{--*/ $textdisplay='Make Active' /*--}}
                                 @endif
                                    <tr>                                    
                                        <td>{{$i}}</td>
                                        <td>{{$dataCuntry->country}}</td>
                                        <td>{{$dataCuntry->created_at}}</td>
                                        <td>{{$status}}</td> 
                                        <td><a href="{{URL::to('/admin/location/'.$dataCuntry->id)}}">Edit</a>  
                                        <a href="{{URL::to('locationstatus/'.$dataCuntry->id)}}">{{$textdisplay}}</a></td>                                   
                                    </tr>
                                     {{--*/ $i++ /*--}}
                                    @endforeach()

                                </tbody>
                            </table>
                        </div>
                    </div>                                

                </div>
                @endif



           