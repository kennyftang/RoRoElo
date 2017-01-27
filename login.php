<?php
if (!isset($_SESSION['user']))
    session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='css/styles.css'/>
    <title>Round Rock Rankings</title>
</head>
<body>
<ul>
    <li><a href='index.php'>Rankings</a></li>
    <li class='active'><a href=''>Login</a></li>
</ul>
<?php
//Check Session
if (isset($_SESSION['user'])) {
    if (time() - $_SESSION['lastactivity'] > 1200) {
        echo("    <p>Your session has expired</p>
");
        session_unset();
        session_destroy();
    } else
        $_SESSION['lastactivity'] = time();
}
//Prints the login form
function printLoginForm()
{
    echo("<form method='post'>
    <label>Username</label></br><input name='username' type='text' id='username'></br>
    <label>Password</label></br><input name='password' type='password' id='password'></br>
    <button type='submit'>Submit</button></br>
</form>");
}

//Set or print username and password
if (isset($_POST["username"]))
    $username = $_POST["username"];
else {
    printLoginForm();
    die();
}
if (isset($_POST["password"]))
    $password = $_POST["password"];
else {
    printLoginForm();
    die();
}
//Setup the PDO
$dsn = "sqlite:" . __DIR__ . "/db/rrtt.sqlite";
$pdo = new PDO($dsn);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
//Check if the user is registering using the secret key
if (preg_match("/^RRHStabletennis107:.*/", $username)) {
    //Check if the user is already registered
    $checkstmt = $pdo->prepare("SELECT COUNT(*) FROM `logins` WHERE `username` = :username");
    $checkstmt->execute(array(":username" => $username));
    $check = $checkstmt->fetch()[0];
    //Get the actual username (take out the secret key)
    $username = explode(":", $username, 2)[1];
    if ($check == 0) {
        //Add the user
        $registerstmt = $pdo->prepare("INSERT INTO logins (username,password) VALUES(?, ?)");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $registerstmt->execute(array($username, $hashed));
        echo("<p>User $username has been added!</p>\r\n");
    }
}
//Get the user
$loginstmt = $pdo->prepare("SELECT * FROM `logins` WHERE `username` = ?");
$loginstmt->execute(array($username));
$login = $loginstmt->fetch();
//Check if the password is correct
if (password_verify($password, $login["password"])) {
    //Start the session and redirect to index.php
    session_start();
    $_SESSION['user'] = $username;
    $_SESSION['lastactivity'] = time();
    header("Location: index.php");
} else
    echo("<p>Username or Password is incorrect</p>\r\n");
?>
</body>
</html>

