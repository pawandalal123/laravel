 
<div class="col s12 m4 l6">
      <div class="col s12 m12 l12 middle-sec card user-profile">
        <div class="row">
          <div class="col s12">
            <ul class="tabs">
              <li class="tab col s6 m6 l6"><a class="waves-effect @if($currenttab=='') active @endif" href="#test1">Send INvitation</a></li>
              <li class="tab col s6 m6 l6"><a class="waves-effect @if($currenttab=='edittab') active @endif" href="#test2">View Invitation </a></li>
            <div class="indicator" style="right: 276px; left: 0px;"></div></ul>
          </div>
             @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>
        @endif
          <div id="test1" class="col s12 tab-content" >
        <form action="" method="post">
            <div class="input-field">
              <select name="discussion_name">
              <option value="" disabled>Select</option>
                @if(count($duscussionlist)>0)
                @foreach($duscussionlist as $duscussionlist)
                <option value="{{$duscussionlist->id}}">{{$duscussionlist->title}}</option>
                @endforeach()
                @endif
              </select>
              <label class="">Select Discussion</label>
               @if ($errors->has('discussion_name')) 
                     <div class="error">{{ $errors->first('discussion_name') }}</div> 
                     @endif
              </div>
         
            <div class="input-field">
              <input name="name" type="text" class="validate" value="">
              <label>Enter Name</label>
               @if ($errors->has('name')) 
                     <div class="error">{{ $errors->first('name') }}</div> 
                     @endif
            </div>
            <div class="input-field">
              <input name="email" type="text" class="validate" value="">
              <label>Enter Email</label>
               @if ($errors->has('email')) 
                     <div class="error">{{ $errors->first('email') }}</div> 
                     @endif
            </div>
              <div class="row">
              <div class="col s6 m6 l6">
                <!-- <button class="waves-effect waves-light btn article-hire-button">Invite People</button> -->
              </div>
              <div class="col s6 m6 l6">
               <button type="submit" name="sendinvite" class="waves-effect waves-light btn article-hire-button">Invite</button> 
              </div>
           
            </div></form>
                 
           </div>
          <div id="test2" class="col s12 tab-content"  style="display: none;">
            <div class="card tab-form">
            @if(count($invitationlist)>0)
         <table>
           <thead>
             <th style="color: #000">Sr.NO</th>
             <th style="color: #000">Name</th>
             <th style="color: #000">Email</th>
             <th style="color: #000">Send on</th>
           </thead>
           {{--*/ $i=1 /*--}}
           @foreach($invitationlist as $invitationlist)
           <tr>
             <td>{{$i}}</td>
             <td>{{$invitationlist->name}}</td>
             <td>{{$invitationlist->email}}</td>
             <td>{{$invitationlist->created_at}}</td>
           </tr>
           {{--*/ $i++ /*--}}
           @endforeach()
         </table>
         @endif
            </div>
          </div>
        </div>  
      </div>
    </div>
