<?php
//Start session if it has not been started
if (!isset($_SESSION['user']))
	session_start();
//Make sure session has not expired, 20 minute timeout
if (isset($_SESSION['user'])) {
	if (time() - $_SESSION['lastactivity'] > 1200) {
		$GLOBALS['queue'] = "    <p>Your session has expired</p>
";
		session_unset();
		session_destroy();
	} else
		$_SESSION['lastactivity'] = time();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<link rel='stylesheet' href='css/styles.css'/>
	<script src='js/rankings.js'></script>
	<title>Round Rock Rankings</title>
</head>
<body>
<ul>
	<li><a href='index.php'><?php echo isset($_SESSION['user']) ? "Rankings (logged in as " . $_SESSION['user'] . ")" : "Rankings" ?></a>
	</li>
	<li class='active'><a href=''>Log</a></li>
	<?php echo isset($_SESSION['user']) ? "<li class='signout'><a href='signout.php'>Sign Out</a></li>" : "<li><a href='login.php'>Login</a></li>\r\n" ?>
</ul>
<h1>Log</h1>
<table>
	<tr>
		<th>Winner</th>
		<th>Loser</th>
		<th>∆Elo</th>
	</tr>
	<?php
	//Setting up the PDO and showing errors
	$dsn = "sqlite:" . __DIR__ . "/db/rrtt.sqlite";
	$pdo = new PDO($dsn);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	//Count how many log entries there are currently
	$countstmt = $pdo->prepare("SELECT COUNT(*) FROM `game_log`");
	$countstmt->execute();
	$count = $countstmt->fetch()[0];
	$gameLogStmt = $pdo->prepare("SELECT * FROM `game_log`");
	$gameLogStmt->execute();
	//Print out the game log
	for ($i = 0; $i < $count; $i++) {
		$log = $gameLogStmt->fetch();
		//Print out the table data with correct spacing, excuse the terrible formatting here...
		echo("<tr>
        <td>" . $log["winner"] . "</td>
        <td>" . $log["loser"] . "</td>
        <td>( +" . $log["delo"] . " )</td>");
		//Make sure we don't add extra spaces
		if ($i !== $count - 1)
			echo("
    </tr>
    ");
		else
			echo("
    </tr>
");
	}
	?>
</table>
</body>
</html>