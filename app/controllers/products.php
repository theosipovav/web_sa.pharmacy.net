<?php 




ob_start();
include "app/views/content_products.php";
$content = ob_get_contents();
ob_end_clean();
?>