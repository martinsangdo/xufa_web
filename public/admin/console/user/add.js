//BootstrapValidator

$(function () {
    $('.select2').select2();
    //form validation
    $("#frm_add").bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'Username không được để trống!'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'Username phải nhiều hơn 6 ký tự và không lớn hơn 30 ký tự!'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'Username hợp lệ chỉ chứa ký tự, chữ số và dấu gạch dưới!'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Email không được để trống!',
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/,
                        message: 'Email hợp lệ chỉ chứa ký tự, chữ số và dấu gạch dưới!'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Mật khẩu không được để trống!'
                    },
                    stringLength: {
                        min: 6,
                        message: 'Mật khẩu phải nhiều hơn 6 ký tự!'
                    }
                }
            },
            re_password: {
                validators: {
                    notEmpty: {
                        message: 'Xác nhận mật khẩu không được để trống!'
                    },
                    identical: {
                        field: 'password',
                        message: 'Xác nhận mật khẩu không trùng khớp!'
                    }
                }
            },
            fullname: {
                validators: {
                    notEmpty: {
                        message: 'Họ và tên không được để trống!'
                    },
                    stringLength: {
                        min: 6,
                        message: 'Họ và tên phải nhiều hơn 6 ký tự!'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();

        $('#btn_add').button('loading');
        // Get the form instance
        var $form = $(e.target);
        var formData = new FormData($("#frm_add")[0]);
        formData = new FormData($(this)[0]);
        // Get the BootstrapValidator instance
        var bv = $form.data('bootstrapValidator');
        // Use Ajax to submit form data
        var url = '/_api/admin/user/add_new_user';
        // formData.append('content_product', CKEDITOR.instances['write_content'].getData());
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            async: false,
            success: function (res) {
                if(res.message == CONSTANT.OK_CODE){
                    alert("Thêm mới thành công!");
                    window.location = '/_admin/user/listing';
                }
                else{
                    if(res.message == CONSTANT.USER_IS_EXISTED) {
                        alert('Username đã tồn tại!');
                    }
                    else if (res.message == CONSTANT.EMAIL_IS_EXISTED) {
                        alert('Email đã tồn tại!');
                    }
                }
                $('#btn_add').button('reset');
            },
            error: function () {
                alert('Vui lòng thử lại !');
                $('#btn_add').button('reset');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }); //end bootstrapValidator

    $('#btn_add').click(function() {
        $('#frm_add').bootstrapValidator('validate');
    });

    // Format the phone number as the user types it
    document.getElementById('phone').addEventListener('keyup',function(evt){
        var phoneNumber = document.getElementById('phone');
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        phoneNumber.value = phoneFormat(phoneNumber.value);
    });

    // A function to format text to look like a phone number
    function phoneFormat(input){
        // Strip all characters from the input except digits
        input = input.replace(/\D/g,'');

        // Trim the remaining input to ten characters, to preserve phone number format
        input = input.substring(0,10);

        // Based upon the length of the string, we add formatting as necessary
        var size = input.length;
        if(size == 0){
            input = input;
        }else if(size < 4){
            input = '('+input;
        }else if(size < 7){
            input = '('+input.substring(0,3)+') '+input.substring(3,6);
        }else{
            input = '('+input.substring(0,3)+') '+input.substring(3,6)+' - '+input.substring(6,10);
        }
        return input;
    }

    function loadImage(input) {
        /*
         * Todo: check file size when upload
         */
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = '<img width="100px" src="' + e.target.result + '" />';
                $('#img_preview').html(img);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#img_src").change(function() {
        loadImage(this);
    });

    // Format the phone number as the user types it
    document.getElementById('phone').addEventListener('keyup',function(evt){
        var phoneNumber = document.getElementById('phone');
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        phoneNumber.value = phoneFormat(phoneNumber.value);
    });

    // We need to manually format the phone number on page load
    document.getElementById('phone').value = phoneFormat(document.getElementById('phone').value);

    // A function to determine if the pressed key is an integer
    function numberPressed(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if(charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 36 || charCode > 40)){
            return false;
        }
        return true;
    }

    // A function to format text to look like a phone number
    function phoneFormat(input){
        // Strip all characters from the input except digits
        input = input.replace(/\D/g,'');

        // Trim the remaining input to ten characters, to preserve phone number format
        input = input.substring(0,10);

        // Based upon the length of the string, we add formatting as necessary
        var size = input.length;
        if(size == 0){
            input = input;
        }else if(size < 4){
            input = '('+input;
        }else if(size < 7){
            input = '('+input.substring(0,3)+') '+input.substring(3,6);
        }else{
            input = '('+input.substring(0,3)+') '+input.substring(3,6)+' - '+input.substring(6,10);
        }
        return input;
    }
});