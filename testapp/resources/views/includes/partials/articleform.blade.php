
     
     <div class="sidebar">
     <p><strong>Write Article</strong></p>
       @if(Session::has('message'))
        <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
        <button type="button" class="close" data-dismiss="alert">×</button>
        {{ Session::get('message') }}
        </div>

        @endif
      <form  action='' method="post" enctype="multipart/form-data">
        <div class="input-field">
          <select name="category">
            <option value="" disabled selected>Select Category</option>
            @if(count($catlist)>0)
            @foreach($catlist as $catlist)
              <option value="{{$catlist->name}}">{{$catlist->name}}</option>
            @endforeach()
            @endif
          </select>
          @if ($errors->has('category')) 
             <div class="alert alert-danger">{{ $errors->first('category') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <select name="subcategory">
            <option value="" disabled selected>Sub Category</option>
            @if(count($subcatlist)>0)
            @foreach($subcatlist as $subcatlist)
              <option value="{{$subcatlist->name}}">{{$subcatlist->name}}</option>
            @endforeach()
            @endif
          </select>
          @if ($errors->has('subcategory')) 
             <div class="alert alert-danger">{{ $errors->first('subcategory') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <input name="title" type="text" class="validate">
          <label>Title Of the Article</label>
          @if ($errors->has('title')) 
             <div class="alert alert-danger">{{ $errors->first('title') }}</div> 
             @endif
        </div>
        <div class="input-field">
          <textarea class="materialize-textarea" name="description"></textarea>
          <label>Write here....</label>
        </div>
        <div class="file-field input-field">
          <div class="btn">
            <span>File</span>
            <input name="articlefile" type="file" multiple>
             @if ($errors->has('articlefile')) 
             <div class="alert alert-danger">{{ $errors->first('articlefile') }}</div> 
             @endif
          </div>
          <div class="file-path-wrapper">
            <input name="articles_image"  class="file-path validate" type="text" placeholder="Add Cover To Article">
          </div>
        </div>
        <!-- <p>
          <input name="group1" type="radio" id="timeline" />
          <label for="timeline">Publish on timeline</label>
        </p>
        <p>
          <input name="group1" type="radio" id="Request" />
          <label for="Request">Request To Publish Your Article</label>
        </p> -->
        <button type="submit" name="submitarticle" class="waves-effect waves-light btn article-hire-button">Submit</button> 
        </form>
      </div>
  