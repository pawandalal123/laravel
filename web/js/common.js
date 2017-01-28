  $(document).ready(function()
  {
        $('body').on("click","#closemodel",function()
        {
            closemodel();
        });
        $('.countrychange').change(function()
        {
          var countryid = $(this).val();
          $.post(SITE_URL+'statelist',{'countryid':countryid},function(data,status)
          {
      

          }).fail(function(response) {
              //alert('Error: ' + response.responseText);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
              
          });

        })
        $('.catlistchange').change(function()
        {
           var catid=$(this).val();
          if($(this).val() != 0 || $(this).val() !='')
          {
           var catid = $(this).find('option:selected').attr('rel');
          }
          $.post(SITE_URL+'subcatlistajax',{'catid':catid},function(data,status)
          {
            $('.subcatlist').html(data);
            
          }).fail(function(response) {
              //alert('Error: ' + response.responseText);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
              
          });;

        });
        $('.articlesearch').click(function()
        {
          var catname = $('.catlistchange').val();
          if(catname=='')
          {
            alert('Please select category.');

          }
          else
          {
            var subatname = $('.subcatlist').val();
            var urlpram = catname+'/'+subatname;
            if(subatname=='')
            {
              urlpram = catname;
            }
            window.location.href=SITE_URL+'articles/'+urlpram.toLowerCase();

          }


        });

     });

		function loadsharebox(id)
		{
			var _token = $('input[name=_token]').val();
			$.post(SITE_URL+'loadsharebox',{'id':id,'_token':_token},function(data,status)
			{
				$('#modelcontent').html(data);
			});
		}
		function sharedocument(id)
		{
			 modelblock('Share the document','<div id="modelcontent"></div>','small','small'); 
       loadsharebox(id);
		}
    function connectmailbox()
    {
      modelblock('<h4>Connect Via Mail</h4>','<div id="modelcontent"></div>','small','small');
      var _token = $('input[name=_token]').val();
      $.post(SITE_URL+'connectmailbox',{'_token':_token},function(data,status)
      {
        $('#modelcontent').html(data);
      });

    }

    function loginbox()
    {
      modelblock('Login to continue','<div id="modelcontent"></div>','small','small');
        var _token = $('input[name=_token]').val();
        $.post(SITE_URL+'ajaxlogin',{'_token':_token},function(data,status)
            {
                $('#modelcontent').html(data);
            });
    }
    function savecomment(id,detailfor)
    {
      var message = $('textarea[name=comment]').val();
      var _token = $('input[name=_token]').val();
      // var detailfor =$(this).attr('rel');
      if(message=='')
      {
        swal({ title: "Error", text: "Please enter comment.", type: "error" });
      }
      else
      {
        $.getJSON(SITE_URL+'savedetails',{'id':id,'message':message,'detailfor':detailfor,'_token':_token},function(data,status)
        {
          alert(data.status);
          if(data.status=='success')
          {
            swal({ title: "Done", text: "Comment submit successfully.", type: "success" });

          }
          else
          {
            swal({ title: "Error", text: "Please try later.", type: "error" });

          }

        }).fail(function(response) {
            //alert('Error: ' + response.responseText);
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
            
        });

      }
    }
     function checkuserlogin(loginfor)
        {
            var email = $('input[name=user_email]').val();
            var password = $('input[name=password]').val();
            var _token = $('input[name=_token]').val();
            $.getJSON(SITE_URL+'ajaxauthnticate',{'email':email,'password':password,'_token':_token},function(data,status)
            {
                 $.each(data, function(index, element)
                 {
                   if(index=='error')
                   {
                     if(element.email)
                     {
                       $('input[name=user_email]').parent().addClass("has-error");
                       $(this).parent().append('<div>* This Field is required</div>');
                     }
                     if(element.password)
                     {
                       $('input[name=password]').parent().addClass("has-error");
                       $(this).parent().append('<div>* This Field is required</div>');
                     }
                   }
                   else if(index == 'loginsuccess')
                   {
                     if(element.auth==true)
                     {
                       var userId = element.user;
                       window.location.reload();
                     }
                     else
                     {
                        $(':input').val('');
                        $('.modal-header').append('<div class="alert alert-warning fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid Login Details..</div>');
                       return false;
                     }
                   }
                });
            });
        }
    

    function submitmailbox()
    {
       var email = $('input[name=email]').val();
       var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
       var valid = emailRegex.test(email);
       if(!valid)
       {
        alert('please enter valid email');
        return false;

       }
    }

    function deletedocumnet(id)
    {
      swal({   title: "Are you sure you want to delete?",   text: "",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){  
       
     
       $.post(SITE_URL+'deletedocument',{'documentid':id},function(data,status)
        {
            if(data==1)
            {
              swal({ title: "Done", text: "Delete Successfully", type: "success" });
              setTimeout(function(){closemodel()},2000);

            }
            else
            {
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
              setTimeout(function(){closemodel()},2000);

            }
        }).fail(function(response) {
            //alert('Error: ' + response.responseText);
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
            setTimeout(function(){closemodel()},2000);
        });
         });

    }

    function submitsharebox(id)
    {
       var email = $('input[name=email]').val();
       var name = $('input[name=name]').val();
       var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
       var valid = emailRegex.test(email);
       if(!valid)
       {
        alert('please enter valid email');
        return false;

       }
       if(name=='')
       {
        alert('please enter name');
        return false;

       }
       else
       {
          $.post(SITE_URL+'sharedocument',{'name':name,'email':email,'documentid':id},function(data,status)
          {
              if(data==1)
              {
                swal({ title: "Done", text: "Share Successfully", type: "success" });
                closemodel();
                setTimeout(function(){closemodel()},2000);

              }
              else
              {
                swal({ title: "Error", text: "Some Technical Issue", type: "error" });
                closemodel();
                setTimeout(function(){closemodel()},2000);

              }
          }).fail(function(response) {
              //alert('Error: ' + response.responseText);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
                //setTimeout(function(){closemodel()},2000);
          });
        }
         

    }

     

        function modelblock(header,body,size)
        {
            closemodel();
            if(typeof(footer) == 'undefined'){
                footer = '';
            }
            if(typeof(size) == 'undefined'){
                size = 'small';
            }
           // $('#page_hide').show();
            var template = '<div class="modelbox"><div id="myModal" class="modal">'+
                '<div class="modal-dialog '+size+'">'+
                    '<div class="modal-header">'+
                    '<button type="button" class="close" id="closemodel" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                        '<h4>'+header+'</h4>'+
                    '</div>'+
                    '<div class="content">'+body+'</div>'+
                '</div>'+
            '</div></div>';
            $('body').append(template);
             //$('#page_hide').hide();

        }

        function closemodel()
        {
            $('#myModal').remove();
        }
       
		function myReplace(str, a, b) 
		{  
			var re = new RegExp(a, "g");
            var ret = str.replace(re,b);
            return ret.toLowerCase();
     }


