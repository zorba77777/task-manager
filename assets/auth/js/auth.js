$(function () {
    $('#auth').on('click', function (e) {

        e.preventDefault();

        $(".error").hide();

        let isValidLoginResult = isValidLogin();

        let isValidPasswordResult = isValidPassword();

        if ((isValidLoginResult === true) && isValidPasswordResult === true ) {
            let userLoginVal = $.trim($("#inputLogin").val());

            let userPasswordVal = $.trim($("#inputPassword").val());

            $.post(
                '/auth/checkpassword',
                {
                    login: userLoginVal,
                    password: userPasswordVal
                },
                function (data) {
                    if (data === 'correct') {
                        $('#authform').trigger('submit');
                    } else {
                        $("#inputPassword").after('<span class="error">Неверные логин/пароль</span>');
                    }
                }
            );
        }

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

