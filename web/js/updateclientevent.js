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
        var eventid = "{!! $eventdisplaydetails->eventid !!}";
        var action = 'EventName';
        var name = $(this).val();
        if(name=='')
        {
          alert('Please enter value');
        }
        else
        {
            $('.namebox').text('');
            $.post(SITE_URL+'updateclientEvent',{'eventid':eventid,'actionfor':action,'name':name},function(data)
            {
             
                if(data==1)
                {
                  $(".namebox").attr('rel', name);
                  $('.namebox').html('<h1 class="eventname">'+name+'</h1>');
                  //swal({ title: "Done", text: "Successfully Created", type: "success" });
                }
                else
                {
                  var namevalue= $('.namebox').attr('rel');
                 $('.namebox').html('<h1 class="eventname">'+name+'</h1>');
                  swal({ title: "Oops...", text: "Please try later", type: "error" });

                }
            }).fail(function(response) {
              //alert('Error: ' + response.responseText);
               var namevalue = $('.namebox').attr('rel');
               $('.namebox').html('<h1 class="eventname">'+name+'</h1>');
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
          });
        }
    });


    $(document).on('click','.event_startDate_btn',function()
    {
      var t = $('.event_startDate').attr('rel');
      $('.event_startDate').text('').append($('<input  />',{'value' : t,class:'ddcalendar'}));
      $('input').focus();
    });
     $(document).on('click','.btnediturl',function()
    {
      var t = $('.editurl').attr('rel');
      $('.editboxurl span:first-child').addClass('preurl');
      $('.editurl').text('').append($('<input  />',{'value' : t,class:'ddcalendar'}));
      $('input').focus();
    });

    $('.event_startDate').on('blur','input',function()
    {
        //lert($(this).val());
        var eventid = "{{ $eventdisplaydetails->eventid }}";
        var action = 'EventStartDate';
        var name = $(this).val();
        if(name=='')
        {
          alert('Please enter value');
        }
        else
        {
            $('.event_startDate').text('');
            $.post(SITE_URL+'updateclientEvent',{'eventid':eventid,'actionfor':action,'name':name},function(data)
            {
             
               if(data!="error" && data!=2)
                {

                  $(".event_startDate").attr('rel', name);
                  $('.event_startDate').html(data+'({{$eventdisplaydetails->timezonename}})');
                  //swal({ title: "Done", text: "Successfully Created", type: "success" });
                }
                else
                {
                  var namevalue= $('.event_startDate').attr('title');
                  $('.event_startDate').html(namevalue);

                 if(data==2)
                   swal({ title: "Event start date should be less than event end date.", text: "Event start date should be less than event end date", type: "error" });
                 else
                  swal({ title: "Oops...", text: "Please try later", type: "error" });

                }
            }).fail(function(response) {
              //alert('Error: ' + response.responseText);
               var namevalue = $('.event_startDate').attr('title');
               $('.event_startDate').html(namevalue);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
          });
        }
    });


    $(document).on('click','.event_endDate_btn',function()
    {
      var t = $('.event_endDate').attr('rel');
      $('.event_endDate').text('').append($('<input  />',{'value' : t,class:'ddcalendar'}));
      $('input').focus();
    });
     $(document).on('click','.btnediturl',function()
    {
      var t = $('.editurl').attr('rel');
      $('.editboxurl span:first-child').addClass('preurl');
      $('.editurl').text('').append($('<input  />',{'value' : t,class:'ddcalendar'}));
      $('input').focus();
    });

    $('.event_endDate').on('blur','input',function()
    {
        //lert($(this).val());
        var eventid = "{!! $eventdisplaydetails->eventid !!}";
        var action = 'EventEndDate';
        var name = $(this).val();
        if(name=='')
        {
          alert('Please enter value');
        }
        else
        {
            $('.event_endDate').text('');
            $.post(SITE_URL+'updateclientEvent',{'eventid':eventid,'actionfor':action,'name':name},function(data)
            {
             
                if(data!="error" && data!=2)
                {


                  $(".event_endDate").attr('rel', name);
                  $('.event_endDate').html(data+'({{$eventdisplaydetails->timezonename}})');
                  //swal({ title: "Done", text: "Successfully Created", type: "success" });
                }
                else
                {
                  var namevalue= $('.event_endDate').attr('title');
                 $('.event_endDate').html(namevalue);

                 if(data==2)
                   swal({ title: "Event end date should be greater than event start date.", text: "Please try later", type: "error" });
                  
                 else
                  swal({ title: "Oops...", text: "Please try later", type: "error" });
 
                }
            }).fail(function(response) {
              //alert('Error: ' + response.responseText);
               var namevalue = $('.event_endDate').attr('title');
               $('.event_endDate').html(name);
              swal({ title: "Error", text: "Some Technical Issue", type: "error" });
          });
        }
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
        var id = "{!! $eventdisplaydetails->eventid !!}";
        var updatefor = $(this).attr('updatefor');
        
         $('.logoloader').show();      
             
              //form_data.append($(this).prop("name"), $(this)[0].files[0]);
        form_data.append('fileField',fileField);
        form_data.append('updateid',id);
        form_data.append('updatefor',updatefor);
        $.ajax({
               url: SITE_URL+"updateclientEvent",
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
           
             swal({ title: "Error", text: "Invalid File", type: "error" });
            
          }
          else
          {
            
            $('.logoloader').hide();
            $("#event_banner").attr('src',data);
            
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
});

$(document).on("mouseover",".ddcalendar", function(e){ 
        $('.ddcalendar').datetimepicker({
          yearOffset:0,
          lang:'en',
          timepicker:true,
          format:'Y-m-d H:i',
          formatDate:'Y-m-d H:i',
          minDate:0,
          step:15
          
          //minDate:'-1970/01/2',
           // maxDate:'+1970/01/2'
          //minDate:'-1970-01-02', // yesterday is minimum date
          //maxDate:'+2025-01-02' // and tommorow is maximum date calendar
        });
      });



function genratedescform(updateid,updatefor,tabname)
  {
    if(tabname)
    {
      obj={};
      obj['updateid']=updateid;
      obj['updatefor']=updatefor;
      obj['tabname']=tabname;
      $('#page_hide').show();
      $.post(SITE_URL+'eventeditabletabforms',obj,function(data)
          {
            $('#page_hide').hide();
              if(data=='error')
              {
                swal({ title: "Oops...", text: "Please try later", type: "error" });
              }
              else
              {
                $('#descrip').html(data);
              }
           }).fail(function(response) {
            $('#page_hide').hide();
            //alert('Error: ' + response.responseText);
            swal({ title: "Error", text: "Some Technical Issue", type: "error" });
          });
    }
    else
    {
      swal({ title: "Error", text: "Some Technical Issue", type: "error" });
    }
  }

  function updateorgevent(tabname,updateid,updatefor,urltoredirect,actionname)
  {
      obj={};
    obj['updateid']=updateid;
    obj['updatefor']=updatefor;
    obj['tabid']=tabname;
    obj['urltoredirect']=urltoredirect;
    obj['tabname']=actionname;
    obj['tabcontent']=CKEDITOR.instances['editor1'].getData();
        $('.updatetab').text('Processing').attr("disabled", 'disabled');
        if($('input[name=tabname]').val()=='')
        {
          $('input[name=tabname]').focus();
        }
        else
        {
          $.getJSON(SITE_URL+'updateclientEvent',obj,function(data)
          {
             if(data.status=='error')
                {
                  swal({ title: "Oops...", text: "Please try later", type: "error" });
                  $('.updatetab').text('Submit').prop("disabled", false);
                }
                else if(data.status=='sametab')
                {
                  swal({ title: "Oops...", text: "Please Enter Diffrent Tab Name", type: "error" });
                   $('.updatetab').text('Submit').prop("disabled", false);
                }
                else if(data.status=='tabname')
                {
                  swal({ title: "Oops...", text: "Please Enter Tab Name", type: "error" });
                   $('.updatetab').text('Submit').prop("disabled", false);
                }
                else if(data.status=='success')
                {
                  swal({ title: "Successfully Updated",  type: "success" });
                  window.location.href=data.returnurl;
                }
          });
        }
        
  }