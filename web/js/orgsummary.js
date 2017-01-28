$(document).ready(function()
{


    $(document).on('click','.btnedit',function()
    {
      var t = $('.namebox').attr('rel');
      $('.namebox').text('').append($('<input  />',{'value' : t}));
      $('input').focus();
    });
     $(document).on('click','.btnediturl',function()
    {
      var t = $('.editurl').attr('rel');
      $('.editboxurl span:first-child').addClass('preurl');
      $('.editurl').text('').append($('<input  />',{'value' : t}));
      $('input').focus();
    });
    $('.namebox').on('blur','input',function()
    {
        //lert($(this).val());
        var updateid = $('input[name=updateid]').val();
        var updatefor = 'orgname';
        var name = $(this).val();
        if(name=='')
        {
          alert('Please enter value');
        }
        else
        {
          //$('.namebox').text('');
          $.post(SITE_URL+'updateorgprofiles',{'updateid':updateid,'updatefor':updatefor,'name':name},function(data)
          {
              if(data==1)
              {
                $(".namebox").attr('rel', name);
                $('.namebox').text(name.ucwords());
                //swal({ title: "Done", text: "Successfully Created", type: "success" });
              }
              else
              {
                var namevalue= $('.namebox').attr('rel');
                $('.namebox').text(namevalue.ucwords());
                swal({ title: "Oops...", text: "Please try later", type: "error" });

              }
          }).fail(function(response) {
            //alert('Error: ' + response.responseText);
             var namevalue = $('.namebox').attr('rel');
             $('.namebox').text(namevalue.ucwords());
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
        });
        }
    });
  $('.editurl').on('blur','input',function()
    {
        //lert($(this).val());
        $('.editboxurl span:first-child').removeClass('preurl');
        var updateid = $('input[name=updateid]').val();
        var updatefor = 'orgurl';
        var name = $(this).val();
        var regex = new RegExp("^[a-zA-Z0-9 -_]+$");
        if(name=='')
        {
          alert('Please enter value');
        }
        else if(!regex.test(name))
        {
          alert('Please enter valid url');
           // return false;
        }
        else
        {
          
          $.getJSON(SITE_URL+'updateorgprofiles',{'updateid':updateid,'updatefor':updatefor,'name':name},function(data)
          {
              if(data.status=='success')
              {
                $(".editurl").attr('rel', data.url);
                $('.editurl').text(data.url);
                //swal({ title: "Done", text: "Successfully Created", type: "success" });
              }
              else if(data.status=='exist')
              {
                var namevalue= $('.editurl').attr('rel');
                $('.editurl').text(namevalue);
                swal({ title: "Oops...", text: "Url already taken, please try another.", type: "error" });

              }
              else
              {
                var namevalue= $('.editurl').attr('rel');
                $('.editurl').text(namevalue);
                swal({ title: "Oops...", text: "Please try later", type: "error" });

              }
          }).fail(function(response) {
            //alert('Error: ' + response.responseText);
             var namevalue = $('.editurl').attr('rel');
             $('.editurl').text(namevalue);
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
        });
        }
    });
 $('.usermode').click(function()
  {

      var updateid = $('input[name=updateid]').val();
      var updatefor = 'orgusermode';
      var statusvalue = $(this).attr('id');
      $.getJSON(SITE_URL+'updateorgprofiles',{'updateid':updateid,'updatefor':updatefor,'statusvalue':statusvalue},function(data)
      {
          if(data.status=='success')
          {
            swal({ title: "Done", text: "Status Changed", type: "success" });
          }
          else
          {
            swal({ title: "Oops...", text: "Please try later", type: "error" });
          }
      }).fail(function(response) {
     
        swal({ title: "Error", text: "Some Technical Issue", type: "error" });
    });
});

  $(document).on('click','.updatesocila',function()
  {
      var obj = {};
      obj['updateid']=$(this).attr('id');
      obj['updatefor']=$(this).attr('rel');
      var inputData = $('#socialform').serializeArray();
        var error=0;
        $.each(inputData, function(i,field)
        {
         var name = field.name, value = field.value;
         if(value!='')
         {
          var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
          if (!re.test(value))
          { 
             if($('input[name='+name+']').hasClass("has-error"))
             {

             }
             else
             {
              $('input[name='+name+']').addClass("has-error")
              $('input[name='+name+']').parent().parent().append('<div class="validatemessage" style="color:red">* Please Enter valid Url.</div>');
             }
              
              error++;
          }
          else
          {
            $('input[name='+name+']').removeClass("has-error");
            $('input[name='+name+']').parent().parent().find('.validatemessage').hide();
          }

         }
         obj[name] = obj[name] === undefined ? value: $.isArray( obj[name] ) ? obj[name].concat( value ) : [ obj[name], value ];
        });
        if(error==0)
        {
          $.post(SITE_URL+'updateorgprofiles',obj,function(data)
            {
                if(data==1)
                {
                  swal({ title: "Successfully Updated",  type: "success" });
                }
                else
                {
                  swal({ title: "Oops...", text: "Please try later", type: "error" });
                  
                }
                closemodel();
            }).fail(function(response) {
              //alert('Error: ' + response.responseText);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
              closemodel();
          });
        }
       //console.log(obj);

   });


   
$(document).on('change','#getimage2',function()
  {
     var fileField  = this.files[0];
     var name = fileField.name;
     var size = fileField.size;
     // var regex = new RegExp("(.*?)\.(PNG|png|jpg|jpeg|gif|svg)$");
     // if(!(regex.test(val))) 
     // {
     //   $(this).val('');
     //   alert('Please select correct file format');
     // }
      var formerr = 0;
      var fileExt =  name.split('.').pop().toLowerCase();
      if($.inArray(fileExt, ['gif','png','jpg','jpeg','svg','PNG','GIF']) == -1)
      {
          alert('invalid file !');
          formerr++;
          return false;
      }
     else
     {
        var form_data = new FormData();
        var id = $('input[name=updateid]').val();
        var updatefor = $(this).attr('updatefor');
        if(updatefor=='orglogo')
        {
          $('.logoloader').show();
        }
        else
        {
          $('.bannerloader').show();
        }
        
             
              //form_data.append($(this).prop("name"), $(this)[0].files[0]);
               form_data.append('fileField',fileField);
               form_data.append('updateid',id);
               form_data.append('updatefor',updatefor);
      $.ajax({
               url: SITE_URL+"updateorgprofiles",
         type: "POST",
         data:  form_data,
         cache: false,
             contentType: false,
             processData: false,
       success: function(data)
          {
          if(data=='invalid file')
          {
            $('.logoloader').hide();
            $('.bannerloader').hide();
             swal({ title: "Error", text: "Invalid File", type: "error" });
            
          }
          else
          {
            
           if(updatefor=='orglogo')
            {
              $('.logoloader').hide();
              $('.orgLogo').css('background-image', 'url('+data+')');

            }
            else
            {
              $('.bannerloader').hide();
              $('.bannerimage').html('<img src='+data+'>');
            }
          }
          },
         error: function() 
          {
             $('.logoloader').hide();
            $('.bannerloader').hide();
             swal({ title: "Error", text: "Some Technical Issue", type: "error" });
          }          
        });
     }
  });
  $(document).on('click','.uplodimage',function()
  {

     var fileField = $('#imagegalleryInputFile')[0].files[0];
     var filetext = $('textarea#message').val();
     if(typeof fileField !== typeof undefined )
     {
         var name = fileField.name;
         var size = fileField.size;
         var fileExt =  name.split('.').pop().toLowerCase();
         var formerr = 0;
          if($.inArray(fileExt, ['gif','png','jpg','jpeg','svg','PNG','GIF']) == -1)
          {
              alert('invalid file !');
              formerr++;
              return false;
          }
         else
         {

          $('#page_hide').show();
            var form_data = new FormData();
            var id = $('input[name=updateid]').val();
            var updatefor = 'orgimagegal';
                  //form_data.append($(this).prop("name"), $(this)[0].files[0]);
                   form_data.append('fileField',fileField);
                   form_data.append('updateid',id);
                   form_data.append('updatefor',updatefor);
                   form_data.append('filetext',filetext);
          $.ajax({
                   url: SITE_URL+"updateorgprofiles",
             type: "POST",
             data:  form_data,
             cache: false,
                 contentType: false,
                 processData: false,
           success: function(data)
              {
                $('#page_hide').hide();
              if(data=='invalid file')
              {
               
                 swal({ title: "Error", text: "Invalid File", type: "error" });
              }
              else
              {
                // var lsatDiv=$('.justified-gallery:last a');
                 $('.mediagallery').html(data);
                 //lsatDiv.after(data);
              }
              },
             error: function() 
              {
               $('#page_hide').hide();
                 swal({ title: "Error", text: "Some Technical Issue", type: "error" });
              }          
            });
         }
       }
       else
       {
         swal({ title: "Error", text: "Select file.", type: "error" });
       }
  });


$(document).on('click','.savevideoorg',function()
{
   var obj={};
   obj['updateid']=$('input[name=updateid]').val();
   obj['updatefor']=$(this).attr('updatefor');
   var urltype = $('input[name=Videotype]:checked').val();
   var videourl = $('input[name=youtube]').val();
   obj['urltype']=urltype;
   obj['videourl']=videourl;
   if(videourl=='')
   {
     $('input[name=youtube]').focus();
     swal({ title: "Error", text: "Please enter valid url", type: "error" });
     return false;
   }
   else
   {
      $('#page_hide').show();
      $.getJSON(SITE_URL+'updateorgprofiles',obj,function(data,status)
        {
          $('input[name=youtube]').val('');
          $('#page_hide').hide();
          if(data.status=='success')
          {
            var lsatDiv=$('.videolistdiv:last');
            $('.text-center').hide();
            lsatDiv.after(data.datadisplay);

          }
          else if(data.status=='notvalid')
          {
            swal({ title: "Error", text: "Please enter valid url", type: "error" });

          }
          else
          {
            swal({ title: "Error", text: "Please try later", type: "error" });

          }
        }).fail(function(response) {
           $('#page_hide').hide();
                swal({ title: "Error", text: "Some Technical Issue", type: "error" });
            });
  }

 });
$(document).on('click','.radioVideo',function(){
  
  var ddUrl = $("#youtubeurl").attr('placeholder');
  if(ddUrl =="https://www.youtube.com/embed/fvsC4x4VBpM")
    $('#youtubeurl').attr('placeholder','https://www.facebook.com/veigasevents/videos/1056214481122956/');
  else
    $('#youtubeurl').attr('placeholder','https://www.youtube.com/embed/fvsC4x4VBpM');
});
$('.deleteusergal').click(function()
{
  var obj={};
  var galid = $(this).attr('id');
   obj['updateid']=$('input[name=updateid]').val();
   obj['galid']=galid;
   if(confirm('Are you sure you want to delete?'))
   {
    $('#page_hide').show();
     $.getJSON(SITE_URL+'deletusergallery',obj,function(data,status)
        {
          $('#page_hide').hide();
          if(data.status=='success')
          {
            $('.gallery'+galid).remove();

          }
          else if(data.status=='notvalid')
          {
            swal({ title: "Error", text: "Please enter valid url", type: "error" });

          }
          else
          {
            swal({ title: "Error", text: "Please try later", type: "error" });

          }
        }).fail(function(response) {
           $('#page_hide').hide();
                swal({ title: "Error", text: "Some Technical Issue", type: "error" });
            });
      }
       return false;
});
});

String.prototype.ucwords = function() {
  str = this.toLowerCase();
  return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
    function(s){
      return s.toUpperCase();
  });
};
$(function()
  {
    $(window).ready(function()
    {

      var url = window.location.href;
      var parmetrs =url.split('/');
      var geteventpram = parmetrs[1].split('/');
      newUrl=parmetrs[0]+'/'+geteventpram[0];
      var totalcount = 0;
      getsocalgplus(url);
      getsocalpintrest(url);
      getsocalfbcount(url);
      var updateid = $('input[name=updateid]').val();
      eventviews(updateid,'user_profile');
    });
  });

  function getsocialbox(updateid,updatefor)
  {
    modelblock('Update Social Links','<div id="modelcontent"></div>','small','small');
    $.post(SITE_URL+'getsocialbox',{'updateid':updateid,'updatefor':updatefor},function(data,status)
    {
      $('#modelcontent').html(data);
    }).fail(function(response) {
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
        });;  
    
  }