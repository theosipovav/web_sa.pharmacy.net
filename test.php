<?php


$pdoConnection = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
$pdoQuery = "INSERT INTO `user` (`id`, `login`, `password`) VALUES (NULL, 'qwerty', '1')";
$pdoRes = $pdoConnection->query($pdoQuery);

print("qwerty");
