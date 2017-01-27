@extends('layout.adminlayout')
@section('content')
<div class="content">
            
            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Admin</a> <span class="divider">></span></li>                
                    <li class="active">User</li>
                </ul>
            </div>
            <div class="workplace">
                <div class="page-header">
                    <h1>User Management</h1>
                </div>
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
                        <th><!-- <span>S.No</span> -->
                        Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Social Login</th>
                        <th>Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@if(!empty($dataUser))
                    	@foreach($dataUser as $dataUsers)
	                      <tr>
	                        <td><!-- <span>{!! $counter++; !!}.</span> -->
	                        {!! $dataUsers->name; !!}</td>                     
						    <td>{!! $dataUsers->email; !!}</td>
						    <td>{!! $dataUsers->mobile; !!}</td>
						    <td>@if($dataUsers->login_type==1) Yes @else No @endif</td>
						    <td>{!! $dataUsers->created_at; !!}</td>
	                      </tr>
	                    @endforeach
	                    @endif                           
                    </tbody>
                  </table>
                <div class="text-center">
                <?php echo $dataUser->render(); ?>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
     
@stop



