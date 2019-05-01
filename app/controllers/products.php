<?php 
$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "SELECT * FROM `scan_source`";
$pdoRes = $pdoConnection->query($pdoQuery);
if ($pdoRes == false)
{
    // Ошибка
}
$pdoResArray = $pdoRes->fetchAll();
$htmlOptionSelectSelectResourceName = "";
foreach ($pdoResArray as $item)
{ 
    /* HTML
    * <option value="1" onclick="">One</option>
    */
    $nId = $item["id"];
    $sName = $item["name"];
    $sUrl = $item["url"];
    //$html = "<button class=\"dropdown-item\" href=\"#\" onclick=\"fnViewProductsForSource('$nId','$sName', 0, 2500)\">$sName</button>";
    $html = "<option value=\"$nId\" onclick=\"fnVeiwScanDate('$nId')\">$sName</option>";
    $htmlOptionSelectSelectResourceName .= $html;
}


// Отрисовка страницы
ob_start();
include "app/views/content_products.php";
$content = ob_get_contents();
ob_end_clean();
?>