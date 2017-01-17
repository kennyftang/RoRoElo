<?php
/**
 * Kenny Tang 2017
 */
if(isset($_POST['password']))
    $password = $_POST['[password'];
else
    die();
if($password !== "n1m8"){
    die("<p>Invalid Password</p>\r\n");
}

$dsn = 'sqlite:/db/players.sq3';
$db = new PDO($dsn);