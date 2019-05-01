<?php
ini_set('MAX_EXECUTION_TIME', '-1');

require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 1;
$dateCurrent = date('Y-m-d H:i:s');
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
$n = 0;
$count = 0;
while (true)
{
    $sUrlParsing = $url_1 . $n . $url_2;
    @$site_parsing = file_get_html($sUrlParsing);
    $site_parsing_tables = $site_parsing -> find('div.c-prod-item-list div.c-prod-item');
    if ($site_parsing_tables == null)
    {
        break;
    }
    else
    {
        $n++;
    }
    foreach($site_parsing_tables as $item) {
        $sName = trim($item->find('div.c-prod-item__title', 0)->innertext);
        $nPrice = $item -> find('span.b-price span meta', 0)->getAllAttributes()["content"];
        @$company = $item -> find('div.c-prod-item__manufacturer div', 0)->innertext;
        $sInfo = "";
        if (isset($company)) $sInfo .= "Компания: " . $company . "<br>";
        @$country = $item -> find('div.c-prod-item__manufacturer div', 1)->innertext;
        if (isset($country)) $sInfo .= "Страна: " . $country . "<br>";
        @$category = $item -> find('div.c-prod-item__manufacturer div', 2)->innertext;
        if (isset($category)) $sInfo .= "Категория: " . $category . "<br>";
        $sName = str_replace("'", "*", $sName);
        $nPrice = str_replace("'", "*", $nPrice);
        $sInfo = str_replace("'", "*", $sInfo);
        $pdoQuery = "INSERT INTO `scan_object` (`id`, `cource_id`, `name`, `price`, `info`, `log_id`) VALUES (NULL, '$nSourceId', '$sName', '$nPrice', '$sInfo', '$nLogId')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            echo "Ошибка<hr>$pdoQuery";
            exit();
        }
        else
        {
            $count++;
        }
    }
}
echo "Выполнено !";
echo "<h1>$count</h1";