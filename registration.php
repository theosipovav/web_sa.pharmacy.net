<?php

ob_start();
include "app/views/header.php";
$header = ob_get_contents();
ob_end_clean();
ob_start();
include "app/views/content_registration.php";
$content = ob_get_contents();
ob_end_clean();

ob_start();
include "app/views/main.php";
$site = ob_get_contents();
ob_end_clean();

print($site);