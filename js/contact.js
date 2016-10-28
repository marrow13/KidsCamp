function sendForm(formId, validationCallback) {
    this.formId = formId;
    this.form = $('#' + this.formId);

    this.error = false;

    this.validateName = function(fieldName) {
        var name = $('#' + this.formId + ' input[name=' + fieldName + ']').val();
        if($.trim(name) == "") {
            $('#' + this.formId + ' .err-name').fadeIn('slow');
            this.error = true;
        }
    };

    this.validateEmail = function(fieldName) {
        var email_compare = /^([a-z0-9_.-]+)@([da-z.-]+).([a-z.]{2,6})$/;
        var email = $('#' + this.formId + ' input[name=' + fieldName + ']').val();
        if ($.trim(email) == "") {
            $('#' + this.formId + ' .err-email').fadeIn('slow');
            this.error = true;
        } else if (!email_compare.test(email)) {
            $('#' + this.formId + ' .err-emailvld').fadeIn('slow');
            this.error = true;
        }
    };

    this.validateTime = function(fieldName) {
        var time_compare = /^[0-2][0-9]:[0-5][0-9]$/;
        var time = $('#' + this.formId + ' input[name=' + fieldName + ']').val();
        if ($.trim(time) == "") {
            $('#' + this.formId + ' .err-time').fadeIn('slow');
            this.error = true;
        } else if (!time_compare.test(time)) {
            $('#' + this.formId + ' .err-timevld').fadeIn('slow');
            this.error = true;
        }
    };

    this.validatePhone = function(fieldName) {
        var phone_compare = /^([0-9-+]{10,13})$/;

        var phone = $('#' + this.formId + ' input[name=' + fieldName + ']').val();

        if ($.trim(phone) == "") {
            $('#' + this.formId + ' .err-phone').fadeIn('slow');
            this.error = true;
        }else if (!phone_compare.test(phone)) {
            $('#' + this.formId + ' .err-phonevld').fadeIn('slow');
            this.error = true;
        }
    };

    this.validateDate = function(fieldName) {
        var date_compare = /^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/;

        var date = $('#' + this.formId + ' input[name=' + fieldName + ']').val();

        var dateObj = new Date(date);
        var curDateObj = new Date();

        if ($.trim(date) == "") {
            $('#' + this.formId + ' .err-date').fadeIn('slow');
            this.error = true;
        } else if (!date_compare.test(date)) {
            $('#' + this.formId + ' .err-datevld').fadeIn('slow');
            this.error = true;
        } else if (dateObj.getTime() < curDateObj.getTime()) {
            $('#' + this.formId + ' .err-datepast').fadeIn('slow');
            this.error = true;
        }
    };

    this.send = function() {

        var data_string = $('#' + this.formId).serialize();

        $.ajax({
            type: "POST",
            url: $('#' + this.formId).attr('action'),
            data: data_string,
            timeout: 6000,
            dataType: 'json',
            error: function(request,error) {
                if (error == "timeout") {
                    $('#' + this.formId + ' .err-timedout').slideDown('slow');
                }
                else {
                    $('#' + this.formId + ' .err-state').html('Произошла ошибка при отправке сообщения: ' + error + '');
                    $('#' + this.formId + ' .err-state').slideDown('slow');
                }
            },
            success: function(result) {
                $('#' + this.formId).slideUp('slow');
                $('#' + this.formId + ' .ajaxsuccess').slideDown('slow');

                if (result.FormResponse != undefined && result.FormResponse != null &&
                    result.FormResponse.redirect != undefined && result.FormResponse.redirect != null
                    && result.FormResponse.redirect.length > 0) {
                    document.location = result.FormResponse.redirect;
                }
            }
        });
    };

    this.onClick = function() {
        $('#' + this.formId + ' .error').fadeOut('slow');
        this.error = false;

        validationCallback(this);

        if (this.error == true) {
            $('#' + this.formId + ' .err-form').slideDown('slow');
        } else {
            this.send();
        }

        return false;
    }

    $('#' + this.formId + ' button.send').click(this.onClick.bind(this));
    $('#' + this.formId + ' .ajaxsuccess').fadeOut('slow');
}


jQuery(document).ready(function ($) { // wait until the document is ready 1
    
    var form_u193 = new sendForm('ajax-form-u193', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateTime('Time');
    });

    var form_u364 = new sendForm('ajax-form-u364', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
        form.validateDate('Date');
    });

    var form_u1993 = new sendForm('ajax-form-u1993', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
    });

    var form_u1010 = new sendForm('ajax-form-u1010', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
    });

    var form_u14799 = new sendForm('ajax-form-u14799', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
    });

    var form_u1479 = new sendForm('ajax-form-u1479', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
    });

    var form_u1616 = new sendForm('ajax-form-u1616', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
        form.validateDate('Date');
    });

    var form_u2068 = new sendForm('ajax-form-u2068', function(form) {
        form.validateName('Name');
        form.validatePhone('Phone');
        form.validateEmail('Email');
    });
});
