@extends('layout.default')
@section('content')
<section class="top-blue-sec signup-header">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
          <div class="col s6 offset-s6">
            <div class="row">
            <?php
             $reffer=$_SERVER['REQUEST_URI'];
            if(isset($_SERVER['HTTP_REFERER']))
            {
              $reffer = $_SERVER['HTTP_REFERER'];
            } 
            ?>
             <!-- {{Session::get('massage')}} -->
            {!! Form::open(['url' => 'auth/login', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form','enctype'=>'multipart/form-data']) !!}
   
            {!! csrf_field() !!}
            <input name="refferurl" type="hidden" value="{{$reffer}}"> 
              <div class="input-field col s5">
                <input name="email" id="Email-or-Phone" type="text" class="validate">
                <label for="Email-or-Phone">Email or Phone</label>
                 @if ($errors->has('email')) 
             <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
             @endif
              </div>
              <div class="input-field col s5">
                <input type="password" class="form-control" name='password' class="validate">
                <label for="login-Password">Password</label>
                @if ($errors->has('password')) 
              <div class="alert alert-danger">{{ $errors->first('password') }}</div> 
              @endif
              </div>
              <div class="input-field col s2">
                <button type="submit" class="waves-effect waves-light btn article-hire-button">Log In</button> 
              </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</section>
<section class="signup">
<div class="container">
  <div class="row">
    <div class="col s12 m8 l8 center-align">
      <div class="row">
        <img src="{{URL::to('web/site/images/signup.jpg')}}" alt="">
      </div>
    </div>
    <!-- <div class="col s12 m4 l4 left-link">
      <div class="article-hire-sidebar card">
      {!! Form::open(['url' => 'auth/register', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'eventadd-form']) !!}
        <div class="input-field">
          <input type="text" class="validate" name="name">
          <label>Name</label>
          @if ($errors->has('name')) 
             <div class="alert alert-danger">{{ $errors->first('name') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <input type="text" class="validate" name="email">
          <label>Email</label>
          @if ($errors->has('email')) 
             <div class="alert alert-danger">{{ $errors->first('email') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <input type="password" class="validate"  name="re_password">
          <label>Password</label>
          @if ($errors->has('re_password')) 
              <div class="alert alert-danger">{{ $errors->first('re_password') }}</div> 
              @endif
        </div>
        <div class="input-field">
          <input name="password_confirmation" type="password" class="validate">
          <label>Re-enter Password</label>
            @if ($errors->has('password_confirmation')) 
              <div class="alert alert-danger">{{ $errors->first('password_confirmation') }}</div> 
              @endif
        </div>
        <div>
          <div class="row">
            <div class="input-field col s4">
              <select>
                <option value="" disabled selected>Day</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="2">2</option>
                <option value="3">30</option>
              </select>
              <label>Birthday</label>
            </div>
            <div class="input-field col s4">
              <select>
                <option value="" disabled selected>Month</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
              </select>
              <label></label>
            </div>
            <div class="input-field col s4">
              <select>
                <option value="" disabled selected>Year</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
              </select>
              <label></label>
            </div>
          </div>
        </div>
        <p>
          <input name="gender" type="radio" id="Male" value="1" />
          <label for="Male">Male</label>
          <input name="gender" type="radio" id="Female" value="2" />
          <label for="Female">Female</label>
        </p>
        
        <button type="submit" class="waves-effect waves-light btn article-hire-button">Signup</button> 
        </div>
      </div>
    </div> -->
  </div>
</div>
</section>

@stop



