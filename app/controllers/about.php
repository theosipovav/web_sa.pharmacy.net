<?php 
ob_start();
include "app/views/content_about.php";
$content = ob_get_contents();
ob_end_clean();
?>