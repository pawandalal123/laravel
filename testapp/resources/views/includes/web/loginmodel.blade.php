<div class="modal-body">
         
        
              <h3 class="centerline mt30"><span>or login/signup with email</span></h3>
              
                <div class="form-group">
                  <input type="text" class="form-control" name='user_email' id='user_email' placeholder='Email'>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name='password' id='password' placeholder='Password'>
                </div>
                <div>
                <button class="btn btn-login btn-block btn-lg" onClick="checkuserlogin();">Login</button>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox"> Remember me</label>
                  <a href="{{URL::to('password/email')}}" class="pull-right">Forgot password ?</a>
                </div>
             
            </div>