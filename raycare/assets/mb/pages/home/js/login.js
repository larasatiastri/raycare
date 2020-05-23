mb.app.home = mb.app.home || {};
mb.app.home.login = mb.app.home.login || {};

// mb.app.home.login namespace
(function(o){

    var 
        baseAppUrl              = '';
      

    var handleLogin = function() {

        $('.login-form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    remember: {
                        required: false
                    }
                },

                messages: {
                    username: {
                        required: "Masukan Username"
                    },
                    password: {
                        required: "Masukan Password"
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    $('.alert-danger', $('.login-form')).show();
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },

                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('.input-icon'));
                },

                submitHandler: function (form) {
                    form.submit(); // form validation success, call ajax form submit
                }
            });

            $('.login-form input').keypress(function (e) {
                if (e.which == 13) {
                    if ($('.login-form').validate().form()) {
                        $('.login-form').submit(); //form validation success, call ajax form submit
                    }
                    return false;
                }
            });
    }





    // mb.app.home.login properties
    o.init = function(){
        baseAppUrl = mb.baseUrl() + 'master/home/';
        handleLogin();
    };

}(mb.app.home.login));


// initialize  mb.app.home.login
$(function(){
    mb.app.home.login.init();
});