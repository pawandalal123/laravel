<div class="modal-body">
              <h3 class="centerline"><span>Social login</span></h3>
              <div class="facebooklogin">
                <a href="{{URL::to('sociallogin/facebook')}}" class="btn btn-facebook btn-block btn-lg">Login <span>with</span> Facebook</a>
                <i class="fa fa-facebook Ficon"></i>
              </div>
              <div class="googlelogin mt15">
               <a href="{{URL::to('sociallogin/google')}}" class="btn btn-google btn-block btn-lg">Login <span>with</span> Google</a>
               <i class="fa fa-google-plus Gicon"></i>
              </div>
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