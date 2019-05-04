<?php
ini_set('MAX_EXECUTION_TIME', '-1');
require_once("../lib/parser/simple_html_dom.php");
$nSourceId = 1;
$dateCurrent = date('Y-m-d H:i:s');
$nLogId = 0;
$nLogCount = 0;
$sLogMsg = "";
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "INSERT INTO `scan_log` (`id`, `source_id`, `date`) VALUES (NULL, '$nSourceId', '$dateCurrent');";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    $sLogMsg = "Ошибка при обработке запроса: " . $pdoQuery;
    print("-1");
    exit();
}
$pdoQuery = "SELECT id FROM `scan_log` ORDER BY id DESC LIMIT 1";
$pdoRes = $pdoConnection->query($pdoQuery);
$pdoResArray = $pdoRes->fetchAll();
$nLogId = $pdoResArray[0]["id"];
if ($nLogId == "")
{
    $sLogMsg = "Ошибка при обработке запроса: " . $pdoQuery;
    print("-1");
    exit();
}
$nPageNum = 0;
$nLogCount = 0;
while (true)
{
    $sUrlParsing = 'https://366.ru/c/lekarstva/?page=' . $nPageNum . '&q=%3Apriority-desc';
    @$htmlPage = file_get_html($sUrlParsing);
    if ($htmlPage == null)
    {
        break;
    }
    $htmlPage_tables = $htmlPage -> find('div.c-prod-item-list div.c-prod-item');
    if ($htmlPage_tables == null)
    {
        break;
    }
    else
    {
        $nPageNum++;
    }
    foreach($htmlPage_tables as $item) {
        $sObjectName = $item->find('div.c-prod-item__title', 0)->innertext;
        if (isset($sObjectName))
        {
            $sObjectName = trim($sObjectName);
            $sObjectName = str_replace("'", "*", $sObjectName);
        }
        else
        {
            continue;
        }
        @$nObjectPrice = $item->find('span.b-price span meta', 0)->getAllAttributes()["content"];
        if (isset($nObjectPrice))
        {
            $nObjectPrice = preg_replace("/[^a-z\d]/",' ', $nObjectPrice);
            $nObjectPrice = trim($nObjectPrice);
            $nObjectPrice = str_replace("'", "*", $nObjectPrice);
        }
        else
        {
            continue;
        }
        $sObjectInfo = "";
        @$substr = $item -> find('div.c-prod-item__manufacturer div', 0)->innertext;
        if (isset($substr))
        {
            $sObjectInfo .= "Компания: " . $substr . "<br>";
            $substr = null;
        }
        @$substr = $item -> find('div.c-prod-item__manufacturer div', 1)->innertext;
        if (isset($substr))
        {
            $sObjectInfo .= "Страна: " . $substr . "<br>";
            $substr = null;
        }
        @$substr = $item -> find('div.c-prod-item__manufacturer div', 2)->innertext;
        if (isset($substr))
        {
            $sObjectInfo .= "Категория: " . $substr . "<br>";
            $substr = null;
        }
        $sObjectInfo = str_replace("'", "*", $sObjectInfo);

        $pdoQuery = "INSERT INTO `scan_object` (`id`, `cource_id`, `name`, `price`, `info`, `log_id`, `url`) VALUES (NULL, '$nSourceId', '$sObjectName', '$nObjectPrice', '$sObjectInfo', '$nLogId', '$sUrlParsing')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            $sLogMsg = "Ошибка при обработке запроса: " . $pdoQuery;
            continue;
        }
        else
        {
            $nLogCount++;
        }
    }
}
if ($sLogMsg == "")
{
    $sLogMsg = "Ошибок нет";
}
$pdoQuery = "UPDATE `scan_log` SET `count` = '$nLogCount', `msg` = '$sLogMsg' WHERE `scan_log`.`id` = $nLogId";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    print("-1");
    exit();
}
print($nLogCount);