<?php
$Res = array
(
    "status"=>"Error",
    "data"=>""
);
if (!isset($_GET["source"]) || !isset($_GET["log"]))
{
    $Res["status"] = "Error";
    $Res["data"] = "Не заданы входящие параметры";
    print(json_encode($Res));
    exit();
}
$Res = array();
$sSourceId = $_GET["source"];
$sLogId = $_GET["log"];
try 
{
    $aConfig = include "../../config.php";
    $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
    $pdoQuery = "SELECT so.id as `id`, so.name as `name`, ss.name as 'source', so.price as `price`, sl.date as `date` ";
    $pdoQuery .= "FROM scan_object as so, scan_source as ss, scan_log as sl ";
    $pdoQuery .= "WHERE so.cource_id = $sSourceId AND so.log_id = $sLogId AND so.cource_id = ss.id AND so.log_id = sl.id;";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
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
            $htmlLink = "<a href='?r=product/$nId' class='btn btn-secondary btn-sm'>Продробнее</a>";
            
            $aRow = array($sName,$sSource,$nPrice,$sDate,$htmlLink);
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