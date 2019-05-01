<?php
$Res = array("status"=>"Error", "data"=>"");
if (!isset($_GET["name"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}
$sObjectName = $_GET["name"];
try 
{
    $pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
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
        foreach ($pdoResArray as $item)
        {
            array_push($aResData, array($item["date"], $item["price"]));
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