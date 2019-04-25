<?php
session_start();
$Res = array("status"=>"Error","data"=>"");
try
{
    if (isset($_SESSION["id"]) && isset($_SESSION["login"]) && isset($_SESSION["name"]))
    {
        $id = $_SESSION["id"];
        $login = $_SESSION["login"];
        $name = $_SESSION["name"];
        $pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
        $pdoQuery = "SELECT `user`.`login`, `user_card`.`name`, `user_card`.`email`, `user_card`.`date_registration` FROM `user`, `user_card` WHERE `user`.`id` like ('$id') AND `user`.`login` like ('$login') AND `user_card`.`name` like ('$name'); ";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            $Res["status"] = "Error";
            $Res["data"] = "(code: 008) Произошла ошибка при получение данных";
        }
        else
        {
            $pdoResArray = $pdoRes->fetchAll();
            if (count($pdoResArray) == 0)
            {
                $Res["status"] = "Error";
                $Res["data"] = "(code: 001) Произошла ошибка при получение данных";
                $Res["data"] = $pdoQuery;

            }
            else
            {
                $Res["status"] = "Success";
                $Res["data"] = $pdoResArray[0];
            }
        }
    }
    else
    {
        $Res["status"] = "Error";
        $Res["data"] = "(code: 009) Произошла ошибка при получение данных";
    }
}
catch (Exception $ex)
{
    $Res = array("status"=>"Error","data"=>$ex->getMessage());
}
print(json_encode($Res));