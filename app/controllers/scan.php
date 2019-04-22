<?php
require_once("../lib/parser/simple_html_dom.php");
$url1 = 'https://366.ru/c/lekarstva/?page=0&q=:priority-desc';

@$site_parsing = file_get_html($url1);
$site_parsing_tables = $site_parsing -> find('div.b-bckgr--common div.c-prod-item-list div.c-prod-item');

print_r($site_parsing_tables);