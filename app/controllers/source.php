<?php
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "SELECT id, name, url, (SELECT date FROM `scan_log` WHERE source_id = 1 ORDER BY date DESC LIMIT 1) as date FROM scan_source";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    ob_start();
    include "app/views/error.php";
    $content = ob_get_contents();
    ob_end_clean();
}
else
{
    $ArraySourceObject = array();
    $pdoResArray = $pdoRes->fetchAll();
    foreach ($pdoResArray as $item) {
        array_push($ArraySourceObject, array("id"=>$item["id"], "name"=>$item["name"], "url"=>$item["url"], "date"=>$item["date"]));
    }
    ob_start();
    include "app/views/content_source.php";
    $content = ob_get_contents();
    ob_end_clean();
}
