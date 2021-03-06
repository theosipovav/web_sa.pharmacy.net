<?php
session_start();
$Res = array
(
    "status"=>"Error",
    "data"=>""
);
try 
{
    if (!isset($_POST["login"]) || !isset($_POST["password"]))
    {
        $Res["status"] = "Error";
        $Res["data"] = "(code: 007) Произошла ошибка при выполнение авторизации";
    }
    else
    {
        $login = $_POST["login"];
        $password = md5($_POST["password"]);
        $aConfig = include "../../config.php";
        $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
        $pdoQuery = "SELECT `user`.`id`, `user`.`login`,`user_card`.`name` FROM `user`, `user_card` WHERE `login` like ('$login') and `password` like ('$password') AND `user`.`id`=`user_card`.`user_id`";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            $Res["status"] = "Error";
            $Res["data"] = "(code: 005) Произошла ошибка при выполнение авторизации";
        }
        else
        {
            $pdoResArray = $pdoRes->fetchAll();
            if (count($pdoResArray) == 0)
            {
                $Res["status"] = "Success";
                $Res["data"] = "-1";

            }
            else
            {
                $Res["status"] = "Success";
                $Res["data"] = $pdoResArray[0];
                $_SESSION["id"] = $pdoResArray[0]["id"];
                $_SESSION["login"] = $pdoResArray[0]["login"];
                $_SESSION["name"] = $pdoResArray[0]["name"];
            }
        }
    }
}
catch (PDOException $ex)
{
    $Res["status"] = "Error";
    $Res["data"] = $ex->getMessage();
}
print(json_encode($Res));