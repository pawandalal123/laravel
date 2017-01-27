<?php
namespace App\Http\Controllers\Admin; 
use Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Model\Common;
use Appfiles\Repo\MainheadingInterface;
use Appfiles\Repo\MainsubheadingInterface;
use Appfiles\Repo\SubsubheadingInterface;
use Appfiles\Repo\HelpcentercontentInterface;
use Redirect;
use Validator;
use Appfiles\Common\AmazonS3Upload;
use Illuminate\Container\Container;
use Appfiles\Common\Functions;
use URL;
use View;
class HelpcenterController extends Controller {

   protected $functions;
   public function  __construct(Functions $function,MainheadingInterface $mainhead,MainsubheadingInterface $mainsubhead , SubsubheadingInterface $subsubhead,HelpcentercontentInterface $helpcontent)
    {
      $this->function=$function;
      $this->mainhead = $mainhead;
      $this->mainsubhead =$mainsubhead;
      $this->subsubhead =$subsubhead;
      $this->helpcontent = $helpcontent;
      $commonObj = new common();
       $getcokkiesCity = $commonObj->getcokkies('usercity');
       View::share ( 'getcokkiesCity', $getcokkiesCity );

    }
  
  
   /////////front view//////
    public function help()
    {
      $commonObj = new common();
      $subheadArray = array();
      $mainArray = array();
      $getallmainhead = $this->mainhead->getallBy(array('status'=>1),array('id','name'));
      $getallsubmainhead = $this->mainsubhead->getallBy(array('status'=>1),array('id','heading_id','name'));
      if(count($getallsubmainhead)>0)
      {
        foreach ($getallsubmainhead as $getallsubmainhead) 
        {
          $urlname = $commonObj->cleanurl($getallsubmainhead->name);
          $subheadArray[$getallsubmainhead->heading_id][] = array('displayname'=>$getallsubmainhead->name,
                                                                  'urlname'=>$urlname,
                                                                  'id'=>$getallsubmainhead->id);
        }

      }
      if(count($getallmainhead)>0)
      {
        foreach($getallmainhead as $getallmainhead)
        {
          if(array_key_exists($getallmainhead->id, $subheadArray))
          {
             //$urlname = $commonObj->cleanurl($getallsubmainhead->name);
             $mainArray[$getallmainhead->name] = $subheadArray[$getallmainhead->id];
          }

        }

      }
    
      return \View::make('web/staticpage.helpcenterfront',compact('mainArray'));   

    }
    /////////front view//////
    public function helpsubhead($pram1,$pram2,$pram3=false)
    {
      $commonObj = new common();
      $getallsubsubhead=array();
      $bradecum="<li><a  href='".URL::to('help-center/')."'>Help Center</a></li>
              <i class='fa fa-angle-right' aria-hidden='true'></i>";
      $dataArray='';
      $pagename='';
      if($pram3)
      {
        $checksubsubhead = $this->subsubhead->find($pram3);
        if($checksubsubhead)
        {
          $getsubhead = $this->mainsubhead->getBy(array('id'=>$checksubsubhead->sub_heading_id),array('name','id'));
          $pagename = $getsubhead->name;
          $getallsubsubheadlist = $this->subsubhead->getallBy(array('sub_heading_id'=>$getsubhead->id),array('name','id'));
          $counter=1;
          $subsubheadId=$pram3;
          $subsubheadname=$checksubsubhead->name;
          if(count($getallsubsubheadlist)>0)
          {
            foreach($getallsubsubheadlist as $getallsubsubheadlist)
            {
              $urlname = $commonObj->cleanurl($getallsubsubheadlist->name);
              $getallsubsubhead[$counter]['name']=$getallsubsubheadlist->name;
              $getallsubsubhead[$counter]['id']=$getallsubsubheadlist->id;
              $getallsubsubhead[$counter]['url']=$urlname;
              $counter++;
            }
            $getcontent = $this->helpcontent->getBy(array('sub_sub_heading_id'=>$pram3),array('description'));
            if($getcontent)
            {
              $dataArray['description'] = $getcontent->description;
              $dataArray['subsubheadname'] = $subsubheadname;
            }
            
            $bradecum.="<li><a href=''>".$getsubhead->name."</a></li>
            <i class='fa fa-angle-right' aria-hidden='true'></i>
            <li>".$subsubheadname."</li>";
          }
          return \View::make('web/staticpage.helpcenterdetail',compact('getallsubsubhead','pram1','dataArray','bradecum','pagename','subsubheadId')); 
        }
        else
        {
          return redirect('error404/');
        }
      }
      elseif(is_numeric($pram2))
      {
        $getsubhead = $this->mainsubhead->getBy(array('id'=>$pram2),array('name'));
        
        if($getsubhead)
        {
           $pagename = $getsubhead->name;
            $getallsubsubheadlist = $this->subsubhead->getallBy(array('sub_heading_id'=>$pram2),array('name','id'));
            $counter=1;
            $subsubheadId=0;
            $subsubheadname='';
            if(count($getallsubsubheadlist)>0)
            {
              foreach($getallsubsubheadlist as $getallsubsubheadlist)
              {
                if($subsubheadId==0)
                {
                  $subsubheadId = $getallsubsubheadlist->id;
                  $subsubheadname= $getallsubsubheadlist->name;
                }
                $urlname = $commonObj->cleanurl($getallsubsubheadlist->name);
                $getallsubsubhead[$counter]['name']=$getallsubsubheadlist->name;
                $getallsubsubhead[$counter]['id']=$getallsubsubheadlist->id;
                $getallsubsubhead[$counter]['url']=$urlname;
                $counter++;
              }
              $getcontent = $this->helpcontent->getBy(array('sub_sub_heading_id'=>$subsubheadId),array('description'));
              if($getcontent)
              {
                $dataArray['description'] = $getcontent->description;
                $dataArray['subsubheadname'] = $subsubheadname;
              }
              
              $bradecum.="<li><a href=''>".$getsubhead->name."</a></li>
              <i class='fa fa-angle-right' aria-hidden='true'></i>
              <li>".$subsubheadname."</li>";
            }
          return \View::make('web/staticpage.helpcenterdetail',compact('getallsubsubhead','pram1','dataArray','bradecum','pagename','subsubheadId')); 
        }
      
      else
      {
        return redirect('error404/');
      }

      }
      else
      {
        return redirect('error404/');
      }

    }
   ///////////help center/////////
  public function helpcenter()
  {   
     $user = Auth::user();
     if(empty($user->id) || $user->user_type != 1)
     {
             return redirect('auth/login');

     }
     $allheads = $this->mainhead->getallBy(array('status'=>1));
     $getListhead = $this->mainhead->getallby(array(),array('name','id'));
     $getListsubsub = $this->subsubhead->getallby(array(),array('name','id','sub_heading_id'));
     $getListsub = $this->mainsubhead->getallby(array(),array('id','name','heading_id'));
     $allcontent = $this->helpcontent->getallBy(array('status'=>1));
     $subsubArray = array();
     $subArray = array();
     $headArray = array();
    
     
     foreach($getListhead as $getListhead)
     {
      $headArray[$getListhead->id] = array('name'=>$getListhead->name);

     }
     foreach($getListsub as $getListsub)
     {
      $subArray[$getListsub->id] = array('name'=>$getListsub->name,
                                         'sub_head_id'=>$getListsub->heading_id);

     }
    foreach($getListsubsub as $getListsubsub)
     {
      $subsubArray[$getListsubsub->id] = array('name'=>$getListsubsub->name,
                                               'sub_head_id'=>$getListsubsub->sub_heading_id);

     }
     $counter=1;
     $contentArray = array();
     if(count($allcontent)>0)
     {
      foreach ($allcontent as $allcontent)
      {
        if(array_key_exists($allcontent->sub_sub_heading_id, $subsubArray))
        {
          $contentArray[$counter]['description'] = $allcontent->description;
          $contentArray[$counter]['createdon'] = $allcontent->created_at;
           $contentArray[$counter]['subsubheadingid'] = $allcontent->sub_sub_heading_id;
          
          $contentArray[$counter]['subsubheading'] = $subsubArray[$allcontent->sub_sub_heading_id]['name'];
          $contentArray[$counter]['subsheading'] =  $subArray[$subsubArray[$allcontent->sub_sub_heading_id]['sub_head_id']]['name'];
          $contentArray[$counter]['heading'] =  $headArray[$subArray[$subsubArray[$allcontent->sub_sub_heading_id]['sub_head_id']]['sub_head_id']]['name'];
          $counter++;
        }
        
      }

     }
    return \View::make('admin.helpcenter',compact('allheads','contentArray'));   
  }

