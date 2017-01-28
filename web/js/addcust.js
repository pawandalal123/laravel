$(document).ready(function(){ 
	 $( "#add_cusfield" ).hide();
	
		  $( "#custfieldselect" ).change(function() {
			  var chkselect = ["radio","select","checkbox"]; 
			  var indexval = chkselect.indexOf($( "#custfieldselect" ).val());
			  
				/* Search vikash in array list. */ 
				if(indexval != -1) {				
				   $( "#add_cusfield" ).show();
				} else {
				   $( "#add_cusfield" ).hide();
				}

			  //if($( "#custfieldselect" ).val() == 
			//  alert(jQuery.inArray("2",arrcontainer));
		  });
		    var counter = 1;
	        var maxField = 10; //Input fields increment limitation
			var addButton = $('.add_button'); //Add button selector
			var wrapper = $('.add_cusfield'); //Input field wrapper
			
			var x = 1; //Initial field counter is 1
			 
			$(addButton).click(function(){ //Once add button is clicked
				var fieldHTML = '<div id="custrmdic'+counter+'"><div class="col-sm-11 col-xs-10" ><div class="form-group" id="addrowtext"><label for="fieldvalue">Option Value</label><input type="text" name="optionvalue[]" placeholder="Field Name" maxlength="500"  class="form-control"  value=""/></div></div><div class="col-sm-1 col-xs-2 addicon"><a href="javascript:void(0);" id="'+counter+'" class="remove_button" title="Add field"><i class="fa fa-minus-circle"></i></a></div></div>';
			counter++;
			if(x < maxField){ 
				x++; 				
				$(wrapper).prepend(fieldHTML); // Add field html
			}
			});
			$(wrapper).on('click', '.remove_button', function(e){ 
				getdivid =  $(this).attr("id");				
				e.preventDefault();
				$('#custrmdic'+getdivid).remove(); //Remove field html
				x--; //Decrement field counter
			});
           
});
(function($,W,D){
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#custadd-form").validate({
                rules: {
						feild_name: {
							required: true
							//alphastring: true
						},
						feild_type: "required"
                },
                messages: {
                                  
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);