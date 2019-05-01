<?php
$Res = array("status"=>"Error", "data"=>"");

try 
{
    $pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
    $pdoQuery = "SELECT `id` FROM `scan_log` ORDER BY `date` DESC LIMIT 1";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        $Res["data"] = "Произошла ошибка при выполнение запроса";
        print(json_encode($Res));
        exit();
    }
    $pdoResArray = $pdoRes->fetchAll();
    $nLogId = $pdoResArray[0][0];
    $pdoQuery = "SELECT so.id as `id`, so.name as `name`, ss.name as 'source', so.price as `price`, sl.date as `date` ";
    $pdoQuery .= "FROM scan_object as so, scan_source as ss, scan_log as sl ";
    $pdoQuery .= "WHERE so.cource_id = ss.id AND so.log_id = sl.id AND sl.id = $nLogId ";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        $Res["data"] = "Произошла ошибка при выполнение запроса";
        print(json_encode($Res));
        exit();
    }
    $pdoResArray = $pdoRes->fetchAll();
    $aResData = array();
    foreach ($pdoResArray as $item)
    {
        $nId = $item["id"];
        $sName = $item["name"];
        $sSource = $item["source"];
        $nPrice = $item["price"];
        $sDate = $item["date"];
        $htmlButton = "<button class='btn btn-secondary btn-sm' data-toggle='modal' onclick=\"fnViewObjectInfo('$nId')\" data-target='#DivModalObjectInfo'>ИНФО</button>";
        $aRow = array($sName,$sSource,$nPrice,$sDate,$htmlButton);
        array_push($aResData, $aRow);
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