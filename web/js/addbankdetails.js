$(document).ready(function(){ 

   $("#bankdetail-form").validate({
			rules: {
				        "account_name": {                            
                            letterslonly: false
                        },
                        "bank_name": {                            
                            lettersspecialonly: true
                        },
                        "bank_name": {                            
                            account_number: true
                        },
                        "bank_name": {                            
                            ifsc: true
                        }
                    },
                    messages: {
                        
                    }
                
		});
   $("#contact-form").validate({
            rules: {
                        "first_name": { 
                        required: true,                          
                            letterslonly: true
                        },
                        // "email": {  
                        //     required: true,                          
                        //     email: true
                        // },
                        "mobile": { 
                            required: true,  
                            minlength: 10,                        
                            number: true
                        },
                        "pincode": {  
                        required: true,
                        maxlength: 8,                           
                        number: true
                        }
                    },
                    messages: {
                        
                    }
                
        });

})