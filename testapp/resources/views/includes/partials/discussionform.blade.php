 <div class="col s12 m4 l4 left-link">
      <div class="article-hire-sidebar card">
        @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>

        @endif
      <form  action='' method="post">
  
        <div class="input-field">
          <input name="title" type="text" class="validate">
          <label>Discussion Heading</label>
          @if ($errors->has('title')) 
             <div class="alert alert-danger">{{ $errors->first('title') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <textarea class="materialize-textarea" name="description"></textarea>
          <label>Discussion Question/Subject Matter</label>
          @if ($errors->has('description')) 
             <div class="alert alert-danger">{{ $errors->first('description') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <input name="short_desc" type="text" class="validate">
          <label>Your Views on the Subject</label>
        </div>
        <div class="input-field">
          <input name="url" type="text" class="validate">
          <label>URL</label>
        </div>
          <div class="row">
          <div class="col s6 m6 l6">
            <!-- <button class="waves-effect waves-light btn article-hire-button">Invite People</button> -->
          </div>
          <div class="col s6 m6 l6">
           <button type="submit" name="submitdiscussion" class="waves-effect waves-light btn article-hire-button">Start Disscussion</button> 
          </div>
        </div>
        
        </form>
      </div>
    </div>