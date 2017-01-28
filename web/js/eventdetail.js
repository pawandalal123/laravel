$('body').on('change','.ticketquantity',function()
{
	//var coupencode='';
	var selectedticket =$(this).attr('id');
	var currancyid = $('.ticketlist'+selectedticket).attr('id');
	var count =0;
	$('.ticketquantity').each(function() 
	{
		var ticketid = $(this).attr('id');
		var value = $(this).val();
		var currancyforall = $('.ticketlist'+ticketid).attr('id');
		if(currancyid!=currancyforall)
		{
			$('#'+ticketid).attr('disabled', 'true');
		}
		else
		{
			//$('.ticketlist'+ticketid).show()
			$('#'+ticketid).removeAttr('disabled');
		}
		if(value>0)
		{
			count++;
		}
    });
    if(count<=0)
    {
    	$('.ticketquantity').removeAttr('disabled');

    }
    
	var coupencode = $('input[name=coupencode]').val();
	calculateAmount(0,coupencode);


});
$('body').on('click','.clearcoupon',function()
{
	 $('input[name=coupencode]').val('');
	 $('.apllymsg').hide();
	 $('.pad').show();
	 $('.dicountclass').hide();
	 calculateAmount(0,'');
	 
});
	$('body').on('click','.applycoupen',function()
	{

		var coupencode = $('input[name=coupencode]').val();
		if(coupencode=='')
		{
			swal({ title: "Oops...", text: "Given coupon is not valid.", type: "error" });

		}
		else
		{
			var count =0;
        	$('.ticketquantity').each(function() 
			{
				//only for paid ticket//
				//var ticketArray={};
				var ticketid = $(this).attr('id');
				var value = $(this).val(); 
				if(value>0)
				{
					//ticketArray[ticketid]=value;
					count++;
				}
            });
            if(count==0)
			{
				swal({ title: 'Please select atleast one ticket.',     timer: 2000 ,type:'warning'});
				return false;
			}
			else
			{
				calculateAmount(0,coupencode);
			}
		}
		//calculateAmount(0);
	});

