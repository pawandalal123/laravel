
$(document).ready(function() {
    $("#offline-frm").validate({
        rules: {
            amount: "required",
            quantity: "required",
            ticket_id: "required",
        },
        messages: {
            amount: "Please specify amount",
            quantity: "Please specify quantity",
            ticket_id: "Please select ticket"

        }
    })

    $("#bookingpricetxt").rules("add", "number");

    $('#ticket_id').on('change', function() {
          
          var dripdown = '<select class="form-control ticketquantity checkqu" style="width: 50px; border: 1px solid #eee;" name="quntity['+ this.value+']" id='+this.value+'><option value="">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option></select> ';
          $( "#qtyDropDown" ).html(dripdown);
                jsonObj = [];
                    var ticket_id =  this.value; 
                 
                 if(ticket_id.length>0){
                       
                   
                    var eventdate=$('.eventshowdate').val();
                    var eventshowid=$('.eventshowid').val();
                    var eventcreatetype=$('.eventcreatetype').val();              
                                         
                     $.ajax({
                        url: SITE_URL+"api/event/ticketlist",
                        type: "get",
                        beforeSend: function(){
                        $('.panel-body').loader('show');  
                        },complete: function(){
                           $('.panel-body').loader('hide'); 
                        },
                        data: {'ticket_id':ticket_id,'eventdate':eventdate,'eventshowid':eventshowid,'eventcreatetype':eventcreatetype,'_token': $('input[name=_token]').val(),'id':$('input[name=eventId]').val()},
                        success: function(data){ 
                          
                          //console.log(data.buyupto);
                          if(data.ticketarray[ticket_id]['buyupto'] > data.ticketarray[ticket_id]['ticketremaining'])
                            var selticket = data.ticketarray[ticket_id]['ticketremaining'];
                          else
                             var selticket = data.ticketarray[ticket_id]['buyupto'];
                          $('.checkqu').empty();
                           $('.checkqu').append('<option value>0</option>');
                           for(var i=1; i<=data.ticketarray[ticket_id]['buyupto']; i++)
                            {   
                              $('.checkqu').append('<option value='+i+'>'+i+'</option>');
                            }
                            
                           $( "#bookingpricetxt" ).val(0);    
                           $( "#amount" ).html(data.ticketarray[ticket_id]['ticketprice']);
                           $('.panel-body').loader('hide');  
                        },error:function(){ 
                                alert("error!!!!");
                                $('.panel-body').loader('hide');  
                         }
                    });

                  } else {
                      $('.panel-body').loader('hide');
                      $('#amount').text(''); 
                      $('#bookingpricetxt').val(''); 
                    }
                   //$("#parenttktdiv"+settingcounter).hide();     
            
    })
   
});



$("#quantity").rules("add", "number");
$("#amount").rules("add", "number");
$("#amount").rules("add", "amount");
