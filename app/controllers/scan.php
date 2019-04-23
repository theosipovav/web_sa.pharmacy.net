<?php
require_once("../lib/parser/simple_html_dom.php");

$url1 = 'https://366.ru/c/lekarstva/?page=1&q=%3Apriority-desc';

@$site_parsing = file_get_html($url1);
$site_parsing_tables = $site_parsing -> find('div.b-bckgr--common');


var_dump($site_parsing_tables);