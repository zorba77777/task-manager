$(function () {

    $('#save-task-new').on('click', function () {

        $(".error").hide();

        let isValidUserNameResult = isValidUserName();

        let isValidEmailResult = isValidEmail();

        let isValidTaskTextResult = isValidTaskText();

        return !!((isValidUserNameResult === true) && (isValidEmailResult === true) && (isValidTaskTextResult === true));
    });

    $('.task-done').on('click', function () {
        let $this = $(this);
        let done = '';
        if ($this.text() === 'Не выполнено') {
            done = 1;
        } else {
            done = 0;
        }
        let id = $this.attr('id').split('-')[1];
        $.get(
            '/main/done',
            {
                id: id,
                done: done
            },
            function (data) {
                if (data === 'success') {
                    (done === 1) ? $this.text('Выполнено') : $this.text('Не выполнено');
                }
            }
        );
    });

    $('.task-content').on('click', function () {

        let id = $(this).attr('id').split('-')[1];
        $('#save-task-edit').attr('data-id', id);


        $('#edit-task').modal();
        $('#task-text-edit').val($(this).text());

        $("#task-text-edit").append('<div id="temp-storage" hidden></div>');
        $('#temp-storage').text($(this).text());

    });

    $('#save-task-edit').on('click', function () {

        if ($('#temp-storage').text() !==  $('#task-text-edit').val()) {

            let id = $(this).attr('data-id');

            $.post(
                '/main/edit',
                {
                    id: id,
                    content: $('#task-text-edit').val()
                },
                function (data) {
                    if (data === 'success') {
                        $('#content' + '-' + id).text($('#task-text-edit').val());
                        $('#content' + '-' + id).addClass('edited');
                        $('#temp-storage').remove();
                    } else if (data === 'Admin privileges needed') {
                        alert('Авторизуйтесь как администратор чтобы вносить правки');
                    }
                }
            );
        }



    });

    $('.select-css').on('change', function () {
        let sortField = $(this).attr('id').split('-')[1];

        let sortOrder= '';
        if ($(this).val() === 'Сортировать по возрастанию') {
            sortOrder = 'asc';
        } else if ($(this).val() === 'Сортировать по убыванию') {
            sortOrder = 'desc';
        } else if ($(this).val() === 'Не сортировать') {
            sortOrder = '';
        }

        let pageNumber;
        if (location.href.indexOf('?') >= 0) {
            pageNumber = getUrlParameter('page');
        } else {
            pageNumber = 1;
        }

        let url = 'http://' + location.host + '/main/index?page=' + pageNumber;
        if (sortOrder !== '') {
            location.href = url + '&sortfield=' + sortField + '&sortorder='+sortOrder;
        } else {
            location.href = url;
        }


    });


    if (getCookie('task_just_created') == true) {
        alert('Задача добавлена');
        setCookie('task_just_created', false, 1)
    }

});


function isValidUserName() {

    let userNameVal = $.trim($("#user-name").val());

    if (userNameVal === '') {
        $("#user-name").after('<span class="error">Пожалуйста, введите имя пользователя.</span>');
        return false;
    } else {
        return true;
    }
}

function isValidEmail() {

    let hasError = false;
    let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    let emailAddressVal = $("#email").val();

    if (emailAddressVal === '') {
        $("#email").after('<span class="error">Пожалуйста, введите ваш e-mail.</span>');
        hasError = true;

    } else if (!emailReg.test(emailAddressVal)) {
        $("#email").after('<span class="error">Пожалуйста, введите правильный e-mail адрес.</span>');
        hasError = true;
    }

    return hasError !== true;

}

function isValidTaskText() {

    let userNameVal = $.trim($("#task-text-new").val());

    if (userNameVal === '') {
        $("#task-text-new").after('<span class="error">Пожалуйста, введите текст задачи.</span>');
        return false;
    } else {
        return true;
    }
}

function setCookie(cookieName, cookieValue, expireDays) {
    let date = new Date();
    date.setTime(date.getTime() + (expireDays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + date.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

function getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getUrlParameter(sParam) {
    let sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
}

