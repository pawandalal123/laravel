(function() 
{
// Localize jQuery variable
var jQuery;
var scripts= document.getElementsByTagName('script');
var mysrc= scripts[scripts.length-1].src;
var arr = mysrc.split("/web");
// console.log(arr[0]);
var SITE_URL =arr[0]+'/';
var APIURL = arr[0]+'/api/';
//var SITE_URL ='http://localhost/php/devgo/GEsite/';
//var APIURL = 'http://localhost/php/devgo/GEsite/api/';

/******** Load jQuery if not present *********/
if (window.jQuery === undefined || window.jQuery.fn.jquery !== '1.4.2') 
{
    var javascript = document.createElement('script');
    javascript.setAttribute("type","text/javascript");
    javascript.setAttribute("src",
        "https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js");
    if (javascript.readyState)
     {
        javascript.onreadystatechange = function () 
        { // For old versions of IE
          if (this.readyState == 'complete' || this.readyState == 'loaded')
          {
              scriptLoadHandler();
          }
      };
    } 
    else 
    {
      javascript.onload = scriptLoadHandler;
    }
    // Try to find the head, otherwise default to the documentElement
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(javascript);
} 
else 
{
    // The jQuery version on the window is the one we want to use
    jQuery = window.jQuery;
    main();
}

/******** Called once jQuery has loaded ******/
function scriptLoadHandler() 
{
    // Restore $ and window.jQuery to their previous values and store the
    // new jQuery in our local jQuery variable
    jQuery = window.jQuery.noConflict(true);
    // Call our main function
    main(); 
}

/******** Our main function ********/
function main() { 
    jQuery(document).ready(function($) { 
        /******* Load CSS *******/
        var css_link = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: SITE_URL+'web/css/ticketwidget.css' 
        });
        //         var mobilecss_link = $("<link>", { 
        //     rel: "stylesheet", 
        //     type: "text/css", 
        //     href: SITE_URL+'web/css/mobile.css' 
        // });
        var csspaper_link = $("<link>", { 
            rel: "stylesheet", 
            type: "text/css", 
            href: SITE_URL+'web/css/ticket-widget-paper-bootstrap.css' 
        });
        var js_link = $("<script>", { 
            //rel: "stylesheet", 
            type: "application/javascript", 
            href: SITE_URL+'web/js/eventdetail.js' 
        });
                csspaper_link.appendTo('head');  
                css_link.appendTo('head'); 
        // mobilecss_link.appendTo('head'); 

        // js_link.appendTo('head');  
        var eventId='&id='+Eventid;
        $.ajax({
        type: "get",
        url:   SITE_URL+"api/event/tickewidgetapi",   
        datatype: 'json',
        data: eventId,
        success: function (result) {
        
            WidgetCallback([result]);
         
            },
        error: function (result) {
            
        }
    });       

    });
}


