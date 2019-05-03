<?php
ini_set('MAX_EXECUTION_TIME', '-1');

require_once("../lib/parser/simple_html_dom.php");

$nSourceId = 3;
$dateCurrent = date('Y-m-d H:i:s');
$nLogId = 0;

/*
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "INSERT INTO `scan_log` (`id`, `source_id`, `date`) VALUES (NULL, '$nSourceId', '$dateCurrent');";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    print("-1");
    exit();
}
*/
/*
$pdoQuery = "SELECT id FROM `scan_log` ORDER BY id DESC LIMIT 1";
$pdoRes = $pdoConnection->query($pdoQuery);
$pdoResArray = $pdoRes->fetchAll();
$nLogId = $pdoResArray[0]["id"];
if ($nLogId == "")
{
    print("-1");
    exit();
}
*/
$nPageNum = 1;
$count = 0;
while (true)
{
    $sUrlParsing = 'https://vseapteki.ru/catalog/39-lekarstva_i_bad/?page=' . $nPageNum;
    @$htmlPage = file_get_html($sUrlParsing);
    $aHtmlArticle = $htmlPage-> find('article');
    
    foreach ($aHtmlArticle as $article) 
    {
        $sName = $article->find('header',0)->find('h3',0)->plaintext;

        $nPrice = $article->find('footer',0)->find('div',0)->plaintext;
        
        
        $sInfo = "";
        @$sInfo_1 = $article->find('header',0)->find('div',0)->plaintext;
        if (isset($sInfo_1)) $sInfo .= trim($sInfo_1) . "<br>";
        @$sInfo_2 = $article->find('header',0)->find('div',1)->plaintext;
        if (isset($sInfo_2)) $sInfo .= trim($sInfo_2) . "<br>";


       


        echo "$sName<br>Цена: $nPrice<br>$sInfo<hr>";
    }







    exit();
    $htmlCatalogList = $htmlPage -> find('div.catalog-list', 0);
    $aHtmlArticle = $htmlCatalogList-> find('article');
    foreach ($aHtmlArticle as $article) 
    {
        $sName = $article->find('span.h2-style',0)->children(0)->plaintext;
        $nPriceData = $article->find('div.price',0);
        if (isset($nPriceData))
        {
            $nPrice = $nPriceData->find('span',0)->plaintext;
        }
        else
        {
            $nPrice = "";
        }
        $sInfo = "";
        @$sInfo_1 = $article->find('div.item_additional-info',0)->children(0)->plaintext;
        if (isset($sInfo_1)) $sInfo .= trim($sInfo_1) . "<br>";
        @$sInfo_2 = $article->find('div.item_additional-info',0)->children(1)->plaintext;
        if (isset($sInfo_2)) $sInfo .= trim($sInfo_2) . "<br>";
        @$sInfo_3 = $article->find('div.preparation',0)->plaintext;
        if (isset($sInfo_3)) $sInfo .= trim($sInfo_3) . "<br>";
        $sName = trim($sName);
        $nPrice = trim($nPrice);
        $sInfo = trim($sInfo);
        $sName = str_replace("'", "*", $sName);
        $nPrice = str_replace("'", "*", $nPrice);
        $sInfo = str_replace("'", "*", $sInfo);
        $pdoQuery = "INSERT INTO `scan_object` (`id`, `cource_id`, `name`, `price`, `info`, `log_id`) VALUES (NULL, '$nSourceId', '$sName', '$nPrice', '$sInfo', '$nLogId')";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            print("-1");
            exit();
        }
        else
        {
            $count++;
        }
    }
    break;
}
print($count);