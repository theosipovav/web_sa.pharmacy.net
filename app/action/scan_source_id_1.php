<?php
require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 1;
$dateCurrent = date('Y-m-d');
$nLogId = 0;

$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "INSERT INTO `scan_log` (`id`, `source_id`, `date`) VALUES (NULL, '1', '$dateCurrent');";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    print("-1");
    exit();
}

$pdoQuery = "SELECT id FROM `scan_log` ORDER BY id DESC LIMIT 1";
$pdoRes = $pdoConnection->query($pdoQuery);
$pdoResArray = $pdoRes->fetchAll();
$nLogId = $pdoResArray[0]["id"];
if ($nLogId == "")
{
    print("-1");
    exit();
}

$url_1 = 'https://366.ru/c/lekarstva/?page=';
$url_2 = '&q=%3Apriority-desc';
$aScanObjs = array();
$isParserContent = TRUE;
while ($isParserContent)
{
    @$site_parsing = file_get_html($url_1 . $n . $url_2);
    $site_parsing_tables = $site_parsing -> find('div.c-prod-item-list div.c-prod-item');
    if ($site_parsing_tables == null)
    {
        $isRead = false;
    }
    foreach($site_parsing_tables as $item) {
        $sName = trim($item->find('div.c-prod-item__title', 0)->innertext);
        $nPrice = $item -> find('span.b-price span meta', 0)->getAllAttributes()["content"];
        $sInfo = "";
        @$company = $item -> find('div.c-prod-item__manufacturer div', 0)->innertext;
        if (isset($company)) $sInfo .= "Компания: " . $company . "<br>";
        @$country = $item -> find('div.c-prod-item__manufacturer div', 1)->innertext;
        if (isset($country)) $sInfo .= "Страна: " . $country . "<br>";
        @$category = $item -> find('div.c-prod-item__manufacturer div', 2)->innertext;
        if (isset($category)) $sInfo .= "Категория: " . $category . "<br>";

        $pdoQuery = "INSERT INTO `scan_object` (`id`, `cource_id`, `name`, `price`, `info`, `log_id`) VALUES (NULL, '$nSourceId', '$sName', '$nPrice', '$sInfo', '$nLogId')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            print("-1");
            exit();
        }
    }
}
print("1");