function calculateAmount(book,coupencode,bookfrom)
{
		        var newtotalprice=0;
            	var eventId=$('.eventdisplayid').val();
            	var eventdate=$('.eventshowdate').val();
            	var eventshowid=$('.eventshowid').val();
            	var eventcreatetype=$('.eventcreatetype').val();
            	var ticketArray={};
            	var donateTicketArray={};
            	var count =0;
            	$('.ticketquantity').each(function() 
				{
					var ticketid = $(this).attr('id');
					if(ticketid=='')
					{
						swal({   title: 'Please select atleast one ticket.',     timer: 2000 ,type:'warning'});
		                return false;
					}
					var value = $(this).val(); 
					// if donation ticket then this value is amount else its considered as quantity

					//only donation type ticket will have input textbox 
					if($(this).attr('type')=='text' || $(this).attr('type')=='number')
					{
						if(value>0)
						{
							donateTicketArray[ticketid]=value;
							count++;
						}
					}
					else 
					{
						if(value>0)
						{

							ticketArray[ticketid]=value;
							count++;
						}
						else
						{
							$('#subtotal'+ticketid).html('0');

						}
					}
	            });
	            if(count<=0)
	            {
	            	$('.subtotal').hide();
	            	$('.Internetdiscount').hide();
					$('.dicountclass').hide();
				    $('.FinalAmountclass').hide();
				    $('.InternetCharges').hide();
				    $('#bookingprice').html(0);
			        $('.FinalAmountclass').show();
				    $('.finalamount').html(0);
				    $('.texcharges').html('');
				    $('.texcharges').hide();
				    $('.currancy').html('');
				    $('.collapse').html('');
			        $('.collapse').hide();
	            }
            	
    var data={};
	data.ticketArray=ticketArray;
	data.eventId=eventId;
	data.eventdate=eventdate;
	data.eventshowid=eventshowid;
	data.bookfrom=bookfrom;
	data.eventcreatetype=eventcreatetype;
	data.donateTicketArray=donateTicketArray;
	data.book=book;
	var checkInputFlag = false;
	data.coupencode=coupencode;
	if ($('#bookingpricetxt').length > 0)
	{
       checkInputFlag = true;
    }
   
    //console.log(count);
	if(book==1 && count==0)
	{
		swal({   title: 'Please select atleast one ticket.',     timer: 2000 ,type:'warning'});
		return false;
	}

	if(count>0)
	{
	// Ajax call to calculate amount
	 data.offlineAmount=$('#bookingpricetxt').val();
	 
    $.ajax({
        type: "post",
        url:   SITE_URL+"api/event/calculateAmount",   
        datatype: 'json',
        data: data,
        success: function (result) {
        	var response=result.response.calculatedDetails;
        	if(response.bookingerror=='error')
        	{
               swal({   title: 'Not a valid quantity.',     timer: 2000 ,type:'warning'});
                $('.Internetdiscount').hide();
				$('.dicountclass').hide();
			    $('.FinalAmountclass').hide();
			    $('.InternetCharges').hide();
			    $('#bookingprice').html(0);
		        $('.FinalAmountclass').show();
			    $('.finalamount').html(0);
			    $('.texcharges').html('');
			    $('.texcharges').hide();
			    $('.currancy').html('');
			    $('.collapse').html('');
			    $('.collapse').hide();
        	}
        	else if(response.bookingerror=='quantitynotmatch')
        	{
	        		swal({   title: 'Selected ticket is not available any more.',     timer: 3000 ,type:'warning'});
	        		window.location.reloade();
			             //eturn false;
        	}
        	else
        	{
	        	if(response.donateError=='donateError')
	        	{
	        		swal({   title: 'Minimum donation amount is '+response.donateAmount+'',     timer: 3000 ,type:'warning'});
			             return false;

	        	}
	        	else
	        	{
		            if(book)
		        	{ 
		        		if(book == 3)
		        		{
		        			var loginas = $('input[name=loginas]').val();
							window.location.href = SITE_URL + 'offline/order/complete/' + response.orderId+'/'+loginas;
							return false;
		        		}
		        		window.location.href = SITE_URL + 'completeorder/' + response.orderId+'/'+eventId;
		        	    return false;
		            }
		        	else
		        	{

		        		if(checkInputFlag == true)
		        		{
		        		  $('#bookingpricetxt').val(response.totalAmount);
		        		}
			    		else 
			    		{
			    		  $('#bookingprice').html(response.totalAmount);
			    		  $('.FinalAmountclass').show();
			        	  $('.finalamount').html(response.finalamount);
			        	  $('.currancy').html(response.currancyname+'&nbsp;');
			    		}
			    		var ticketArray = response.ticketArray;
			    		$('.subtotalamount').html('');
			    		for(ticketid in response.ticketArray)
	        			{
	        				var ticketprice = response.ticketArray[ticketid].price;
	        				var ticketqt = response.ticketArray[ticketid].selectedQuantity;
	        				var subtotal = ticketprice*ticketqt;
	        				$('#subtotal'+ticketid).html(subtotal);
	        				$('.subtotal').show();
	        			}
			    		var wHTML = "";
		        		if(response.extracharges.total>0)
		        		{
		        			for(extraamount in response.extracharges.front)
		        			{
		        				// var wHTMLs='';
		        				var displayname=response.extracharges.front[extraamount].name;
		        				if(response.extracharges.front[extraamount].name=='Service Charges')
		        				{
		        					 displayname='Convenience Fee (incl. of taxes)';
		        				}
		        				wHTML+='<tr class="texcharges"><td><span class="small-text tic-small-text" >'+displayname;
		        				if(response.extracharges.front[extraamount].fixedvalue!='' && response.extracharges.front[extraamount].name!='Convenience Fee' && response.extracharges.front[extraamount].name!='Service Charges')
		        				{
		        					wHTML+=' @ '+response.extracharges.front[extraamount].fixedvalue+'%'
		        				}
		        				if ('details' in response.extracharges.front[extraamount])
		        				{
		        					  var detailsArray =response.extracharges.front[extraamount].details;
		        					  wHTML+='</span></td><td>:</td><td align="right"><span class="small-text tic-small-text">'+response.extracharges.front[extraamount].amount+'&nbsp;&nbsp;<small class="tax_breakup"><a href="#taxbreakup'+extraamount+'" data-toggle="collapse" aria-expanded="false" aria-controls="taxbreakup"><i class="fa fa-info" aria-hidden="true"></i></a></small></span></td></tr>';
				                      $('#taxbreakup'+extraamount).html('');
				                  
				                      wHTML+='<tr class="collapse" id="taxbreakup'+extraamount+'"><td colspan="3"><table class="price_table_tax price_table_space">';
				                      for(taxdetails in detailsArray)
				        			  {
					                      wHTML+='<tr><td>'+taxdetails+'</td><td>=</td><td align="right">'+response.currancyname+' '+detailsArray[taxdetails]+'</td></tr>';
					                  }
					                  wHTML+='</table></td></tr>';
		        				}
		        				else
		        				{
		        					wHTML+='</span></td><td>:</td><td align="right"><span class="small-text tic-small-text">'+response.extracharges.front[extraamount].amount+'</span></td></tr>';
		        				}
		        			}
		        	
		                    
		        			$('.texcharges').html('');
		        			$('.dicountclass').after(wHTML);
		        			$('.InternetCharges').hide();
			                $('.internetamount').html('');

		        		}
		        		else if(response.internetChargeRate>0)
		        		{
		        			$('.InternetCharges').show();
		        			$('.extracharges').hide();
		        			$('.extraprice').html('');
			        		$('.internetprice').html(response.internetdiscount);
			        		$('.Internetdiscount').show();
			        		$('.internetamount').html(response.internetdiscount);
			        		$('.texcharges').html('');
						    $('.texcharges').hide();
			        		
		        		}
		        		else
		        		{
		        			$('.InternetCharges').hide();
			                $('.internetamount').html('');
				            $('.extracharges').hide();
				            $('.extraprice').html('');
				            $('.texcharges').html('');
						    $('.texcharges').hide();
		        		}

			        	if(response.discount>0)
			        	{
			        		if(response.goeventzdiscount==1)
			        		{
			        			$('.dicounttext').text('Goeventz Discount');
			        		}
			        		if(response.directdiscount>0)
			        		{
			        			swal({   title: 'Group discount applied.',     timer: 3000 ,type:'success'});
			        			$('.dicounttext').find('.small-text').text('(group discount)');
			        			
			        		}
			        		else
			        		{
			        			$('.couponcode').text(coupencode);
			        		    $('.apllymsg').show();
			        		    $('.pad').hide();
			        		    $('.dicounttext').find('.small-text').text('(coupon code discount)');
			        		}
			        		$('.dicountclass').show();
			        		$('.FinalAmountclass').show();
			        		$('.discountamount').html(response.discount);
			        		$('.finalamount').html(response.finalamount);

			        	}
			        	else
			        	{
			        		if(coupencode!='')
			        		{
			        			//alert(coupencode);
			        			swal({   title: 'Coupon code not valid.',     timer: 2000 ,type:'warning'});

			        		}
			        		$('.dicountclass').hide();
			        		//$('.FinalAmountclass').hide();

			        	}

		        	}
	        		
	        	}
        	}
        	

            },
        error: function (result) {
            //alert('Please select a quantity.');   //make this Dynamic to make sure proper error is returned based on api response
        }
    });

		}






	// var callBack={};
	// callBack.success=function(result){

	// 	        	var response=result.response.calculatedDetails;
		        	
	// 	        	$('#bookingprice').html(response.totalAmount);

	// }

	// callBack.error=function(result){

	// 	alert('somthing went wrong please try again');   //make this Dynamic to make sure proper error is returned based on api response
	// }

	// ajaxCall('POST',SITE_URL+"/api/event/calculateAmount",data,callBack);

	}


  //       // COMMON FUNCTION FOR AJAX CALL
  //       function ajaxCall(method,url,data,callBack)
		// {

		// 	// Ajax call 
		//     $.ajax({
		//         type: method,
		//         url:   url,   
		//         datatype: 'json',
		//         data: data,
		//         success: function (result) {
		        	
		//         	callBack.success(result);

		//             },
		//         error: function (result) {
		//             callBack.error(result);
		//         }
		//     });

		// }



	function bookNow()
	{
		var coupencode=''
		coupencode = $('input[name=coupencode]').val();
		calculateAmount(1,coupencode,0); //passing book parameter 

	}
		