<?php
$Res = array
(
    "status"=>"Error",
    "data"=>""
);
try 
{
    $login = $_POST["login"];
    $password = md5($_POST["password"]);
    $name = $_POST["name"];
    $email = $_POST["email"];
    $date = date('Y-m-d');
    $aConfig = include "../../config.php";
    $pdoConnection = new PDO('mysql:host='.$aConfig["host"].';dbname='.$aConfig["dbname"], $aConfig["user"], $aConfig["password"]);
    $pdoQuery = "INSERT INTO `user` (id, login, password) VALUES (NULL, '$login', '$password')";
    $pdoRes = $pdoConnection->query($pdoQuery);
    if ($pdoRes == false)
    {
        $Res["status"] = "Error";
        if ($pdoConnection->errorCode() == 23000)
        {
            $Res["data"] = "Аккаунт с таким же логином уже сущесвует";
        }
        else
        {
            $Res["data"] = "(code: 001) Произошла ошибка при попытке добавить нового пользователя";
        }
    }
    else
    {
        $pdoQuery = "SELECT `id` FROM `user` WHERE `login` like ('$login');";
        $pdoRes = $pdoConnection->query($pdoQuery);
        $pdoResArray = $pdoRes->fetchAll();
        $id = $pdoResArray[0]["id"];
        $pdoQuery = "INSERT INTO `user_card` (`id`, `user_id`, `name`, `email`, `date_registration`, `img`) VALUES (NULL, '$id', '$name', '$email', '$date', NULL)";
        $pdoRes = $pdoConnection->query($pdoQuery);
        if ($pdoRes == false)
        {
            $Res["status"] = "Error";
            $Res["data"] = "(code: 002) Произошла ошибка при попытке добавить нового пользователя";
        }
        else
        {
            $pdoQuery = "SELECT `user`.`login`, `user_card`.`name`, `user_card`.`email`, `user_card`.`date_registration`, `user_card`.`img` FROM `user`, `user_card` WHERE `user`.`id` = `user_card`.`user_id` AND `user`.`id` = $id";
            $pdoRes = $pdoConnection->query($pdoQuery);
            if ($pdoRes == false)
            {
                $Res["status"] = "Error";
                $Res["data"] = "(code: 003) Произошла ошибка при попытке добавить нового пользователя";
            }
            else
            {
                $pdoResArray = $pdoRes->fetchAll();
                if (count($pdoResArray) == 0)
                {
                    $Res["status"] = "Error";
                    $Res["data"] = "(code: 004) Произошла ошибка при попытке добавить нового пользователя";
                }
                else
                {
                    $Res["status"] = "Success";
                    $Res["data"] = $pdoResArray[0];
                }
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