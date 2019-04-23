<?php

$login = $_GET["login"];
$password = $_GET["password"];
$name = $_GET["name"];
$email = $_GET["email"];
$date = new DateTime();

// INSERT INTO `user` (`id`, `login`, `password`) VALUES (NULL, 'qwe', 'qwe')
// INSERT INTO `user_card` (`id`, `user_id`, `name`, `email`, `date_registration`, `img`) VALUES (NULL, '1', 'qweqwe', 'qweqweqwe', '2019-04-03', NULL)

try 
{
    $conn = new PDO('mysql:host=localhost;dbname=sa.pharmacy.net', 'administrator', '611094');
    $sth = $conn->prepare("INSERT INTO 'user' ('login', 'password') VALUES ('$login', '$password');");
    



    foreach($dbh->query('SELECT * from user') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $ex) {
    print "Error!: " . $ex->getMessage() . "<br/>";
    die();
}
?> 