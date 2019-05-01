<?php
$Res = array("status"=>"Error", "data"=>"");
if (!isset($_GET["id"]) || !isset($_GET["limit1"]) || !isset($_GET["limit2"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}

$sLogId = $_GET["id"];
$sLimitStart = $_GET["limit1"];
$sLimitRange = $_GET["limit2"];

try 
{
    $pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
    $pdoQuery = "SELECT so.id as `id`, ss.name as `source`, so.name as `name`, so.price as `price`, sl.date as `date` FROM scan_object as so, scan_source as ss, scan_log as sl WHERE so.cource_id = ss.id AND so.log_id = sl.id AND ss.id = $sLogId ORDER BY `date` DESC";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        $Res["data"] = "Произошла ошибка при выполнение запроса";
    }
    else
    {
        $pdoResArray = $pdoRes->fetchAll();
        $aResData = array();
        foreach ($pdoResArray as $item)
        {
            $nId = $item["id"];
            $sName = $item["name"];
            $sSource = $item["source"];
            $nPrice = $item["price"];
            $sDate = $item["date"];
            $htmlButton = "<button class='btn btn-secondary btn-sm' onclick=\"fView('$nId')\">ИНФО</button>";
            $aRow = array($sName,$sSource,$nPrice,$sDate,$htmlButton);
            array_push($aResData, $aRow);
        }
    }
    $Res["status"] = "Success";
    $Res["data"] = $aResData;
    print(json_encode($Res));
}
catch (PDOException $ex)
{
    $Res["status"] = "Error";
    $Res["data"] = $ex->getMessage();
    print(json_encode($Res));
}