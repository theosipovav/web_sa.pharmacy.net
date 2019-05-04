<?php 
if (!isset($params[0]))
{
    "<h1>Ошибка!</h1>";
    exit();
}
$nObjectId = $params[0];
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "SELECT `so`.`name` AS `so.name`, `so`.`price` AS `so.price`, `so`.`info` AS `so.info`, `ss`.`name` AS `ss.name`, `sl`.`date` AS `sl.date`, `so`.`url` AS `so.url` ";
$pdoQuery .= "FROM `scan_object` AS `so`, `scan_source` AS `ss`, `scan_log` AS `sl` ";
$pdoQuery .= "WHERE `so`.`id` = $nObjectId AND `so`.`source_id` = `ss`.`id` AND `so`.`log_id` = `sl`.`id`;";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    "<h1>Ошибка!</h1>";
    exit();
}
$pdoResArray = $pdoRes->fetchAll();
$sProductName = $pdoResArray[0]["so.name"];
$nProductPrice = $pdoResArray[0]["so.price"];
$sProductInfo = $pdoResArray[0]["so.info"];
$sProductScanUrl = $pdoResArray[0]["so.url"];
$sProductSource = $pdoResArray[0]["ss.name"];
$dataProductScan = $pdoResArray[0]["sl.date"];


// Отрисовка страницы
ob_start();
include "app/views/content_product.php";
$content = ob_get_contents();
ob_end_clean();
?>