function getsocalgplus (uri)
    {
      var count=0;
      //var uri = window.location.href;
      $.ajax({
              async:false,
              type: 'POST',
              url: 'https://clients6.google.com/rpc',
              processData: true,
              contentType: 'application/json',
              data: JSON.stringify({
                'method': 'pos.plusones.get',
                'id': uri,
                'params': {
                  'nolog': true,
                  'id': uri,
                  'source': 'widget',
                  'userId': '@viewer',
                  'groupId': '@self'
                },
                'jsonrpc': '2.0',
                'key': 'p',
                'apiVersion': 'v1'
              }),
              success: function(response) {
                //alert(response.result.metadata.globalCounts.count);
                count=response.result.metadata.globalCounts.count;
                var getcount = $('.getsocial').text();
                $('.getsocial').text(parseInt(getcount)+parseInt(count));
                  
              },
        error: function (response) {
          return 0;
            
        }
    });
    }
    function getsocalpintrest(url)
    {
      var count=0;
      //var url = window.location.href;
      $.ajax({
      type: "GET",
      async:false,
      timeout:5000,
      url: 'https://api.pinterest.com/v1/urls/count.json?callback=?&url='+encodeURIComponent(url),
      dataType: 'json',
      success: function (data) {

            //result = data[0];
            //alert(data.count);
            if(data.count)
            {
              count =parseInt(data.count);
              var getcount = $('.getsocial').text();
              $('.getsocial').text(parseInt(getcount)+count);
            }
      },
        error: function (data) {
          return 0;
            
        }
    });
    }
    function getsocalfbcount(url)
    {
      var count=0;
      //var url = window.location.href;
      $.ajax({
      type: "GET",
      async:false,
      url: 'https://graph.facebook.com/?id='+encodeURI(url),
      timeout:5000,
      dataType: 'json',
      success: function (data) {
            result = data[0];
            if(data.share)
            {
              count =parseInt(data.share.share_count);
              var getcount = $('.getsocial').text();
              $('.getsocial').text(parseInt(getcount)+parseInt(count));
            }
      },
        error: function (data) {
          return 0;
            
        }
    });
    }
    function getsocaltwit(url)
    {
      $.ajax({
      type: "GET",
      url: 'http://cdn.api.twitter.com/1/urls/count.json?url='+encodeURI(url),
      async:false,
      timeout:5000,
      dataType: 'json',
      success: function (data) {
            result = data[0];
            if(data.count)
            {
              return data.count;
            }
      },
        error: function (data) {
          return 0;
            
        }
    });
    }

  