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
	echo("<form method='post'>\r\n");
	echo("\t<label>Username</label></br><input name='username' type='text' id='username'></br>\r\n");
	echo("\t<label>Password</label></br><input name='password' type='password' id='password'></br>\r\n");
	echo("\t<button type='submit'>Submit</button></br>\r\n");
	echo("</form>\r\n");
}
if(isset($_POST['username']))
	$username = $_POST['username'];
else {
	printLoginForm();
	die("username");
}
if(isset($_POST['password']))
	$password = $_POST['password'];
else {
	printLoginForm();
	die("password");
}
echo 'did not die';
$dsn = "sqlite:".__DIR__."/db/rrtt.sqlite";
$pdo = new PDO($dsn);
if(preg_match("/\$RRHStabletennis107:.*/", $username)) {
    $registerstmt = $pdo->prepare("INSERT INTO logins VALUES(id,username,password)");
}
$loginstmt = $pdo->prepare("SELECT * FROM logins WHERE username = :username");
$loginstmt->execute(array(':username' => $username));
$login = $loginstmt->fetch();
if(password_verify($password, $login['password'])) {

}
else
	die("\t<p>Username or Password is incorrect</p>\r\n");
?>
</body>
</html>