function WidgetCallback(json) 
{
  // console.log(Eventid);

  jQuery(document).ready(function($) { 
    var ticketwidget = json[0];
    var eventData  =ticketwidget.ticketWidgetData;
    var textdispalyArray = ticketwidget.textdispalyArray;


    var ticketarray = ticketwidget.ticketarray;
    var ticketobject;
    var name;
    var count;
  ticketobject = ticketarray;
   //display("Keys for entry 0:");
  count = 0;
    var wHTML = "";
    if(eventData=='badrequest')
    {
      wHTML +=('<section><div class=""><div class="row"><div class=" mt10 ge-box" id="ticketid"><div class="ticketwidgetbox">');
       wHTML +=('<div style="text-align: center;font-weight: bold;">Event has end or ticket booking has closed</div>');
      wHTML +=('<div style="text-align: center;">Powered by <a href="'+SITE_URL+'"><img src="'+SITE_URL+'web/images/smallogo.png"></a></div></div></div></div></div></div></section>');

    }
    else
    {


    wHTML +=('<section><div class=""><div class="row"><div class=" mt10 ge-box" id="ticketid"><div class="ticketwidgetbox">');
    if(eventData.bannerimage)
    {
      wHTML +=('<div class="ticketbanner"><img src="'+eventData.bannerimage+'" class="img-responsive"></div>');
    }
    wHTML +=('<div class="Logo-and-Time"><div class="media">');
    wHTML +=('<div class="media-body ticketschedule">');
    wHTML +=('<div class="eventtitle"><h3>'+eventData.eventtitle+'</h3></div>');
    wHTML +=('<p class="eventdate"><img src="'+SITE_URL+'web/images/calendar.jpg"><span>'+eventData.startdate+'</span></p>');
    wHTML +=('<p class="eventvenue"><img src="'+SITE_URL+'web/images/location.jpg">'+eventData.venuename+', <a href="#">'+eventData.city+'</a>, <a href="#">'+eventData.country+'</a></p>');
    wHTML +=('</div></div></div>');
    wHTML +=('<input type="hidden" name="eventid" value="'+eventData.eventid+'">');
    wHTML += ('<div class="clearfix"><table class="table"><thead><tr><th style="width:50%;">Ticket Type</th><th style="width:100px;">'+textdispalyArray.textforbox+'</th>');
        if(eventData.showremaining)
    {
    wHTML += ('<th style="width:100px;" class="columnhide">Remaining</th>');
    }
    wHTML += ('<th style="width:50px;">Quantity</th><th style="width:50px;" class="subtotal">Sub Total</th></tr></thead>');
    wHTML += ('<tbody>');
  for (name in ticketobject) 
  {
      //alert(name.ticketname);
      var ticketdetails = ticketobject[name];
      //alert(ticketdetails.ticketname);
      wHTML += ('<tr class="ticketcur ticketlist'+name+'" id="'+ticketdetails.currencyid+'">');
      wHTML += ('<td >'+ticketdetails.ticketname);
      wHTML +=('<a data-toggle="collapse" href="#ticket'+count+'" aria-expanded="false" aria-controls="ticket'+count+'" title="Show ticket details" style="margin-left:5px; color:#888;"><i class="fa fa-info information"></i></a><br>');

      if(eventData.ticketdescription)
      {
              wHTML +=('<span style="color:#999; font-size:11px; font-weight:normal!important;" class="columnhide">'+ticketdetails.ticketdescription+'</span></td>');
      }

      wHTML += ('<td class="price'+name+'">');
      if(ticketdetails.ticketprice===null)
      {
        wHTML +=('<small style="font-size:11px; font-weight:bold">'+ticketdetails.currancyName+'</small>');
        wHTML +=('<input type="number" min=0 class="ticketquantity quntity['+name+']" id="'+name+'" name="donate'+name+'" value=""  onchange="myFunction('+name+')"> ');
      }
      else
      {
        wHTML +=('<small style="font-size:11px; font-weight:bold">'+ticketdetails.currancyName+'</small>');
        wHTML +=ticketdetails.ticketprice;
      }
      wHTML +=('</td>');

    if(eventData.showremaining)
    {
    wHTML +=('<td class="columnhide">'+ticketdetails.ticketremaining+'</td>');
    }
      
      wHTML += ('<td><div>');
      if(ticketdetails.ticketprice===null)
      {
        wHTML +=('<label>1</label>');

      }

      else if(ticketdetails.ticketstatus=='avilable' && ticketdetails.ticketprice>=0)
      {
        wHTML +=('<select class="form-control ticketquantity  checkqu quntity['+name+']" id="'+name+'" name="quntity['+name+']" style="width: 50px; border: 1px solid #eee;" onchange="myFunction('+name+')" >');
        wHTML +=('<option value="">0</option>');
        for (i = ticketdetails.startfrom; i <= ticketdetails.buyupto; i++)
        { 
          wHTML +=('<option value="'+i+'">'+i+'</option>');
        }
            wHTML +=('</select>');
      }
      else
      {
        wHTML +=ticketdetails.ticketstatus;
      }
        wHTML +=('</div></td><td class="subtotal subtotalamount"  id="subtotal'+name+'"></td>');
      wHTML += ('</tr>');

     wHTML +=('                 <tr class="detailbg">');
     wHTML +=('             <td colspan="5">');
     wHTML +=('               <div class="collapse detailborder" id="ticket'+count+'">');
                    if(ticketdetails.showremaining==1)
     wHTML +=('                 <div class="pt5">Remaning:<span class="remaningticket">'+ticketdetails.ticketremaining+'</span></div>');
                   
      wHTML +=('                <div class="pt5">Available Till:<span class="remaningtime">'+ticketdetails.convertedTimeCounter+'</span></div>');
      wHTML +=('                <div class="ticketdesc mt5">'+ticketdetails.ticketdescription);
      wHTML +=('                </div> ');             
      wHTML +=('              </div>');
      wHTML +=('            </td>');
      wHTML +=('          </tr>');

      ++count;
  }
    wHTML +=('</tbody></table>');;
    wHTML+=('<div class="col-sm-6 text-center marigtop">');
    if(ticketwidget.coupuncode=='Yes')
  {
    
      wHTML+=('        <div class=" apllymsg applycode smallhide">');
      wHTML+=('          <i style="color:green;" class="fa fa-check"></i>');
       wHTML+=('         Coupon code applied:  <strong style="color:#EF811D;" class="couponcode" ></strong> '); 
       wHTML+=('         <a  class="clearcoupon" title="Clear Coupon" onClick="clearcoupon()"><i class="fa fa-times"></i>&nbsp;clear</a>');

       wHTML+=('       </div>');
       wHTML+=('<span class="coupontext mt10" onClick="showcoupoun()">Enter Coupon code</span>');
       wHTML+=('       <div class="couponcombinebox  pad">');
        wHTML+=('        <input name="coupencode"  type="text" placeholder="Enter coupon code" style="font-size:14px;" id="webid" class="form-control">');
        wHTML+=('        <button value="submit"  type="button" name="applycode"   class="apply-combinebtnright btnmy  applycoupen" onClick="discount()" >Apply</button>');
        wHTML+=('      </div>');
        
    }
    else
    {
      wHTML +=('<input type="hidden" class="form-control" name="coupencode" placeholder="Enter coupoun">');
    }
  
wHTML+=('    </div>');
    wHTML +=('        <div class="col-sm-6">');
    wHTML +=('            <div class="pricebox">');
     wHTML +=('             <table>');
     wHTML +=('               <tbody>');
     wHTML +=('                 <tr>');
     wHTML +=('                   <td>Total</td>');
    wHTML +=('                    <td>:</td>');
     wHTML +=('                   <td><span id="bookingprice" class="bookingprice">0</span></td>');
     wHTML +=('                 </tr>');
      wHTML +=('              <tr class="dicountclass  smallhide">');
      wHTML +=('                 <td class="dicounttext">Discount<span class="small-text">(coupon code discount)</span></td></td>');
      wHTML +=('                 <td>:</td>');
      wHTML +=('                 <td><span id="discountamount" class="discountamount">0</span></td>');
       wHTML +=('              </tr>');
       // wHTML +=('<tr class="collapsetax smallhide" id="taxbreakup"></tr>');
     wHTML +=(' <tr class="extracharges smallhide"><td>Extra Charges</td><td>:</td><td><span class="extraprice" id="">0</span></td></tr>');
      wHTML +=('<tr class="InternetCharges smallhide"><td>Internet Handling Fee</td><td>:</td><td><span class="internetprice" >0</span></td></tr>');
      wHTML +=('<tr class="Internetdiscount smallhide"><td>Discount<span class="small-text">(100% discount on internet handling fee)</span></td><td>:</td><td><span class="internetamount" >0</span></td></tr>');
      wHTML +=('              <tr class="FinalAmountclass">');
        wHTML +=('               <td>Amount Payable</td>');
        wHTML +=('               <td>:</td>');
        wHTML +=('               <td><span class="currancy"></span><span id="finalamount" class="finalamount">0</span></td>');
        wHTML +=('             </tr> ');
        wHTML +=('          </tbody>');
       wHTML +=('          </table>');
        wHTML +=('       </div></div>');
 
       
      

    wHTML +=('<div class="clearfix"></div><div class="col-sm-5 col-sm-push-7"><div class="book-btn">');
    wHTML +=('<a class="btn btn-primary btn-block bookingbtn mt10" href="javascript:void(0)" onClick="bookNow()">'+textdispalyArray.booknowbutton+'</a></div></div>');
     wHTML +=('<div class="col-sm-7 col-sm-pull-5"><div class="payment-img payment-imgg"><span>Secure online payment options </span>');
        wHTML +=('<img src="'+SITE_URL+'/web/images/cards/visa-card.png">');
        wHTML +=('<img src="'+SITE_URL+'/web/images/cards/master-card.png">');
        wHTML +=('<img src="'+SITE_URL+'/web/images/cards/american-card.png">');
        wHTML +=('<img src="'+SITE_URL+'/web/images/cards/mastro-card.png">');
        wHTML +=('<img src="'+SITE_URL+'/web/images/cards/net-banking.png">');
        wHTML +=('</div></div>');
     wHTML +=('<div class="col-sm-12 text-center payment-text"><span>You will receive '+textdispalyArray.ticket_text+' in your email immediately after making the payment.</span></div>');
 wHTML +=('</div>');
    wHTML +=('<div class="ticketlogo text-center">');
    
      wHTML +=('<span>Powered by</span><a href=""><img src="'+SITE_URL+'web/images/smallogo.png"></a></div></div></div></div></div></section>');
    }

    document.getElementById('example-widget-container').innerHTML = wHTML;
    // console.log(wHTML);
    $('.ticketbox').width(eventData.width).height(eventData.height);
    if(eventData.banner!=1)
    {
      $('.ticketbanner').hide();

    }

    if(eventData.logo!=1)
    {
      $('.ticketlogo').hide();

    }
    if(eventData.showtitle!=1)
    {
      $('.eventtitle').hide();

    }
    if(eventData.eventdates!=1)
    {
      $('.eventdate').hide();
    }
    if(eventData.eventvenue!=1)
    {
      $('.eventvenue').hide();
    }
    
    if(eventData.topcolor)
    {
     
        $('.ticketwidgetbox .table thead').attr('style', 'background-color: #'+eventData.topcolor+' !important');

    }
    if(eventData.button_color)
    {
        $('.ticketwidgetbox .btn-primary,.ticketwidgetbox .btnmy').attr('style', 'background-color: #'+eventData.button_color+' !important');
    }
});
}


