<?php
require_once("../lib/parser/simple_html_dom.php");

$url1 = 'https://366.ru/c/lekarstva/?page=0&q=%3Apriority-desc';

@$site_parsing = file_get_html($url1);
$site_parsing_tables = $site_parsing -> find('div.c-prod-item-list div.c-prod-item');

foreach($site_parsing_tables as $item) {
    $a1 = $item -> find('h3.c-prod-item__title', 0)->plaintext;
    $a2 = $item -> find('div.c-prod-item__manufacturer div', 0)->plaintext;
    $a3 = $item -> find('div.c-prod-item__manufacturer div', 1)->plaintext;
    $a4 = $item -> find('div.c-prod-item__manufacturer div', 2)->plaintext;

    
    echo "Наименование: ". $a1;
    echo "<br>";
    echo "Компания: " . $a2;
    echo "<br>";
    echo "Страна: " . $a3;
    echo "<br>";
    echo "Категория: " . $a4;

    echo "<hr/>";
    break;
}
