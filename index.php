<?php
$link_main = '<span class="sr-only">(current)</span>';

ob_start();
include "app/views/header.php";
$header = ob_get_contents();
ob_end_clean();
ob_start();
include "app/views/content_main.php";
$content = ob_get_contents();
ob_end_clean();

ob_start();
include "app/views/main.php";
$site = ob_get_contents();
ob_end_clean();

print($site);