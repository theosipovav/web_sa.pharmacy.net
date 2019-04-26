<?php
require_once("../lib/parser/simple_html_dom.php");

$url = 'https://366.ru/c/lekarstva/?page=0&q=%3Apriority-desc';
$url_1 = 'https://366.ru/c/lekarstva/?page=';
$url_2 = '&q=%3Apriority-desc';

$aScanObjs = array();

$isRead = TRUE;
$n = 0;
while ($isRead) {
    @$site_parsing = file_get_html($url_1 . $n . $url_2);
    $site_parsing_tables = $site_parsing -> find('div.c-prod-item-list div.c-prod-item');

    if ($site_parsing_tables == null)
    {
        $isRead = false;
        echo "<h1>все</h1>";
    }



    foreach($site_parsing_tables as $item) {
        $name = trim($item->find('div.c-prod-item__title', 0)->innertext);
        $price = $item -> find('span.b-price span meta', 0)->getAllAttributes()["content"];
        $info = "";
        @$company = $item -> find('div.c-prod-item__manufacturer div', 0)->innertext;
        if (isset($company)) $info .= "Компания: " . $company . "<br>";
        @$country = $item -> find('div.c-prod-item__manufacturer div', 1)->innertext;
        if (isset($country)) $info .= "Страна: " . $country . "<br>";
        @$category = $item -> find('div.c-prod-item__manufacturer div', 2)->innertext;
        if (isset($category)) $info .= "Категория: " . $category . "<br>";
        
        $row = array
        (
            "cource_id" => "1",
            "name" => $name,
            "price" => $price,
            "info" => $info
        );
        array_push($aScanObjs, $row);
    }

    $n++;
   
}


echo "<hr>".count($aScanObjs);