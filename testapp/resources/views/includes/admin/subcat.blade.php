               
<script type="text/javascript">
  $(document).ready(function()
  {
    $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});  

  });
  </script> <div class="row">
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
                                <select name="category" class="form-control validate[required]">
                                            <option value="">choose a option...</option>
                                            @if(count($dataCat)>0)
                                              @foreach($dataCat as $dataCat)
                                               {{--*/ $selected='' /*--}}
                                               @if(@$datatoedit->category_id==$dataCat->id)
                                               {{--*/ $selected='selected' /*--}}
                                               @endif()
                                               <option value="{{$dataCat->id}}" {{$selected}}>{{ucwords($dataCat->name)}}</option>
                                              @endforeach()
                                            @endif
                                    </select>
                                </div>
                                @if ($errors->has('category')) 
                                 <div style="color:red">{{ $errors->first('category') }}</div> 
                                 @endif
                            </div>  
                            <div class="row-form clearfix">
                                <div class="col-md-3">state:</div>
                                <div class="col-md-9">
                                <input type="text" name="name" class="form-control validate[required]" placeholder="placeholder..." value="{{@$datatoedit->name}}" /></div>
                                @if ($errors->has('name')) 
                                 <div style="color:red">{{ $errors->first('name') }}</div> 
                                 @endif
                            </div>                                                              

                            <div class="footer tar">
                             @if(@$datatoedit->state)
                              <input type="submit" name="updatestate" value="Update" class="btn btn-default">
                             @else
                                <input type="submit" name="submitsubcat" value="submit" class="btn btn-default">
                                @endif
                            </div>                            
                        </div>
                        </form>

                    </div>
                </div>
                @if(count($datasubcat)>0)
                  <div class="row">

                    <div class="col-md-12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Subcategory List</h1>      
                                                   
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                    <tr>                                    
                                        <th width="25%">ID</th>
                                        <th width="25%">subcat</th>
                                        <th width="25%">category</th>
                                        <th width="25%">Created On</th>
                                        <th width="25%">Current status</th>
                                        <th width="25%">Action</th>                                       
                                    </tr>
                                </thead>
                                <tbody>
                                   {{--*/ $i=1 /*--}}
                                @foreach($datasubcat as $datasubcat)
                                   {{--*/ $status='active' /*--}}
                                   {{--*/ $textdisplay='Make Disable' /*--}}
                                 @if($datasubcat->status==0)
                                   {{--*/ $status='deactive' /*--}}
                                   {{--*/ $textdisplay='Make Active' /*--}}
                                 @endif
                                    <tr>                                    
                                        <td>{{$i}}</td>
                                         <td>{{$datasubcat->name}}</td>
                                        <td>{{$datasubcat->category}}</td>
                                        <td>{{$datasubcat->created_at}}</td>
                                        <td>{{$status}}</td> 
                                        <td><a href="{{URL::to('/admin/articles/subcategory/'.$datasubcat->id)}}">Edit</a>  
                                        <a href="{{URL::to('categorystatus/subcategory/'.$datasubcat->id)}}">{{$textdisplay}}</a></td>                                   
                                    </tr>
                                     {{--*/ $i++ /*--}}
                                    @endforeach()
                                                               
                                </tbody>
                            </table>
                        </div>
                    </div>                                

                </div>
                @endif



           