   ///////////help center/////////
  public function helpcenternew()
  {   
     $user = Auth::user();
     if(empty($user->id) || $user->user_type != 1)
     {
             return redirect('auth/login');

     }
    
     $allheads = $this->mainhead->getallby(array(),array('name','id'));
     $getListsub = $this->mainsubhead->getallby(array(),array('id','name','heading_id'));
     $getListsubsub = $this->subsubhead->getallby(array(),array('name','id','sub_heading_id'));
     
     $subArray = array();
     $subsubArray = array();
     
     ///////////make subheadind Array////////
     if(count($getListsub)>0)
     {
       foreach($getListsub as $getListsub)
       {
         $subArray[$getListsub->heading_id][$getListsub->id] =$getListsub->name;
       }
       if(count($getListsubsub)>0)
       {
         foreach($getListsubsub as $getListsubsub)
         {
           $subsubArray[$getListsubsub->sub_heading_id][$getListsubsub->id] =$getListsubsub->name;
         }
       }
     }
     ////////main array/////

     
     
     $counter=1;
    
     
    return \View::make('admin.helpcenternew',compact('allheads','subArray','subsubArray'));   
  }

  ////////make text box//
  public function  newfeild(Request $request)
  {
    $baseUrl = url('/');

    if($request->ajax())
      {
        ?>
         <div class="modal-body">
        <?php
        if($request->feildfor=='maintext')
        {
          $contentDisplay='';
          $getcontent = $this->helpcontent->getBy(array('sub_sub_heading_id'=>$request->id));

           $getListsubsub = $this->subsubhead->getBy(array('id'=>$request->id),array('name','id','sub_heading_id'));
           
            $getListsub = $this->mainsubhead->getBy(array('id'=>$getListsubsub->sub_heading_id),array('id','name','heading_id'));
            $allheads = $this->mainhead->getBy(array('id'=>$getListsub->heading_id),array('name','id'));
          if($getcontent)
          {
            $contentDisplay=$getcontent->description;

          }
          ?>
          <div class="ge-help-right-inner-box">
                  <div class="">
                    <ul class="help-bridcun">
                      <li><?php echo $allheads->name ?></li>
                      <li><?php echo $getListsub->name ?></li>
                      <li><?php echo $getListsubsub->name ?></li>
                    </ul>
                  </div>
                  <div class="editor-box">
                     <textarea name="feildname" id="editor1" class="form-control ckeditor " placeholder="Enter main content"><?php echo $contentDisplay;?></textarea>
                  </div>
                </div>
            
          <script type="text/javascript">

          var editor = CKEDITOR.replace( 'editor1', {
              filebrowserBrowseUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/ckfinder.html',
              filebrowserImageBrowseUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/ckfinder.html?type=Images',
              filebrowserFlashBrowseUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/ckfinder.html?type=Flash',
              filebrowserUploadUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
              filebrowserImageUploadUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
              filebrowserFlashUploadUrl : '<?php echo $baseUrl ?>/web/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
            toolbarGroups: [
            
             { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },        
             { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
             { name: 'links' },
             { name: 'tools' },
             { name: 'insert' },
             { name: 'styles' },
             { name: 'insert' }
            
            ]
          });
          CKFinder.setupCKEditor( editor, '../' );

          </script>
       <?php
        }

        else
        {
        ?>
       
           <div class="form-group">
            <input type="text" class="form-control feildname" name='feildname' id='feildname' placeholder='Feild Name' value=''>
            </div>
       <?php 
        }
       ?>
            <a  class="btn btn-success" onClick="makefeild('<?php echo $request->feildfor;?>','<?php echo $request->id;?>');">Submit</a>
          </div>
            <?php  
            
        
      }

  }

  /////create feild//////////
  public function makefeild(Request $request)
  {
    $result['status']='';
    if($request->feildfor=='main')
    {
      $dataArray = array('name'=>$request->feildname,'status'=>1);
      $create = $this->mainhead->create($dataArray);
      if($create)
      {
        $result['status']='success';
        $result['id']=$create;

      }
    }
    elseif($request->feildfor=='submain')
    {
      $dataArray = array('heading_id'=>$request->id,'name'=>$request->feildname,'status'=>1);
      $create = $this->mainsubhead->create($dataArray);
      if($create)
      {
        $result['status']='success';
        $result['id']=$create;

      }

    }
    elseif($request->feildfor=='subsubmain')
    {
      $dataArray = array('sub_heading_id'=>$request->id,'name'=>$request->feildname,'status'=>1);
      $create = $this->subsubhead->create($dataArray);
      if($create)
      {
        $result['status']='success';
        $result['id']=$create;

      }

    }
    elseif($request->feildfor=='maintext')
    {
      $checktext = $this->helpcontent->getBy(array('sub_sub_heading_id'=>$request->id,'status'=>1));
      if($checktext)
      {
        $updateArray = array('description'=>$request->feildname,
                             'status'=>1);
        $update = $this->helpcontent->update($updateArray,array('sub_sub_heading_id'=>$request->id,'status'=>1));
        $result['status']='success';
        $result['message']='Updated';

      }
      else
      {
        $create = $this->helpcontent->create(array('sub_sub_heading_id'=>$request->id,
                                                   'status'=>1,
                                                   'description'=>$request->feildname,
                                                   'created_at'=>date('Y-m-d H:i:s')));
        
        $result['status']='success';
        $result['message']='Create';

      }

    }
    return response()->json([$result]);
  }
  ////////////update feilds////////
  public function update(Request $request)
  {
    $result['status']='';
    if($request->feildfor=='main')
    {
      $dataArray = array('name'=>$request->feildname);
      $update = $this->mainhead->update($dataArray,array('id'=>$request->id));
      if($update)
      {
        $result['status']='success';
      }
    }
    elseif($request->feildfor=='submain')
    {
      $dataArray = array('name'=>$request->feildname);
      $update = $this->mainsubhead->update($dataArray,array('id'=>$request->id));
      if($update)
      {
        $result['status']='success';

      }

    }
    elseif($request->feildfor=='subsubmain')
    {
      $dataArray = array('name'=>$request->feildname);
      $update = $this->subsubhead->update($dataArray,array('id'=>$request->id));
      if($update)
      {
        $result['status']='success';
      }

    }
    elseif($request->feildfor=='maintext')
    {
      $checktext = $this->helpcontent->getBy(array('sub_sub_heading_id'=>$request->id,'status'=>1));
      if($checktext)
      {
        $updateArray = array('description'=>$request->feildname,
                             'status'=>1);
        $update = $this->helpcontent->update($updateArray,array('sub_sub_heading_id'=>$request->id,'status'=>1));
        $result['status']='success';
        $result['message']='Updated';

      }
    }
    return response()->json([$result]);
  }
  ///////////getfeildsonchange///////////
  
  
  public function getfeildsonchange(Request $request)
  {
    if($request->feildfor=='submain')
    {
      $dataArray = array('heading_id'=>$request->id,'status'=>1);
      $getfeildlist = $this->mainsubhead->getallBy($dataArray);
      if(count($getfeildlist)>0)
      {
        foreach ($getfeildlist as $getfeildlist)
        {
          echo '<li class="list_type">
                        <a href="javascript:void(0);" id="submain" rel="'.$getfeildlist->id.'">'.$getfeildlist->name.'</a>
                        <span class="edit_tist maketextable" id="text_edit1">Edit <sup><i class="fa fa-pencil"></i></sup></span>
                        
                      </li>';
          
        }
      }
    }
    elseif($request->feildfor=='subsubmain')
    {
      $dataArray = array('sub_heading_id'=>$request->id,'status'=>1);
      $getfeildlist = $this->subsubhead->getallBy($dataArray);
      if(count($getfeildlist)>0)
      {
        foreach ($getfeildlist as $getfeildlist)
        {
          echo '<li class="list_type">
                        <a href="javascript:void(0);" id="subsubmain" rel="'.$getfeildlist->id.'">'.$getfeildlist->name.'</a>
                        <span class="edit_tist maketextable" id="text_edit1">Edit <sup><i class="fa fa-pencil"></i></sup></span>
                       
                      </li>';
        }
      }
    }
  }
 }