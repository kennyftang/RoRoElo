<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/styles.css"/>
	<title>Round Rock Rankings</title>
</head>
<body>
<ul>
	<li><a href="index.php">Rankings</a></li>
	<li class="active"><a href="login.php">Login</a></li>
</ul>
<?php

function printLoginForm()
{
	echo("<form method='post'>
    <label>Username</label></br><input name='username' type='text' id='username'></br>
    <label>Password</label></br><input name='password' type='password' id='password'></br>
    <button type='submit'>Submit</button></br>
</form>");
}
if(isset($_POST["username"]))
	$username = $_POST["username"];
else {
	printLoginForm();
	die();
}
if(isset($_POST["password"]))
	$password = $_POST["password"];
else {
	printLoginForm();
	die();
}
$dsn = "sqlite:".__DIR__."/db/rrtt.sqlite";
$pdo = new PDO($dsn);
if(preg_match("/^RRHStabletennis107:.*/", $username)) {
    password_hash($password, PASSWORD_DEFAULT);
    $checkstmt = $pdo->prepare("SELECT COUNT(*) FROM `logins` WHERE `username` = :username");
    $checkstmt->execute(array(":username" => $username));
    $check = $checkstmt->fetch()[0];
    $username = explode(":", $username, 2)[1];
    if($check == 0) {
        $pdo->beginTransaction();
        $registerstmt = $pdo->prepare("INSERT INTO logins (username,password) VALUES(?, ?)");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $registerstmt->execute(array($username, $hashed));
        echo("<p>User $username has been added!</p>\r\n");
        $pdo->commit();
    }
}
$loginstmt = $pdo->prepare("SELECT * FROM `logins` WHERE `username` = ?");
$loginstmt->execute(array($username));
$login = $loginstmt->fetch();
if(password_verify($password, $login["password"])) {
    echo("
    ");
}
else
	echo("<p>Username or Password is incorrect</p>\r\n");
?>
</body>
</html>

