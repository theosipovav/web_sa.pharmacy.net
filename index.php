<?php
session_start();
ob_start();
include "app/views/header.php";
$header = ob_get_contents();
ob_end_clean();
if (isset($_GET["r"])) $controller = $_GET["r"];
else $controller = "main";
switch ($controller) {
    case 'main':
        include "app/controllers/main.php";
        break;
    case 'products':
        include "app/controllers/products.php";
        break;
    case 'source':
        include "app/controllers/source.php";
        break;
    case 'about':
        include "app/controllers/about.php";
        break;    
    default:
        ob_start();
        include "app/views/content_error404.php";
        $content = ob_get_contents();
        ob_end_clean();
        break;
}
ob_start();
include "app/views/main.php";
$site = ob_get_contents();
ob_end_clean();
print($site);