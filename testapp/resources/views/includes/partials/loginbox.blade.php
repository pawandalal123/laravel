
<p><strong>Login/Signup</strong></p>
      <?php
     $reffer=$_SERVER['REQUEST_URI'];
    if(isset($_SERVER['HTTP_REFERER']))
    {
      $reffer = $_SERVER['HTTP_REFERER'];
    } 
    ?>
    <h3>Login</h3>
     <!-- {{Session::get('massage')}} -->
    {!! Form::open(['url' => 'auth/login', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form','enctype'=>'multipart/form-data']) !!}

    {!! csrf_field() !!}
    <input name="refferurl" type="hidden" value="{{$reffer}}"> 
         <div class="input-field">
          <input name="email" id="Email-or-Phone" type="text" class="validate">
          <label>Email</label>
          @if ($errors->has('email')) 
              <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
              @endif
        </div>
        <div class="input-field">
          <input name="password" id="password" type="password" class="validate">
          <label>Password</label>
          @if ($errors->has('password')) 
              <div class="alert alert-danger">{{ $errors->first('password') }}</div> 
              @endif
        </div>
  
        
        <button type="Submit" class="waves-effect waves-light btn article-hire-button">Submit</button> 
        </form>

  