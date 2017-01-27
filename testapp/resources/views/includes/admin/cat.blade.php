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
                            <h1>Make New Category</h1>
                        </div>
                        <form action='' method='post' id='validation' class="form-horizontal">
                        <div class="block-fluid">                        
                            <div class="row-form clearfix">
                                <div class="col-md-3">Category name:</div>
                                <div class="col-md-9"><input type="text" name="name" class="form-control validate[required]"  placeholder="placeholder..." value="{{@$datatoedit->name}}"/></div>
                                 @if ($errors->has('name')) 
                                 <div style="color:red">{{ $errors->first('name') }}</div> 
                                 @endif
                            </div>                                                               

                                                                       

                            <div class="footer tar">
                            <input class="btn btn-default" type="Submit" name="submitcat" value="Submit">
                                <!-- <button >Submit</button> -->
                            </div>                            
                        </div>

                    </div>

                </div>
                @if(count($dataCat)>0)
                  <div class="row">
                    <div class="col-md-12">                    
                        <div class="head clearfix">
                            <div class="isw-grid"></div>
                            <h1>Category List</h1>      
                                           
                        </div>
                        <div class="block-fluid">
                            <table cellpadding="0" cellspacing="0" width="100%" class="table">
                                <thead>
                                    <tr>                                    
                                        <th width="25%">ID</th>
                                         <th width="25%">name</th>
                                        <th width="25%">Created On</th>
                                        <th width="25%">Current status</th>
                                        <th width="25%">Action</th>                                       
                                    </tr>
                                </thead>
                                <tbody>
                                   {{--*/ $i=1 /*--}}
                                @foreach($dataCat as $dataCat)
                                   {{--*/ $status='active' /*--}}
                                   {{--*/ $textdisplay='Make Disable' /*--}}
                                 @if($dataCat->status==0)
                                   {{--*/ $status='deactive' /*--}}
                                   {{--*/ $textdisplay='Make Active' /*--}}
                                 @endif
                                    <tr>                                    
                                        <td>{{$i}}</td>
                                         <td>{{$dataCat->name}}</td>
                                        <td>{{$dataCat->created_at}}</td>
                                        <td>{{$status}}</td> 
                                        <td><a href="{{URL::to('/admin/articles/'.$dataCat->id)}}">Edit</a>  
                                        <a href="{{URL::to('categorystatus/'.$dataCat->id)}}">{{$textdisplay}}</a></td>                                   
                                    </tr>
                                     {{--*/ $i++ /*--}}
                                    @endforeach()
                                                               
                                </tbody>
                            </table>
                        </div>
                    </div>                                

                </div>
                @endif



           