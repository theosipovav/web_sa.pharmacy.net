<?php
session_start();

$isAuth = FALSE;
if (isset($_SESSION["id"]) && isset($_SESSION["login"]) && isset($_SESSION["name"])) {
    $isAuth = TRUE;
}

if (isset($_GET["r"])) {
    $explode = explode('/', $_GET["r"]);
    $controller = $explode[0];
    $params = array();
    for ($i = 1; $i < count($explode); $i++) {
        array_push($params, $explode[$i]);
    }
} else {
    $controller = "main";
    $params = array();
}

ob_start();
include "app/views/header.php";
$header = ob_get_contents();
ob_end_clean();

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
        if ($isAuth) {
            include "app/controllers/products.php";
        } else {
            ob_start();
            include "app/views/contetn_warning_auth.php";
            $content = ob_get_contents();
            ob_end_clean();
        }
        break;
    case 'product':
        if ($isAuth) {
            include "app/controllers/product.php";
        } else {
            ob_start();
            include "app/views/contetn_warning_auth.php";
            $content = ob_get_contents();
            ob_end_clean();
        }
        break;
    case 'source':
        if ($isAuth) {
            include "app/controllers/source.php";
        } else {
            ob_start();
            include "app/views/contetn_warning_auth.php";
            $content = ob_get_contents();
            ob_end_clean();
        }
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
