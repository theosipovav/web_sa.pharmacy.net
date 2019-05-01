var tableData = null;
$(document).ready(function () {
    // Инициализация компонента DataTable - Таблица для работы с БД
    tableData = $('#tableData').DataTable({
        //"paging": false,
        //"ordering": false,
        //"info": false,
        "language": {
            "url": "json/DataTables-Russian.json"
        }
    });
    fnViewDataDefault();





    // Graph Toggle ############################################
    $('#graph-bars').hide();

    $('#lines').on('click', function (e) {
        $('#bars').removeClass('active');
        $('#graph-bars').fadeOut();
        $(this).addClass('active');
        $('#graph-lines').fadeIn();
        e.preventDefault();
    });

    $('#bars').on('click', function (e) {
        $('#lines').removeClass('active');
        $('#graph-lines').fadeOut();
        $(this).addClass('active');
        $('#graph-bars').fadeIn().removeClass('hidden');
        e.preventDefault();
    });

    

    var previousPoint = null;

    $('#graph-lines, #graph-bars').bind('plothover', function (event, pos, item) {
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                $('#tooltip').remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];
                showTooltip(item.pageX, item.pageY, y + ' visitors at ' + x + '.00h');
            }
        } else {
            $('#tooltip').remove();
            previousPoint = null;
        }
    });

});












// Событие при клике по кнопке "Регистрация"
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
// Событие при клике по кнопке "Войти"
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
// Событие при клике по кнопке "Выйти"
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
// Событие при клике по кнопке "имя_пользователя"
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
// Событие при клике по кнопке "Показать"
$('#ButtonViewData').click(function (event) {
    var nSourceId = $("#SelectResourceName").val();
    var nLogId = $("#SelectScanDate").val();
    var sUrl = 'app/ajax/db_select_products_v2.php?source=' + nSourceId + '&log=' + nLogId;
    tableData.clear().draw();
    $('#ButtonViewData').html("Загрузка...")
    $.ajax({
        dataType: 'json',
        url: sUrl,
        success: function (res) {
            if (res.status == "Success") {
                tableData.rows.add(res.data).draw();
            } else {
                alert('Возникла ошибка: ' + res.data);
            }
            $('#ButtonViewData').html("Показать")
        },
        error: function (xhr, str) {
            alert('Критическая ошибка: ' + str);
            $('#ButtonViewData').html("Показать")
        }
    });
});



/**
 * Загрузка продукции по Id ресурса
 * @param  {int} nSourceId
 * @param  {string} sSourceName
 * @param  {int} nLimitStart
 * @param  {int} nLimitRange
 */
function fnViewProductsForSource(nSourceId, sSourceName, nLimitStart, nLimitRange) {
    $.ajax({
        dataType: 'json',
        url: 'app/ajax/db_select_pdroducts_for_source.php?id=' + nSourceId + '&limit1=' + nLimitStart + '&limit2=' + nLimitRange,
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
}


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
 * При нажатие по кнопке "ИНФО"
 * Показать всплывающие окно "Подробно о товаре" и загрузить данные из БД для отслеживание истории историии изменения цен
 * @param  {n} nObjectId
 */
function fnViewObjectInfo(nObjectId) {
    var sName = "";
    var nPrice = "";
    var sInfo = "";
    var sSource = "";
    $.ajax({
        dataType: 'json',
        url: 'app/ajax/db_select_product_info.php?id=' + nObjectId,
        success: function (res) {
            if (res.status == "Success") {
                var jsonDate = res.data;
                sName = jsonDate[0].name;
                nPrice = jsonDate[0].price;
                sInfo = jsonDate[0].info;
                sSource = jsonDate[0].source;
                $("#HTitleObjectInfo").html(sName);
                $("#DivObjectInfo").html(sInfo);
                $("#DivObjectPrice").html(nPrice);
                $.ajax({
                    dataType: 'json',
                    url: 'app/ajax/db_select_product_history_price.php?name=' + sName,
                    success: function (res) {
                        fnLoadDataChartsPrice(res.data);
                    },
                    error: function (xhr, str) {
                        alert('Критическая ошибка: ' + str);
                        isError = true;
                    }
                });
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
 * Загрузка данных в таблицу отображение (по умолчанию)
 */
function fnViewDataDefault() {
    $.ajax({
        dataType: 'json',
        url: 'app/ajax/db_select_products_default.php',
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
}
/**
 * Загрузка данных в диаграмму изменения цен
 * @param  {json} data
 */
function fnLoadDataChartsPrice(data) {
    var graphData = [{
        data: data,
        color: '#77b7c5',
        points: {
            radius: 4,
            fillColor: '#77b7c5'
        }
    }];
    $.plot($('#graph-lines'), graphData, {
        series: {
            points: {
                show: true,
                radius: 5
            },
            lines: {
                show: true
            },
            shadowSize: 0
        },
        grid: {
            color: '#646464',
            borderColor: 'transparent',
            borderWidth: 20,
            hoverable: true
        },
        xaxis: {
            tickColor: 'transparent',
            tickDecimals: 2
        },
        yaxis: {
            tickSize: 1000
        }
    });
    $.plot($('#graph-bars'), graphData, {
        series: {
            bars: {
                show: true,
                barWidth: .9,
                align: 'center'
            },
            shadowSize: 0
        },
        grid: {
            color: '#646464',
            borderColor: 'transparent',
            borderWidth: 20,
            hoverable: true
        },
        xaxis: {
            tickColor: 'transparent',
            tickDecimals: 2
        },
        yaxis: {
            tickSize: 1000
        }
    });
    
}
// Tooltip #################################################
function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        top: y - 16,
        left: x + 20
    }).appendTo('#graph-wrapper').fadeIn();
}