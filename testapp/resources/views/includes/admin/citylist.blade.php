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
                            <h1>Text fields</h1>
                        </div>
                        <form action='' method='post' id='validation' class="form-horizontal">
                        <div class="block-fluid">                        
                                     
                                     <div class="row-form clearfix">
                                <div class="col-md-3">Select Country:</div>
                                <div class="col-md-9">
                                <select name="country" class="form-control validate[required]">
                                            <option value="">choose a option...</option>
                                            @if(count($dataCuntry)>0)
                                              @foreach($dataCuntry as $dataCuntry)
                                               {{--*/ $selected='' /*--}}
                                               @if(@$datatoedit->country_id==$dataCuntry->id)
                                               {{--*/ $selected='selected' /*--}}
                                               @endif()
                                               <option value="{{$dataCuntry->id}}" {{$selected}}>{{ucwords($dataCuntry->country)}}</option>
                                              @endforeach()
                                            @endif   
                                    </select>
                                </div>
                                 @if ($errors->has('country')) 
                                 <div style="color:red">{{ $errors->first('country') }}</div> 
                                 @endif
                            </div> 
                              <div class="row-form clearfix">
                                <div class="col-md-3">Select state:</div>
                                <div class="col-md-9">
                                <select name="state" class="form-control validate[required]">
                                            <option value="">choose a option...</option>
                                            @if(count($dataState)>0)
                                              @foreach($dataState as $dataState)
                                               {{--*/ $selected='' /*--}}
                                               @if(@$datatoedit->state==$dataState->id)
                                               {{--*/ $selected='selected' /*--}}
                                               @endif()
                                               <option value="{{$dataState->id}}" {{$selected}}>{{ucwords($dataState->state)}}</option>
                                              @endforeach()
                                            @endif    
                                    </select>
                                </div>
                                 @if ($errors->has('state')) 
                                 <div style="color:red">{{ $errors->first('state') }}</div> 
                                 @endif
                            </div> 

                            <div class="row-form clearfix">
                                <div class="col-md-3">City name:</div>
                                <div class="col-md-9"><input type="text" name="city" class="form-control validate[required]"  placeholder="placeholder..." value="{{@$datatoedit->city}}" /></div>
                                 @if ($errors->has('city')) 
                                 <div style="color:red">{{ $errors->first('city') }}</div> 
                                 @endif
                            </div>                                                               

                                                                       

                            <div class="footer tar">
                            <input class="btn btn-default" type="Submit" name="citysubmit" value="Submit">
                                <!-- <button >Submit</button> -->
                            </div>                            
                        </div>

                    </div>

                </div>
                @if(count($dataCity)>0)
                  <div class="row">

                    <div class="col-md-12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Simple table</h1>      
                            <ul class="buttons">
                                <li><a href="#" class="isw-download"></a></li>                                                        
                                <li><a href="#" class="isw-attachment"></a></li>
                                <li>
                                    <a href="#" class="isw-settings"></a>
                                    <ul class="dd-list">
                                        <li><a href="#"><span class="isw-plus"></span> New document</a></li>
                                        <li><a href="#"><span class="isw-edit"></span> Edit</a></li>
                                        <li><a href="#"><span class="isw-delete"></span> Delete</a></li>
                                    </ul>
                                </li>
                            </ul>                        
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                    <tr>                                    
                                        <th width="25%">ID</th>
                                         <th width="25%">City</th>
                                        <th width="25%">State</th>
                                        <th width="25%">Country</th>
                                        <th width="25%">Created On</th>
                                        <th width="25%">Current status</th>
                                        <th width="25%">Action</th>                                       
                                    </tr>
                                </thead>
                                <tbody>
                                   {{--*/ $i=1 /*--}}
                                @foreach($dataCity as $dataCity)
                                   {{--*/ $status='active' /*--}}
                                   {{--*/ $textdisplay='Make Disable' /*--}}
                                 @if($dataCity->status==0)
                                   {{--*/ $status='deactive' /*--}}
                                   {{--*/ $textdisplay='Make Active' /*--}}
                                 @endif
                                    <tr>                                    
                                        <td>{{$i}}</td>
                                         <td>{{$dataCity->city}}</td>
                                         <td>{{$dataCity->state}}</td>
                                        <td>{{$dataCity->country}}</td>
                                        <td>{{$dataCity->created_at}}</td>
                                        <td>{{$status}}</td> 
                                        <td><a href="{{URL::to('/admin/location/city/'.$dataCity->id)}}">Edit</a>  
                                        <a href="{{URL::to('locationstatus/city/'.$dataCity->id)}}">{{$textdisplay}}</a></td>                                   
                                    </tr>
                                     {{--*/ $i++ /*--}}
                                    @endforeach()
                                                               
                                </tbody>
                            </table>
                        </div>
                    </div>                                

                </div>
                @endif



           