window.myFunction = function (ticketid)
{
  jQuery(document).ready(function($) 
  { 
    //var selectedticket =$('select[name=quntity]:selected').attr('id');
    var currancyid = $('.ticketlist'+ticketid).attr('id');
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
  
}
window.showcoupoun = function ()
{
  jQuery(document).ready(function($) 
  { 
    $(".coupontext").hide();
    $(".couponcombinebox").show();
  });
  
}
window.clearcoupon = function ()
{
  jQuery(document).ready(function($) 
  { 
   $('input[name=coupencode]').val('');
   $('.apllymsg').hide();
   $('.pad').show();
   $('.dicountclass').hide();
  calculateAmount(0,'');
});
}
window.hideshow = function ()
{
  jQuery(document).ready(function($) 
  { 
   $('.collapsetax').toggle();
});
}
window.discount = function ()
{
  jQuery(document).ready(function($) 
  { 
    var coupencode = $('input[name=coupencode]').val();
    if(coupencode=='')
    {
      alert('Please enter valid coupen');
      // swal({ title: "Oops...", text: "Please enter validcoupen.", type: "error" });
    }
    else
    {
      var count =0;
      $('.ticketquantity').each(function() 
      {
        //only for paid ticket//
        var ticketArray={};
        var ticketid = $(this).attr('id');
        var value = $(this).val(); 
        if(value>0)
        {
          ticketArray[ticketid]=value;
          count++;
        }
            });
      if(count==0)
      {
        alert('Please select atleast one ticket.');
        // swal({ title: 'Please select atleast one ticket.',     timer: 2000 ,type:'warning'});
        return false;
      }
      else
      {
        calculateAmount(0,coupencode);
      }
    }
    //calculateAmount(0);
  
});
  
}

window.calculateAmount=function (book,coupencode,bookfrom)
{
       jQuery(document).ready(function($) 
  {      
            var newtotalprice=0;
              var eventId=Eventid;
              var ticketArray={};
              var donateTicketArray={};
              var count =0;
              $('.ticketquantity').each(function() 
        {
          var ticketid = $(this).attr('id');
          var value = $(this).val(); 
          // if donation ticket then this value is amount else its considered as quantity

          //only donation type ticket will have input textbox 
          if($(this).attr('type')=='text' || $(this).attr('type')=='number')
          {
            if(value>0)
            {donateTicketArray[ticketid]=value;
              count++;}

          }else {

            
          if(value>0)
          {ticketArray[ticketid]=value;
            count++;}

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
                $('.currancy').hide();
                 $('.collapsetax').html('');
              $('.collapsetax').hide();
              } 
    var data={};
  data.ticketArray=ticketArray;
  data.eventId=eventId;
  data.donateTicketArray=donateTicketArray;
  data.bookfrom=bookfrom;
  data.book=book;
  data.coupencode=coupencode;

// console.log(count);
  if(book==1 && count==0)
  {
    alert('Please select atleast one ticket');
    // swal({   title: 'Please select atleast one ticket.',     timer: 2000 ,type:'warning'});
    return false;
  }

  if(count>0)
  {
  // Ajax call to calculate amount
    $.ajax({
        type: "post",
        url:   SITE_URL+"api/event/calculateAmount",   
        datatype: 'json',
        data: data,
        success: function (result) {
          var response=result.response.calculatedDetails;
          if(response.bookingerror=='error')
          {
            alert('Not a valid quantity');
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
             $('.collapsetax').html('');
              $('.collapsetax').hide();
          }
          else
          {
            if(response.donateError=='donateError')
            {
              alert('Minimum donation amount is '+response.donateAmount+'');
              return false;
            }
            else
            {
              if(book)
              { 
                window.top.location.href = SITE_URL + 'completeorder/' + response.orderId;
                  return false;
                }
              else
              {
                  $('#bookingprice').html(response.totalAmount);
                  $('.FinalAmountclass').show();
                  $('.finalamount').html(response.finalamount);
                  $('.currancy').html(response.currancyname+'&nbsp;');
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
                  wHTML='';
                if(response.extracharges.total>0)
                {
                  for(extraamount in response.extracharges.front)
                  {
                    var displayname=response.extracharges.front[extraamount].name;
                    if(response.extracharges.front[extraamount].name=='Service Charges')
                    {
                       displayname='Convenience Fee (incl. of taxes)';
                    }
                    
                    wHTML+='<tr class="texcharges"><td><span class="tic-small-text" style="line-height:12px !important">'+displayname;
                    if(response.extracharges.front[extraamount].fixedvalue!='' && response.extracharges.front[extraamount].name!='Convenience Fee' && response.extracharges.front[extraamount].name!='Service Charges')
                    wHTML+=' @ '+response.extracharges.front[extraamount].fixedvalue+'%'
                    if ('details' in response.extracharges.front[extraamount])
                    {
                        var detailsArray =response.extracharges.front[extraamount].details;
                        wHTML+='</span></td><td>:</td><td><span class="tic-small-text">'+response.extracharges.front[extraamount].amount+'&nbsp;&nbsp;<small class="tax_breakup"><a href="#taxbreakup'+extraamount+'" data-toggle="collapse" aria-expanded="false" aria-controls="taxbreakup"><i class="fa fa-info" aria-hidden="true"></i></a></small></span></td></tr>';
                              // $('#taxbreakup'+extraamount).html('');
                          $('#taxbreakup'+extraamount).html('');
                        wHTML+='<tr class="collapse" id="taxbreakup'+extraamount+'"><td colspan="3"><table class="price_table_tax">';
                        for(taxdetails in detailsArray)
                        {
                          wHTML+='<tr><td>'+taxdetails+'</td><td>=</td><td>'+response.currancyname+' '+detailsArray[taxdetails]+'</td></tr>';
                        }
                        wHTML+='</table></td></tr>';
                    }
                    else
                    {
                      wHTML+='</span></td><td>:</td><td><span class=" tic-small-text">'+response.extracharges.front[extraamount].amount+'</span></td></tr>';

                    }
                    
                  }
                
                  // var wHTMLs='';
                  // if('ServiceTax' in response.extracharges.detail)
                  // {
                  //   $('.collapsetax').html('');
                  //   $('.detailicon').show();
                  //   wHTMLs+='<td colspan="3"><table class="price_table_tax">';
                  //   for(chargesamount in response.extracharges.detail.ServiceTax)
                  //   {
                  //     wHTMLs+='<tr><td>'+response.extracharges.detail.ServiceTax[chargesamount].name+'</td><td>=</td><td>'+response.currancyname+' '+response.extracharges.detail.ServiceTax[chargesamount].amount+'</td></tr>';
                  //   }
                  //   wHTMLs+='</table></td>';
                  //  }
                  // else
                  // {
                  //   $('.detailicon').hide();
                  //   $('.collapsetax').html('');
                  // }
                  $('.texcharges').html('');
                  $('.dicountclass').after(wHTML);
                  // $('.collapsetax').html(wHTMLs);
                  $('.InternetCharges').hide();
                  $('.internetamount').html('');
                }
               
                else if(response.internetChargeRate>0)
                {
                  
                  $('.extracharges').hide();
                  $('.extraprice').html('');
                  $('.InternetCharges').show();
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
                    //swal({   title: 'Group discount applied.',     timer: 3000 ,type:'success'});
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
                    alert('Coupen code not valid');
                    //swal({   title: 'Coupen code not valid.',     timer: 2000 ,type:'warning'});

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


});

};
window.bookNow = function()
{
  jQuery(document).ready(function($) 
  { 
    var coupencode=''
    coupencode = $('input[name=coupencode]').val();
    calculateAmount(1,coupencode,1); //passing book parameter 
  });

}



})(); 