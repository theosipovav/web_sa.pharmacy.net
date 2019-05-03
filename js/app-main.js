var tableData = null;
var alreadyFetched = {};
var data = [];
var options = null;

$(document).ready(function () {
    

    ///////////////////////////////////////////////
    // Инициализация компонента DataTable
    ///////////////////////////////////////////////
    tableData = $('#tableData').DataTable({
        //"paging": false,
        //"ordering": false,
        //"info": false,
        "language": {
            "url": "json/DataTables-Russian.json"
        }
    });
    fnViewDataDefault();

    ///////////////////////////////////////////////
    // Инициализация компонента FlotCharts
    ///////////////////////////////////////////////
    options = {
        series: {
            bars: {
                show: true,
                barWidth: 0.6,
                align: "center"
            }
        },
        xaxis: {
            mode: "categories",
            showTicks: false,
            gridLines: false
        }
    };

    

    $.plot("#placeholder", data, options);
    















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
        var nSourceId = $("#SelectResourceName").val();
        var nLogId = $("#SelectScanDate").val();
        var sUrl = 'app/ajax/db_select_products.php?source=' + nSourceId + '&log=' + nLogId;
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



    

});

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


function fnViewObjectInfo(nObjectId) {
    fnLoadObjectInfo(nObjectId);
    fnLoadDataChartsPrice(nObjectId);
}
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

function fnLoadDataChartsPrice(nObjectId) {
    
    var urlGenJson = 'app/ajax/db_select_product_history_price.php?name=' + nObjectId;
    $.ajax({
        dataType: 'json',
        type: "GET",
        url: urlGenJson,
        success: function (res) {
            if (res.status == "Success") {
                var flotchartsData = {
                    "label": "Product",
                    "data": res.data
                };
                if (!alreadyFetched[flotchartsData.label]) {
                    alert(flotchartsData.label + "\n" + flotchartsData.data);
                    alreadyFetched[flotchartsData.label] = true;
                    data.push(flotchartsData);
                }

                $.plot("#placeholder", data, options);


            }
            else{
                alert('Возникла ошибка: ' + res.data);
            }
        },
        error: function (xhr, str) {
            alert('Критическая ошибка: ' + str);
            isError = true;
        }
    });


/*
    var urlGenJson = "http://www.flotcharts.org/flot/examples/ajax/data-japan-gdp-growth.json";
    function onDataReceived(series) {
        alert(series.label);
        if (!alreadyFetched[series.label]) {
            alreadyFetched[series.label] = true;
            data.push(series);
        }
        $.plot("#placeholder", data, options);
    }
    $.ajax({
        url: urlGenJson,
        type: "GET",
        dataType: "json",
        success: onDataReceived
    });
*/
}