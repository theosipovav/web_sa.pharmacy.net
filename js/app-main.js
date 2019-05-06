$(document).ready(function () {
    /**
     * Событие при клике по кнопке "Регистрация"
     */
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
                        document.location.href = "?r=login";
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
    /**
     * Событие при клике по кнопке "Войти"
     */
    $('#ButtonAuth').click(function (event) {
        var FormLogin = $('#FormLogin').serialize();
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: 'app/ajax/db_select_userinfo.php',
            data: FormLogin,
            success: function (data) {
                if (data.status == "Success") {
                    if (data.data == "-1")
                    {
                        $('#DivAuthError').css("display","block");
                        $('#inputLogin').val("");
                        $('#inputPassword').val("");
                        $('#inputLogin').addClass("is-invalid");
                        $('#inputPassword').addClass("is-invalid");
                    }
                    else
                    {
                        document.location.href = ".";
                    }
                } else {
                    alert('Возникла ошибка: ' + data.data);
                }
            },
            error: function (xhr, str) {
                alert('Критическая ошибка: ' + str);
            }
        });
    });
    /**
     * Событие при клике по кнопке "Выйти"
     */
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
    /**
     * Событие при клике по кнопке "ИМЯ_ТЕКУЩЕГО_ПОЛЬЗОВАТЕЛЯ"
     */
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
    /**
     * Событие при клике по кнопке "Показать"
     */
    $('#ButtonViewData').click(function (event) {
        var nSourceId = $("#SelectResourceName").val();
        var nLogId = $("#SelectScanDate").val();
        var sUrl = '?r=products/' + nSourceId + '/' + nLogId;
        location.href = sUrl;
    });
});
/**
 * Получение данных из таблицы логов по идентификатору сканируемого ранее ресурса, 
 * и заполнение элемента <select id="SelectScanDate"></select> полученными данным 
 */
function fnVeiwScanDate() {
    var nSourceId = $("#SelectResourceName").val();
    $.ajax({
        dataType: 'json',
        url: 'app/ajax/db_select_log.php?id=' + nSourceId,
        success: function (res) {
            if (res.status == "Success") {
                var html = "";
                var jsonDate = res.data;
                for (let i = 0; i < jsonDate.length; i++) {
                    html += "<option value=\"" + jsonDate[i][0] + "\">" + jsonDate[i][1] + "</option>";
                }
                $("#SelectScanDate").html(html);
            } else {
                alert('Возникла ошибка: ' + res.data);
            }
        },
        error: function (xhr, str) {
            alert('Критическая ошибка: ' + str);
        }
    });
}
/**
 * Получение хронологии изменения цены у продукта,
 * заполнение графика "Динамика изменения цены"
 * @param  {int} nObjectId Идентификатор продукта
 */
function fnViewObjectInfo(nObjectId) {
    fnLoadObjectInfo(nObjectId);
    fnLoadDataChartsPrice(nObjectId);
}
/**
 * Получение данных о продукте и заполнение HTML элементов полученными данными
 * @param  {int} nObjectId Идентификатор продукта
 */
function fnLoadObjectInfo(nObjectId) {
    $.ajax({
        dataType: 'json',
        url: 'app/ajax/db_select_product_info.php?id=' + nObjectId,
        success: function (res) {
            if (res.status == "Success") {
                var jsonDate = res.data;
                var sName = jsonDate[0].name;
                var nPrice = jsonDate[0].price;
                var sInfo = jsonDate[0].info;
                var sSource = jsonDate[0].source;
                $("#HTitleObjectInfo").html(sName);
                $("#DivObjectInfo").html(sInfo);
                $("#DivObjectPrice").html(nPrice);
            } else {
                alert('Возникла ошибка: ' + res.data);
            }
        },
        error: function (xhr, str) {
            alert('Критическая ошибка: ' + str);
        }
    });
}