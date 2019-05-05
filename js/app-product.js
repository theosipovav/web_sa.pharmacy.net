var alreadyFetched = {};
var data = [];
var FlotChartsOptions = null;

$(document).ready(function () {
    ///////////////////////////////////////////////
    // Инициализация компонента FlotCharts
    ///////////////////////////////////////////////
    FlotChartsOptions = {
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
            mode: "categories",
            showTicks: false,
            gridLines: false,
            labelWidth: 250
        },
        yaxis: {
            tickSize: 100
        }
    };
    $.plot("#placeholder", data, FlotChartsOptions);

    var sSiteRoute = window.location.search.replace('?r=', '');
    var aSiteParams = sSiteRoute.split('/');
    var nObjectId = aSiteParams[1];
    fnLoadDataChartsPrice(nObjectId);


});

/**
 * Заполнение графика "Динамика изменения цены" данными о хронологии цены продукта
 * @param  {int} nObjectId Идентификатор продукта
 */
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
                    alreadyFetched[flotchartsData.label] = true;
                    data.push(flotchartsData);
                }
                $.plot("#placeholder", data, FlotChartsOptions);
            } else {
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
            $.plot("#placeholder", data, FlotChartsOptions);
        }
        $.ajax({
            url: urlGenJson,
            type: "GET",
            dataType: "json",
            success: onDataReceived
        });
    */
}