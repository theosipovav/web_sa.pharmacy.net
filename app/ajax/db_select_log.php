<?php
$Res = array("status"=>"Error", "data"=>"");
if (!isset($_GET["id"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}
$nSourceId = $_GET["id"];
try 
{
    $aConfig = include "../../config.php";
    $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
    $pdoQuery = "SELECT id, date FROM `scan_log` WHERE `source_id` = $nSourceId";
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
            $sDate = $item["date"];
            $aRow = array($nId,$sDate);
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