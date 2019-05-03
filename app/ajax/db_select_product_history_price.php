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
    $pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
    $pdoQuery = $sql  = "SELECT name FROM `scan_object` WHERE `id`='$nObjectId'";
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
    $pdoQuery = "SELECT `sl`.date as `date`, `so`.`price` FROM `scan_object` as `so`, `scan_log` as `sl` WHERE `so`.`name` like ('$sObjectName') AND `so`.`log_id` = `sl`.`id` ORDER BY `date` DESC;";
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
            //array_push($aResData, array($n, $nPrice));
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