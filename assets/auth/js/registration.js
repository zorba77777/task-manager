$(function () {
    $('#register').on('click', function (e) {

        e.preventDefault();

        $(".error").hide();

        let userLoginVal = $.trim($("#inputLogin").val());
        $.get(
            '/auth/checklogin',
            {
                login: userLoginVal
            },
            function (data) {
                if (data === 'login does not exist') {
                    if (isValidLogin() && isValidPassword() && isValidEmail()) {
                        $('#regform').trigger('submit');
                    }
                } else {
                    $("#inputLogin").after('<span class="error">Указанный вами логин уже существует. Пожалуйста, ' +
                        'введите другой</span>');
                }
            }
        );
    });
});


function isValidLogin() {

    let userLoginVal = $.trim($("#inputLogin").val());

    if (userLoginVal === '') {
        $("#inputLogin").after('<span class="error">Пожалуйста, введите логин.</span>');
        return false;
    } else {
        return true;
    }

}

function isValidPassword() {

    let password = $.trim($("#inputPassword").val());

    if (password === '') {
        $("#inputPassword").after('<span class="error">Пожалуйста, введите пароль.</span>');
        return false;
    } else {
        return true;
    }
}

function isValidEmail() {

    let hasError = false;
    let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    let emailAddressVal = $("#inputEmail").val();

    if (emailAddressVal === '') {
        $("#inputEmail").after('<span class="error">Пожалуйста, введите ваш e-mail.</span>');
        hasError = true;

    } else if (!emailReg.test(emailAddressVal)) {
        $("#inputEmail").after('<span class="error">Пожалуйста, введите правильный e-mail адрес.</span>');
        hasError = true;
    }

    return hasError !== true;
}

