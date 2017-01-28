
(function($,W,D){
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#changepassword-form").validate({
                rules: {
						password: {
							required: true,
                            minlength: 6
						},
						confirmpassword: {							
							required: true,
							equalTo: "#password",
                            minlength: 6
                           
						}
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