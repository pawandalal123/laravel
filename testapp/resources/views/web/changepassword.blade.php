@extends('layout.default')
@section('content')
<section>
  <div class="container mt45">
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2 text-center">
        <h1>Change Password</h1>
        @if(Session::has('message'))
						<div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
						<button type="button" class="close" data-dismiss="alert">×</button>
						{{ Session::get('message') }}
						</div>
							<script type="text/javascript">
							$(document).ready(function(){

							$onload={};
							$onload.type='change-password';
							$onload.typeValue=null;
							$onload.action='update';
							$onload.page='edit-password';
							$onload.element=null; 
							$onload.referrer=document.referrer;
							$onload.page_url=window.location.href;
							track($onload);


							});
							</script>
		@endif
      </div>
      {!! Form::open(['url' => 'postpassword', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'changepassword-form','enctype'=>'multipart/form-data']) !!}	
     
			<div class="col-sm-8 col-sm-offset-2 myorg">
				<div class="form-group myorgg">
					<label for="ogn">User Name/Email</label>
					<strong>{!! $user->email !!}</strong>
				</div>
				<div class="form-group">
					<label for="ogn">New Password</label>
					{!! Form::text('password',null,array('class' => 'form-control','maxlength'=>'25', 'placeholder'=>'New Password','id'=>'password')); !!}

				</div>
				<div class="form-group">
					<label for="ogn">Confirm Password</label>
					{!! Form::text('confirmpassword',null,array('class' => 'form-control', 'placeholder'=>'Confirm Password','id'=>'')); !!}

				</div> 

				<div class="text-center">
					<input name="submit" type="submit" class="btn-primary btnsize" value="Save">
				</div>
            </div>
       {!! Form::close() !!}
      
    </div>
  </div>
</section>


<div class="mt45"></div> 
<script type="application/javascript"   src="{{ URL::asset('web/js/jquery.validate.min.js')}}"></script>
<script type="application/javascript"   src="{{ URL::asset('web/js/changepassword.js')}}"></script>
@stop
