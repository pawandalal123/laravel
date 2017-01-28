$(document).ready(function(){	

$("#widget-form").validate({
			rules: {
				        "width": {                            
                            number: true,
                            min:400,
                            max:1200
                        },
                        "height": {                            
                            number: true,
                            min:400,
                            max:1200
                        }

                    },
                    messages: {
                        "width": {                           
                            number: "Pincode number must contain digits only"
                        },
						"height": {                           
                            number: "Pincode number must contain digits only"
                        }
                    }
                
		});
		
 
});


