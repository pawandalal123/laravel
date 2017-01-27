 <div class="tab-pane active">
  @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>
        @endif
 <form method="post"  id="basicprofile">
                  <div class="row">
                    <div class="input-field col s6">
                      <input placeholder="Suffix" id="suffix" type="text" class="validate">
                      <label for="suffix">Suffix</label>
                    </div>
                    <div class="input-field col s6">
                      <input name="gender" type="radio" id="test1" checked style="height: 3rem;margin: 0 0 20px 0;" value="1" />
                      <label for="test1">Male</label> 
                      <input name="gender" type="radio" id="test1" value="2" />
                      <label for="test1">Female</label>
                      <label for="first_name" class="active">Gender</label>
                    </div>
                     @if ($errors->has('gender')) 
                     <div class="error">{{ $errors->first('gender') }}</div> 
                     @endif
                  </div>
                  <div class="row">
                    <div class="input-field col s4">
                      <input placeholder="First Name" name="first_name" type="text">
                      <label for="first_name">First Name</label>
                        @if ($errors->has('first_name')) 
                     <div class="error">{{ $errors->first('first_name') }}</div> 
                     @endif
                    </div>
                    <div class="input-field col s4">
                      <input placeholder="Middle Name" name="middle_name" type="text">
                      <label for="middle_name">Middle Name</label>
                    </div>
                    <div class="input-field col s4">
                      <input placeholder="Last Name" id="last_name" name="last_name" type="text">
                      <label for="last_name">Last Name</label>
                    </div>
                  </div>  
                  <div class="row">
                    <div class="input-field col s6">
                      <input placeholder="Email ID" id="email" name="email" type="text" readonly>
                      <label for="email">Email ID</label>
                    </div>
                    <div class="input-field col s6">
                      <input placeholder="Mobile Number" id="mobile" name="mobile" type="text">
                      <label for="mobile">Mobile Number</label>
                      @if ($errors->has('mobile')) 
                     <div class="error">{{ $errors->first('mobile') }}</div> 
                     @endif
                    </div>
                  </div>  
            
                  <div class="row">
                    <div class="input-field col s6">
                      <input type="date" class="datepicker" name="dob">
                      <label for="dob">Date Of Birth</label>
                    </div>
                     <div class="input-field col s6">
                      <input placeholder="House/Unit Number" id="house_unit" name="house_unit" type="text">
                      <label for="house_unit">House/Unit Number</label>
                    </div>
                  </div>  
                  <div class="row">
                 
                    <div class="input-field col s6">
                      <input placeholder="Street" id="street" name="street" type="text">
                      <label for="street">Street</label>
                    </div>
                      <div class="input-field col s6">
                      <input placeholder="Area Name" id="area_name" name="area_name" type="text">
                      <label for="area_name">Area Name</label>
                    </div>
                  </div>  
               
                  <div class="row">
                       <div class="input-field col s4">
                      <select name="country" class="countrychange"> 
                        <option value="" >Choose your option</option>
                        @if(count($countryList)>0)
                        @foreach($countryList as $countryList)
                        <option id="{{$countryList->id}}">{{$countryList->country}}</option>
                        @endforeach
                        @endif
                       
                      </select>
                      <label>Country</label>
                    </div>
                  
                    <div class="input-field col s4">
                      <select name="state" class="statechange">
                        <option value="" disabled>Choose your option</option>
                      </select>
                      <label>state</label>
                    </div>
                      <div class="input-field col s4">
                      <select name="city">
                        <option value="" disabled selected>Choose your option</option>
                        <option value="1">Google</option>
                        <option value="2">Skype</option>
                        <option value="3">Option 3</option>
                      </select>
                      <label>city</label>
                    </div>
                  </div>  
                  <div class="row">
          
                     <div class="input-field col s4">
                      <input placeholder="Pin Number" id="pincode" name="pincode" type="text">
                      <label for="first_name">Pin Number</label>
                    </div>
                      <div class="input-field col s4">
                      <input placeholder="Mother Name" id="mother_name" name="mothername" type="text">
                      <label for="first_name">Mother Name</label>
                    </div>
                    <div class="input-field col s4">
                      <input placeholder="Father Name" id="father_name" name="fathername" type="text">
                      <label for="first_name">Father Name</label>
                    </div>
                  </div>
               
                   <div class="row">
                   <div class="input-field col s4">
                    </div>
                    <div class="input-field col s4">
                   <button type="submit" name="editbasicdetails" class="waves-effect waves-light btn article-hire-button">Submit</button> 
                  </div>
                  <div class="input-field col s4">
                    </div>
                  </div>
                  </form>
                </div>

<script type="application/javascript"   src="{{URL::to('web/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">

  $("#basicprofile").validate({
      rules: {
                        "email": {                            
                            email: true
                        },
  
              "mobile": {                            
              minlength: 10,
              number:true
              }
                    },
                    messages: {
                        "email": {                           
                            email: "Please enter valid email address"
                        },
         
            "mobile": {       minlength:"Please enter valid mobile number"  ,
            // maxlength:"Please enter valid mobile number",
            number:"Please enter only numbers"                  
                            
                        }
                    }
                
    });
</script>