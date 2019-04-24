$(document).ready(function () {
    $('#ButonRegistration').click(function (event) {
        var FormRegistration = $('#FormRegistration').serialize();
        var InputPassword = $('#InputPassword');
        var InputRepeatPassword = $('#InputRepeatPassword');
        if (InputPassword.val() == InputRepeatPassword.val()) {
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'app/ajax/db_insert_user.php',
                data: FormRegistration,
                success: function (data) {
                    if (data.status == "Success") {
                        document.location.href = "./login.php";
                    } else {
                        alert('Возникла ошибка: ' + data.data);
                    }
                },
                error: function (xhr, str) {
                    alert('Критическая ошибка: ' + str);
                }
            });
        } else {
            $('#InputPassword').addClass("is-invalid");
            $('#InputRepeatPassword').addClass("is-invalid");
        }
    });
    $('#ButtonAuth').click(function (event) {
        var FormLogin = $('#FormLogin').serialize();
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: 'app/ajax/db_select_userinfo.php',
            data: FormLogin,
            success: function (data) {
                if (data.status == "Success") {
                    document.location.href = ".";
                } else {
                    alert('Возникла ошибка: ' + data.data);
                }
            },
            error: function (xhr, str) {
                alert('Критическая ошибка: ' + str);
            }
        });
    });
    $('#ButtonLogout').click(function (event) {
        $.ajax({
            dataType: 'json',
            url: 'app/ajax/session_logout.php',
            success: function (data) {
                if (data.status == "Success") {
                    document.location.href = ".";
                } else {
                    alert('Возникла ошибка: ' + data.data);
                }
            },
            error: function (xhr, str) {
                alert('Критическая ошибка: ' + str);
            }
        });
    });
    $('#ButtonUserInfo').click(function (event) {
        var SpanUserName = $('#SpanUserName');
        var SpanLogin = $('#SpanLogin');
        var SpanEmail = $('#SpanEmail');
        var SpanDateRegistration = $('#SpanDateRegistration');
        $.ajax({
            dataType: 'json',
            url: 'app/ajax/db_select_userinfofull.php',
            success: function (data) {
                if (data.status == "Success") {
                    SpanUserName.html(data.data.name);
                    SpanLogin.html(data.data.login);
                    SpanEmail.html(data.data.email);
                    SpanDateRegistration.html(data.data.date_registration);
                } else {
                    alert('Возникла ошибка: ' + data.data);
                }
            },
            error: function (xhr, str) {
                alert('Критическая ошибка: ' + str);
            }
        });


    });

});