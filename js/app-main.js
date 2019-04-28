var tableData = null;
$(document).ready(function () {
    var data = [
        [
            "System Architect",
            "Edinburgh",
            "5421",
            "2011/04/25",
            "$3,120"
        ],
        [
            "Director",
            "Edinburgh",
            "8422",
            "2011/07/25",
            "$5,300"
        ]
    ]

    tableData = $('#tableData').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "language": {
            "url": "json/DataTables-Russian.json"
        },
        data: data
    });









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
    $('#ButtonViewData').click(function (event) {
        $.ajax({
            dataType: 'json',
            url: 'app/ajax/db_select_pdroducts.php?id=10&limit1=0&limit2=24',
            success: function (res) {
                if (res.status == "Success") {
                    tableData.clear().draw();
                    tableData.rows.add(res.data).draw();
                } else {
                    alert('Возникла ошибка: ' + res.data);
                }
            },
            error: function (xhr, str) {
                alert('Критическая ошибка: ' + str);
            }
        });
    });
});



function fView(id) {
    alert(id);
}