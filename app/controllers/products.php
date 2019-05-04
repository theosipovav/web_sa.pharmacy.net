<?php
/**
 * Контроллер страницы продукции
 * Получение данных из БД и отображение их в таблице на сайте
 */

$nSourceId = null;          // Идентификатор сканируемого ресурса
$nLogId = null;             // Идентификатор лога

if (isset($params[0])) $nSourceId = $params[0];     // Получение идентификатора сканируемого ресурса из входного параметра
if (isset($params[1])) $nLogId = $params[1];        // Получение идентификатора лога из входного параметра

// Подключение к базе данных MySql
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');

// Получение данных для списка сканируемых ресурсов 
$pdoQuery = "SELECT * FROM `scan_source`";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    // Ошибка
}
$pdoResArray = $pdoRes->fetchAll();

// Генерация HTML разметки для страницы
$htmlOptionSelectSelectResourceName = "";
foreach ($pdoResArray as $item)
{ 
    $nId = $item["id"];
    $sName = $item["name"];
    $sUrl = $item["url"];
    $html = "<option value=\"$nId\" onclick=\"fnVeiwScanDate('$nId')\">$sName</option>";
    $htmlOptionSelectSelectResourceName .= $html;
}

// Получение данных сканируемой продукции
$pdoQuery = "SELECT `so`.`id` AS `id`, `so`.`name` AS `name`, `ss`.`name` AS `source`, `so`.`price`, `sl`.`date` as `date` ";
$pdoQuery .= "FROM `scan_object` AS `so`, `scan_log` AS `sl`, `scan_source` AS `ss` ";
$pdoQuery .= "WHERE `so`.`log_id` = `sl`.id AND `so`.`source_id` = `ss`.`id` ";
if (isset($nSourceId) && isset($nLogId))
{
    // Если задан идентификатор источника и идентификатор лога,
    // то происходит поиск продукции по ним
    $pdoQuery .= "AND `so`.`source_id` = $nSourceId AND `so`.`log_id` = $nLogId;";
}
else
{
    if (isset($nSourceId))
    {
        // Если задан только идентификатор источника,
        // то происходит поиск продукции по нему и по последнему сканированию
        $pdoQuery .= "AND `so`.`source_id` = $nSourceId AND `so`.`log_id` = (SELECT `id` FROM `scan_log` WHERE `scan_log`.`source_id` = 1 ORDER BY `date` DESC LIMIT $nSourceId);";
    }
    else
    {
        // Если не задан идентификатор источника и идентификатор лога,
        // то происходит поиск продукции по последнему сканированию
        $pdoQuery .= "AND `so`.`log_id` = (SELECT `id` FROM `scan_log` ORDER BY `date` DESC LIMIT 1);";
    }
}
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    // Ошибка
}
$pdoResArray = $pdoRes->fetchAll();

// Генерация HTML разметки для наполнения таблицы
$htmlTableContent = "";
foreach ($pdoResArray as $item)
{
    $nObjectId = $item["id"];
    $sObjectName = $item["name"];
    $sSourceName = $item["source"];
    $nObjectPrice = $item["price"];
    $sLogDate = $item["date"];
    $htmlLink = "<a href='?r=product/$nObjectId' class='btn btn-secondary btn-sm'>Продробнее</a>";
    $htmlTableContent .= "<tr><td>$sObjectName</td><td>$sSourceName</td><td>$nObjectPrice</td><td>$sLogDate</td><td>$htmlLink</td></tr>";
}






// Отрисовка страницы
ob_start();
include "app/views/content_products.php";
$content = ob_get_contents();
ob_end_clean();
?>