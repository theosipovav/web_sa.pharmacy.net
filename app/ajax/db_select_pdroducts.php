<?php


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
    $pdoQuery = "SELECT `so`.`id`, (SELECT `ss`.`name` FROM `scan_source` as `ss` WHERE `ss`.`id` = `so`.`cource_id` LIMIT 1) as `source`, `so`.`name`, `so`.`price` FROM `scan_object` as `so` WHERE `so`.`log_id` = $sLogId ORDER BY id DESC LIMIT $sLimitStart, $sLimitRange";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        //$Res["data"] = "(code: 005) Произошла ошибка при выполнение авторизации";
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
            //$htmlButton = "<button class='btn btn-secondary btn-sm' onclick=\"fView('$nId')\">ИНФО</button>";
            $htmlButton = "btn";
            $aRow = array($sName,$sSource,$nPrice,$htmlButton);
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