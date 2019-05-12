<?php
$Res = array("status"=>"", "data"=>"");
if (!isset($_GET["name"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}
$nObjectId = $_GET["name"];
try 
{
    $aConfig = include "../../config.php";
    $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
    $pdoQuery = $sql  = "SELECT name, info FROM `scan_object` WHERE `id`='$nObjectId'";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        $Res["data"] = "Произошла ошибка при выполнение запроса";
        print(json_encode($Res));
        exit();
    }
    $pdoResArray = $pdoRes->fetchAll();
    $sObjectName = $pdoResArray[0]["name"];
    $sObjectInfo = $pdoResArray[0]["info"];
    //$pdoQuery = "SELECT `sl`.date as `date`, `so`.`price` FROM `scan_object` as `so`, `scan_log` as `sl` WHERE `so`.`name` like ('$sObjectName') AND `so`.`log_id` = `sl`.`id` ORDER BY `date` DESC;";
    $pdoQuery = "SELECT `sl`.date as `date`, `so`.`price` FROM `scan_object` as `so`, `scan_log` as `sl` WHERE `so`.`name` LIKE('$sObjectName') AND `so`.`info` LIKE('$sObjectInfo') AND `so`.`log_id` = `sl`.`id` ORDER BY `date` DESC;";
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
        $n = 0;
        foreach ($pdoResArray as $item)
        {
            $dateScan = $item["date"];
            $nPrice = $item["price"];
            array_push($aResData, array("$dateScan", $nPrice));
            $n++;
        }
        $Res["status"] = "Success";
        $Res["data"] = $aResData;
    }
    print(json_encode($Res));
}
catch (PDOException $ex)
{
    $Res["status"] = "Error";
    $Res["data"] = $ex->getMessage();
    print(json_encode($Res));
}