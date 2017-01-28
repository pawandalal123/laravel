
        $(document).ready(function()
        {
            //setInterval(function(){ remainingTime() },1000);
            var count = 0;
            var timer = $.timer(
            function(){
            var remainingTime = $("#totalTime").val();
            var m=Math.floor(remainingTime/60);
            var s=remainingTime%60;
            if(remainingTime <= 0)
            {   
                timer.pause();
                alert('Your time is over');
                var eventname = $('input[name=eventname]').val();
                var eventid = $('input[name=eventid]').val();
				var orderid = $('input[name=orderid]').val();
				var _token = $('input[name=_token]').val();
				$.post(SITE_URL+'updateholdtime',{'orderid':orderid,'_token':_token},function(data,status)
				{
					if(status=='success')
					{
						if(data==1)
						{
							window.location.href=SITE_URL+'event/'+eventname+'/'+eventid+'?error=true';
						}
					}
				})
            }
            else
            {
                remainingTime = remainingTime-1;
                $('.min').text(m);
                $('.sec').text(s);
                $("#totalTime").val(remainingTime);
            }
            },
            1000,
            true
        );  
            $('.hidelocation').hide();
            $(".group1").colorbox({rel:'group1'});
            $('#time,#timeend').datetimepicker({
                datepicker:false,
                format:'H:i',
                step:5
            });

            $('#datetimepicker2,#datetimepickerend').datetimepicker({
               // yearOffset:0,
                lang:'en',
                timepicker:false,
                format:'Y-m-d',
                formatDate:'Y-m-d',
                //minDate:'1940/01/02' // yesterday is minimum date  
            });

            $(document).on('change','select[name=eventcategory]',function()
            {
                var category_id = $(this).val();
                var _token = $('input[name=_token]').val();
                $.post(SITE_URL+'subcategorylist',{'category_id':category_id,'_token':_token},function(data,status)
                {
                     $('select[name=eventsubcategory]').html('');
                     $('select[name=eventsubcategory]').html(data);
                });
            });
            $(document).on('change','#geocomplete',function()
            {
                var location = $(this).val();
                if(location!='')
                {
                    $('.hidelocation').show();
                }
            });

            $('body').on("click","#closemodel",function()
            {
                closemodel();
            });
            $('#addressNext').click(function()
            {
                var _token = $('input[name=_token]').val();
                var orderid = $('input[name=orderid]').val();
                var firstname =$('input[name=userfname]').val() ;
                var lastname = $('input[name=userlname]').val() ;
                var email = $('input[name=useremail]').val() ;
                var address = $('input[name=address]').val() ;
                var city = $('input[name=city]').val() ;
                var state = $('input[name=state]').val() ;
                var country = $('input[name=country]').val() ;
                var Zipcode = $('input[name=zipcode]').val() ;
                var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
               // var phonemsg=new RegExp(/^(0-?)?(\([0-9]\d{2}\)|[0-9]\d{2})-?[0-9]\d{2}-?\d{4}$/i);
              // var mobvalid=phonemsg.test(mobile)
               var valid = emailRegex.test(email);
                if(firstname=='')
                {
                    alert('Please Enter first Name');
                    return false;
                }
                if(lastname=='')
                {
                    alert('Please Enter first lastname');
                    return false;
                }
                if(!valid)
                {
                    alert('Please Enter valid email address');
                    return false;
                }
                if(address=='')
                {
                    alert('Please Enter first address');
                    return false;
                }
                if(city=='')
                {
                    alert('Please Enter first city');
                    return false;
                }
                if(state=='')
                {
                    alert('Please Enter first state');
                    return false;
                }
                if(country=='')
                {
                    alert('Please Enter first country');
                    return false;
                }
                else
                {
                    var buttontext = $(this).text();
                    $.post(SITE_URL+'savedetailandnext',{'orderid':orderid,'_token':_token,'firstname':firstname,'lastname':lastname,'email':email,'address':address,'city':city,'state':state,'country':country,'Zipcode':Zipcode},function(data,status)
                    {
                        if(buttontext =='Next')
                        {
                            $('.content_b1').hide();
                            $('.content_b2').show();
                        }
                        else
                        {
                            alert('alert');
                        }
                    });
                }
            });
            
            $('#NextStep3').click(function()
            {
                var _token = $('input[name=_token]').val();
                var orderid = $('input[name=orderid]').val();
				var err = 0;
				//var inputData = [];
				$('.inputData').each(function(){
					var queryString = [];
					if($(this).hasClass("inputRequired") && $(this).val()==''){
							$(this).addClass("requiredVal");
							err++;
					}
					/*var inputName = $(this).attr("rel");
					var ticketID = $(this).attr("ticket");
					var inputDynamicName = inputName+"["+ticketID+"][]";
					var inputValue = $(this).val();
					alert(inputDynamicName)
					queryString = { inputDynamicName : inputValue};
					inputData.push(queryString);*/
				});
				var inputData = $('.content_b2 :input').serialize();
				var buttontext = $(this).text();
				if(err==0)
				{
					$.ajax({
						  type: "POST",
						  url: SITE_URL+'saveticketfeildata',
						  data: inputData+"&orderid="+orderid+"&_token="+_token+"&buttontext="+buttontext,
						  dataType: 'html'
						}).done(function(data)
						{
							alert(buttontext);
							alert(data);
							if(buttontext=='Submit')
							{
								alert('successfully order complete');
								window.location.reload();
							}
							else
							{
								alert('Payment Get way');
							}
						});
				}
            });

            $('#completeorder').click(function()
            {
				var _token = $('input[name=_token]').val();
                var firstname =$('input[name=userfname]').val() ;
				var orderid = $('input[name=orderid]').val();
                var lastname = $('input[name=userlname]').val() ;
                var email = $('input[name=useremail]').val() ;
                var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
                var valid = emailRegex.test(email);
                if(firstname=='')
                {
                    alert('Please Enter first Name');
                    return false;
                }
                if(lastname=='')
                {
                    alert('Please Enter first lastname');
                    return false;
                }
                if(!valid)
                {
                    alert('Please Enter valid email address');
                    return false;
                }
				else
				{
					var buttontext = $(this).text();
					 $.post(SITE_URL+'savedetailandorder',{'orderid':orderid,'_token':_token,'firstname':firstname,'lastname':lastname,'email':email,'buttontext':buttontext},function(data,status)
                    {
						if(status=='success')
						{
							if(buttontext =='Next')
							{
								$('.content_b1').hide();
                                $('.content_b2').show();
							}
							else
							{
								alert('successfully order complete');
								window.location.reload();
							}
						}
					});
				}

            });
        });
        function disablelocation()
        {
            $('#geocomplete').attr('disabled', true);
            $('#geocomplete').val('onlineEvent');
        }
        function makesearUrl(value,searchfor)
        {
            var urlpram='';
            if(searchfor=='keyword')
            {
               var location = $('input[name=headerlocation]').val();
               urlpram=location+'/'+value;

            }
            if(searchfor=='city')
            {
                 var keyword = $('input[name=keyword]').val();
                 urlpram=value+'/'+keyword;
            }
            
            window.location.href=SITE_URL+'search/'+urlpram;
        }
        function loginbox(loginfor,id)
        {
            var _token = $('input[name=_token]').val();
            $.post(SITE_URL+'ajaxlogin',{'loginfor':loginfor,'id':id,'_token':_token},function(data,status)
                {
                    $('#modelcontent').html(data);
                });
        }
		function messagebox(userid)
		{
				var _token = $('input[name=_token]').val();
				$.post(SITE_URL+'messagebox',{'id':userid,'_token':_token},function(data,status)
				{
					$('#modelcontent').html(data);
				});	
		}
		function setreminderbox(eventid)
		{
				var _token = $('input[name=_token]').val();
				$.post(SITE_URL+'setreminderbox',{'id':eventid,'_token':_token},function(data,status)
					{
						$('#modelcontent').html(data);
					});	
		}
		
		function reportincorrecthandler(eventid)
		{
			var _token = $('input[name=_token]').val();
			$.post(SITE_URL+'reportincorrecthandler',{'eventid':eventid,'_token':_token},function(data,status)
			{
				$('#modelcontent').html(data);
			});	
		}
        function freeticketCheckoutlogin(buytickets)
        {
             modelblock('For checkout login first','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small'); 
            loginbox('buytickets');
        }
        function followwithlogin(organiserid)
        {
            modelblock('Follow the orgeniser','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small'); 
            loginbox('followorganiser',organiserid);
        }
		function favouritewithlogin(eventid)
        {
            modelblock('Follow the orgeniser','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small'); 
            loginbox('markfavourite',eventid);
        }
		function makeview(viewfor,id)
		{
			var _token = $('input[name=_token]').val();
			$.post(SITE_URL+'viewconter',{'id':id,'viewfor':viewfor,'_token':_token},function(data,status)
			{
			});
		}
		function loadfriendbox(id)
		{
			var _token = $('input[name=_token]').val();
			$.post(SITE_URL+'loadfriendbox',{'id':id,'_token':_token},function(data,status)
			{
				$('#modelcontent').html(data);
			});
		}
		function forwardtofriend(id)
		{
			 modelblock('Forward the event detail to friend','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small'); 
            loadfriendbox(id);
		}
        function showformfor(formfor)
        {
            if(formfor=='register')
            {
                $('.registerform').show();
                $('.loginform').hide();
            }
            if(formfor=='login')
            {
                $('.loginform').show();
                $('.registerform').hide();
            }
        }
		function setreminderevent(eventid)
		{
			
			var name = $('input[name=name]').val();
			var email = $('input[name=user_email]').val();
            var days = $('select[name=days]').select().val();
			var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
            var valid = emailRegex.test(email);
			var _token = $('input[name=_token]').val();
			if(name=='')
			{
				 $('input[name=name]').addClass("requiredVal");
			}
		    if(!valid)
			{
				$('input[name=user_email]').addClass("requiredVal");
				return false;
			}
			if($('input[name=tillend]:checked').length>0)
			{
				$.post(SITE_URL+'setreminderevent',{'id':eventid,'name':name,'email':email,'days':days,'_token':_token},function(data,status)
				{
					if(status=='success')
					{
						$(':input').val('');
						
						if(data==1)
						{
							$('.header').append('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your Reminder set successfully</div>');
							setTimeout(function(){closemodel()},2000);
							
						}
						
					}
				});
			}
			else
			{
				alert('Please selct daily reminder');
			
			}
		
		}
		function submitforwardbox(eventid)
		{
			var name = $('input[name=name]').val();
			var email = $('input[name=user_email]').val();
            var message = $('input[name=message]').val();
			var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
            var valid = emailRegex.test(email);
			var _token = $('input[name=_token]').val();
			if(name=='')
			{
				 $('input[name=name]').addClass("requiredVal");
			}
		    if(!valid)
			{
				$('input[name=user_email]').addClass("requiredVal");
				return false;
			}
		    if(message=='')
			{
				$('input[name=message]').addClass("requiredVal");
			}
			else
			{
				$.post(SITE_URL+'saveforwardform',{'eventid':eventid,'name':name,'email':email,'message':message,'_token':_token},function(data,status)
				{
					if(status=='success')
					{
						$(':input').val('');
						
						if(data==1)
						{
							$('.header').append('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your message sent successfully</div>');
							
						}
						
					}
				});
			
			}
		}
		function submitmessagebox(userid)
		{
			var name = $('input[name=name]').val();
			var email = $('input[name=user_email]').val();
			var subject = $('input[name=subject]').val();
            var message = $('input[name=message]').val();
			var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
            var valid = emailRegex.test(email);
			var _token = $('input[name=_token]').val();
			if(name=='')
			{
				 $('input[name=name]').addClass("requiredVal");
			}
		    if(!valid)
			{
				$('input[name=user_email]').addClass("requiredVal");
				return false;
			}
			if(subject=='')
			{
				 $('input[name=subject]').addClass("requiredVal");
			}
		    if(message=='')
			{
				$('input[name=message]').addClass("requiredVal");
			}
			else
			{
				$.post(SITE_URL+'saveusercontectform',{'userid':userid,'name':name,'email':email,'subject':subject,'message':message,'_token':_token},function(data,status)
				{
					
					if(status=='success')
					{
						$(':input').val('');
						
						if(data==1)
						{
							$('.header').append('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your message sent successfully</div>');
							
						}
						
					}
				});
			}
		}
		function submitincorrect(eventid)
		{
			var email = $('input[name=user_email]').val();
            var message = $('input[name=message]').val();
			var emailRegex = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/);
            var valid = emailRegex.test(email);
			var _token = $('input[name=_token]').val();
			if($('#error_report_phone:checked').length>0)
			{
				var checkedvalue = [];
				$('#error_report_phone:checked').each(function()
				{
					checkedvalue.push($(this).val());
				});
				$.post(SITE_URL+'handelincorrect',{'eventid':eventid,'repotvalue':checkedvalue,'email':email,'message':message,'_token':_token},function(data,status)
			    {
					if(data==1)
					{
						$('#modelcontent').html('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Thanks You, we revert you back soon..</div>');
						setTimeout(function(){closemodel()},2000);
					}
				});
			}
			else
			{
				alert('Please slect one option');
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
							 $('input[name=user_email]').addClass("requiredVal");
						 }
						 if(element.password)
						 {
							 $('input[name=password]').addClass("requiredVal");
						 }
					 }
					 else if(index == 'loginsuccess')
					 {
						 if(element.auth==true)
						 {
							 var userId = element.user;
							 //var userId = alert(element.user);
							 if(loginfor=='buytickets')
							 {
								 closemodel();
								 var checkquqntityAlert = checkquqntity();
								 if(checkquqntityAlert==1)
								 {
									 window.location.reload();
								 }
							 }
							 if(loginfor=='followorganiser')
							 {
								 var followUserId = $('input[name=organisderid]').val();
								 closemodel();
								 followorganiser(followUserId);
								 window.setTimeout(function(){location.reload()},2000)
							 }
							 if(loginfor=='markfavourite')
							 {
								 var favouriteeventid = $('input[name=favouriteeventid]').val();closemodel();
								 closemodel();
								 favouritevent(favouriteeventid);
								 window.setTimeout(function(){location.reload()},2000)
							 }
						 }
						 else
						 {
							  $(':input').val('');
							  $('.header').append('<div class="alert alert-warning fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid Login Details..</div>');
							 return false;
						 }
					 }
                });
            });
        }
		function contectuser(userid)
		{
			 modelblock('Contact to  the User','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small');
			messagebox(userid);
		}
		function setreminder (eventid)
		{
			modelblock('Set Reminder For Event','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small');
			setreminderbox(eventid);
		}
		function reportincorrect(eventid)
		{
			 modelblock('Report Incorrect','<div id="modelcontent"></div>','<input type="submit" name="closemodel" id="closemodel" class="btn btn-primary paper-shadow relative" value="Close"/>','small','small');
			 reportincorrecthandler(eventid);
			
		}
		function favouritevent(eventid)
        {
            var _token = $('input[name=_token]').val();
            $.getJSON(SITE_URL+'favouritevent',{'eventid':eventid,'_token':_token},function(data,status)
            {
				 $.each(data, function(index, element)
                 {
					 if(index=='success')
					{
						 if(element.status==0)
						{
							$('.favourite').removeClass("btn-primary");
							$('.favourite').addClass("requiredVal");
						}
						else if(element.status==1)
						{
							$('.favourite').removeClass("requiredVal");
							$('.favourite').addClass("btn-primary");
						}
					}
				 });
            });
        }
        function followorganiser(organiserid)
        {
            var _token = $('input[name=_token]').val();
            $.getJSON(SITE_URL+'followorganiser',{'organiserid':organiserid,'_token':_token},function(data,status)
            {
				 $.each(data, function(index, element)
                 {
					 if(index=='success')
					{
						 if(element.status==0)
						{
							var totlafollower = $('.totalfollowers').text();
							$('.totalfollowers').text(parseInt(totlafollower)+parseInt(1));
							$('.userFollow').text('Unfollow');
							$('.message').html('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully follow the user..</div>');
						}
						else if(element.status==1)
						{
							var totlafollower = $('.totalfollowers').text();
							$('.totalfollowers').text(parseInt(totlafollower)-parseInt(1));
							$('.userFollow').text('Follow');
							$('.message').html('<div class="alert alert-warning fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully Unfollow the user..</div>');
						}
					}
				 });
            });
        }
        function registerUser()
        {
            var email = $('input[name=email]').val();
            var name = $('input[name=name]').val();
            var password = $('input[name=password]').val();
            var _token = $('input[name=_token]').val();
            $.post(SITE_URL+'ajaxregister',{'name':name,'email':email,'password':password,'_token':_token},function(data,status)
            {

            });
        }
        function checkquqntity()
        {
            var quanityselect=0;
            $('.checkqu').each(function()
            {
                var valuesele = $(this).select().val();
                quanityselect+=parseInt(valuesele);
            });
            if(quanityselect>0)
            {
                $('#ticketbuyform').submit();
            }
            else
            {
                alert('Please select quantity for a ticket');
				return 1;
            }
        }
        function modelblock(header,body,footer,size)
        {
            closemodel();
            if(typeof(footer) == 'undefined'){
                footer = '';
            }
            if(typeof(size) == 'undefined'){
                size = 'small';
            }
            var template = '<div id="myModal" class="modal">'+
                '<div class="modal-dialog '+size+'">'+
                    '<div class="header">'+
                        '<h3>'+header+'</h3>'+
                    '</div>'+
                    '<div class="content">'+body+'</div>'+
                    '<div class="modelfooter">'+footer+'</div>'+
                '</div>'+
            '</div>';
            $('body').append(template);
        }

        function closemodel()
        {
            $('#myModal').remove();
        }
        function freeticketCheckout(ticketid)
        {
            var quantity = $('.quntity'+ticketid).select().val();
            var _token = $('input[name=_token]').val();
            if(quantity<1 || quantity=='')
            {
                alert('Please select tickets quantity');
                return false;
            }
            else
            {
                //$('#page_hide').show();
                $.post(SITE_URL+'makeorder',{'ticketid':ticketid,'quantity':quantity,'_token':_token},function(data,status)
                {
                  if(status=='success')
                  {
                     window.location.href=SITE_URL+'completeorder/'+data;


                  }
                });

            }
        }
        
         
      $(function(){
        $("#geocomplete").geocomplete({
          map: ".placepicker-map",
          details: "form ul",
          detailsAttribute: "data-geo"
        });
      });