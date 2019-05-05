var tableData = null;

$(document).ready(function () {
    ///////////////////////////////////////////////
    // Инициализация компонента DataTable
    ///////////////////////////////////////////////
    tableData = $('#TableProducts').DataTable({
        "language": {
            "url": "json/DataTables-Russian.json"
        }
    });
    fnVeiwScanDate();
});

window.onload = function()
{
    $("#DivDivTableLoad").css("display", "none");
    $("#DivTableProducts").css("display", "block");
}
