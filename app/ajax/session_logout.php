<?php
$Res = array("status"=>"Error","data"=>"");
try
{
    session_start();
    session_destroy();
    $Res = array("status"=>"Success","data"=>"");
}
catch (Exception $ex)
{
    $Res = array("status"=>"Error","data"=>$ex->getMessage());
}
print(json_encode($Res));