function isEmail(evt) {
    var charCode = evt.which || event.charCode || event.char;
    if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 64) && (charCode != 46) && (charCode != 95) && (charCode != 33) && (charCode > 31 && (charCode < 48 || charCode > 57)))
        return false;
    return true;
}

function isAlphaNumSpace(evt) {
    var charCode = evt.which || event.charCode || event.char;
    if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && (charCode != 64) && (charCode != 46) && (charCode != 95) && (charCode != 33) && (charCode > 31 && (charCode < 48 || charCode > 57)) && (charCode != 32))
        return false;
    return true;
}

$(function(){
    $('#submit_reg').click(function(){
        var name = $('#name_reg').val();
        var email = $('#email_reg').val();
        var password = $('#password_reg').val();
        var con_password = $('#con_password_reg').val();
        var role = $('#role_reg').val();

        var mail_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z]{2,4})+$/;
        var name_regex = /^[(A-Z)?(a-z)?(0-9)?\s*]+$/;

        if ((name == "") || (email == "") || (password == "") || (con_password == "") || (role == "")) {
            $('#reg_error').text("Don't leave the fields blank!");
            return;
        }
        else if (!mail_regex.test(email)) {
            $('#reg_error').text('Enter a valid Email!');
            return;
        }
        else if (!name_regex.test(name)) {
            $('#reg_error').text('Enter a Proper Name!');
            return;
        }
        else if (password != con_password) {
            $('#reg_error').text("Passwords doesn't match!");
            return;
        }

        $.ajax({
            url: 'backends/register.php',
            type: 'POST',
            data: {
                'name': name,
                'email': email,
                'password': password,
                'role': role
            },
            dataType: 'json',
            beforeSend: function(){
                $('#submit_reg').prop("disabled", true);
                $('#reg_error').text("");
            },
            success: function(data){
                $('#name_reg').val("");
                $('#email_reg').val("");
                $('#password_reg').val("");
                $('#con_password_reg').val("");
                var instance2 = M.Modal.getInstance($('#modal2'));
                instance2.close();
                if (data.code == "0") {
                    toggleModal('Registration Failed', data.msg);
                } else {
                    toggleModal('Success', data.msg);
                }
                $('#submit_reg').prop("disabled", false);
            },
            error: function(){
                $('#reg_error').text("Server error! Please try again.");
                $('#submit_reg').prop("disabled", false);
            }
        });
    });

    $('#login_btn').click(function(){
        var email = $('#email_login').val();
        var password = $('#password_login').val();
        var mail_regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z]{2,4})+$/;

        if ((email == "") || (password == "")) {
            $('#login_error').text("Don't leave the fields blank!");
            return;
        }
        else if (!mail_regex.test(email)) {
            $('#login_error').text('Enter a valid Email!');
            return;
        }

        $.ajax({
            url: 'backends/login.php',
            type: 'POST',
            data: {
                'email': email,
                'password': password
            },
            dataType: 'json',
            beforeSend: function(){
                $('#login_btn').prop("disabled", true);
                $('#login_error').text("");
            },
            success: function(data){
                var instance1 = M.Modal.getInstance($('#modal1'));
                instance1.close();
                if (data.code == "0") {
                    toggleModal('Login Failed', data.msg);
                } else {
                    location.reload(true);
                }
                $('#login_btn').prop("disabled", false);
            },
            error: function(){
                $('#login_error').text("Server error! Please try again.");
                $('#login_btn').prop("disabled", false);
            }
        });
    });
});
