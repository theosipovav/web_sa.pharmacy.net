<?php
$Res = array("status"=>"Error", "data"=>"");
if (!isset($_GET["id"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}
$nObjectId= $_GET["id"];
try 
{
    $aConfig = include "../../config.php";
    $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
    $pdoQuery = "SELECT `so`.`name` AS `so.name`, `so`.`price` AS `so.price`, `so`.`info` AS `so.info`, `ss`.`name` AS `ss.name`, `sl`.`date` AS `sl.date` ";
    $pdoQuery .= "FROM `scan_object` AS `so`, `scan_source` AS `ss`, `scan_log` AS `sl` ";
    $pdoQuery .= "WHERE `so`.`id` = $nObjectId AND `so`.`cource_id` = `ss`.`id` AND `so`.`log_id` = `sl`.`id`;";
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
            $aRow = array(
                "name"=>$item["so.name"],
                "price"=>$item["so.price"],
                "info"=>$item["so.info"],
                "source"=>$item["ss.name"],
                "date"=>$item["sl.date"]
            );
            array_push($aResData, $aRow);
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