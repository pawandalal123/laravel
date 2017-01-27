@extends('layout.loginlayout')
@section('content')

<!-- 
<div class='col-lg-4 col-lg-offset-4'>

    @if ($errors->has())
        @foreach ($errors->all() as $error)
            <div class='bg-danger alert'>{{ $error }}</div>
        @endforeach
    @endif

    <h1><i class='fa fa-lock'></i> Login</h1>
    {!! Form::open(['url' => 'postIndex', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form','enctype'=>'multipart/form-data']) !!}
    

    <div class='form-group'>
        {!! Form::label('username', 'Username') !!}
        {!! Form::text('username', null, ['placeholder' => 'Username', 'class' => 'form-control']) !!}
    </div>

    <div class='form-group'>
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
    </div>

    <div class='form-group'>
        {!! Form::submit('Login', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

</div> -->
<script type="text/javascript">
  $(document).ready(function()
  {
    $("#validation").validationEngine({promptPosition : "topLeft", scroll: true});  

  });
  </script>
<div class="loginBlock" id="login" style="display: block;">
        <h1>Welcom. Please Sign In</h1>
          @if ($errors->has())
            @foreach ($errors->all() as $error)
                <div class='bg-danger alert'>{{ $error }}</div>
            @endforeach
             @endif
        <div class="dr"><span></span></div>
        <div class="loginForm">
        
             {!! Form::open(['url' => 'postIndex', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'validation','class'=>'form-horizontal']) !!}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="text" id="inputEmail" name="username" placeholder="Email" class="form-control validate[required]"/>
                    </div>                
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="password" id="inputPassword" placeholder="Password" class="form-control validate[required]"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" style="margin-top: 5px;">
                            <label class="checkbox"><input type="checkbox"> Remember me</label>                                                
                        </div>                    
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default btn-block">Sign in</button>       
                    </div>
                </div>
            </form>  
            <div class="dr"><span></span></div>
            
        </div>
    </div>    

    </div>
  
@stop




