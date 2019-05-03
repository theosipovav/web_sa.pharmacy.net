<?php
session_start();
ob_start();
include "app/views/header.php";
$header = ob_get_contents();
ob_end_clean();
if (isset($_GET["r"]))
{
    $explode = explode('/', $_GET["r"]);
    $controller = $explode[0];
    $params = array();
    for ($i=1; $i < count($explode); $i++) { 
        array_push($params, $explode[$i]);
    }
}
else 
{
    $controller = "main";
    $params = array();
}
switch ($controller) {
    case 'main':
        include "app/controllers/main.php";
        break;
    case 'login':
        include "app/controllers/login.php";
        break;
    case 'registration':
        include "app/controllers/registration.php";
        break;
    case 'products':
        include "app/controllers/products.php";
        break;
    case 'product':
        include "app/controllers/product.php";
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
include "app/views/footer.php";
$footer = ob_get_contents();
ob_end_clean();

ob_start();
include "app/views/main.php";
$site = ob_get_contents();
ob_end_clean();
print($site);