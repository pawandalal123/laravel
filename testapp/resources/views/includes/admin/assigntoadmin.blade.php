<div class="modal-body">
              <h3 class="centerline"><span>Select Admin Manager</span></h3>
               @foreach($alluserList as $alluserList)
               @if(in_array($alluserList->id,$checkAssign))
               <input name="assignid" id="assignid" type="checkbox" checked value="{{$alluserList->id}}"> {{$alluserList->name}}
               @else
               <input name="assignid"  id="assignid" type="checkbox" value="{{$alluserList->id}}"> {{$alluserList->name}}
               @endif
               @endforeach
                <div>
                <button class="btn btn-login btn-block btnmy" onClick="submitassign(<?php echo $userid?>)">Assign</button>
                <button class="btn btn-login btn-block btnmy" onClick="sendassignmail(<?php echo $userid?>)">Send Mail</button>
                </div>
              
             
            </div>