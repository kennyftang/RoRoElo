<?php
//TODO: Make the button to add players work
//TODO: Add a way to input a win
//TODO: Calculate the change in elo
//TODO: Show the ELO lost or won
//TODO: Update ranks based on ELO
//TODO: Check name lowercased
//TODO: Add button to add a new player
//TODO: Hide the add player button once it's pressed
//If session has expired, queue will be the session expired message
$queue = "";
//Start session
if (!isset($_SESSION['user']))
    session_start();
//Prints out the message queue
function printQueue() {
    echo $GLOBALS['queue'];
}
//Check session status
if (isset($_SESSION['user'])) {
    if (time() - $_SESSION['lastactivity'] > 1200) {
            $GLOBALS['queue'] = "    <p>Your session has expired</p>
";
        session_unset();
        session_destroy();
    } else
        $_SESSION['lastactivity'] = time();
}
//A value_compare_func for usort
function sortByElo($a, $b) {
    return $b['elo'] - $a['elo'];
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
    <li class='active'><a href=''><?php echo isset($_SESSION['user']) ? "Rankings (logged in as " . $_SESSION['user'] . ")" : "Rankings" ?></a></li>
    <?php echo isset($_SESSION['user']) ? "<li class='signout'><a href='signout.php'>Sign Out</a></li>" : "<li><a href='login.php'>Login</a></li>\r\n" ?>
</ul>
<?php
printQueue();
?>
<h1>Rankings</h1>
<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Elo</th>
        <th>Change</th>
    </tr>
    <?php
    //Setting up the PDO
    $dsn = "sqlite:" . __DIR__ . "/db/rrtt.sqlite";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //Add player if user submitted a new player
    if(isset($_POST['name'])) {
	    $name = $_POST['name'];
	    $addplayerstmt = $pdo->prepare("INSERT INTO `players` (`name`) VALUES (?)");
	    $addplayerstmt->execute(array($name));
    }
    if(isset($_POST['winner']) && isset($_POST['loser'])) {
        $winner = $_POST['winner'];
        $loser = $_POST['loser'];
        if($winner !== $loser) {
	        $eloStmt = $pdo->prepare("SELECT `elo` FROM `players` WHERE `name` LIKE(?)");
	        $eloStmt->execute(array($winner));
	        $winnerElo = $eloStmt->fetch()['elo'];
	        $eloStmt->execute(array($loser));
	        $loserElo = $eloStmt->fetch()['elo'];
	        $changeElo = (int)round(32 * (1 - 1 / (1 + pow(10, ($loserElo - $winnerElo) / 400))));
	        $winnerElo += $changeElo;
	        $loserElo -= $changeElo;
	        $updateEloStmt = $pdo->prepare("UPDATE `players` SET `elo` = ? WHERE `name` LIKE(?)");
	        $updateEloStmt->execute(array($winnerElo, $winner));
	        $updateEloStmt->execute(array($loserElo, $loser));
	        $updateChangeStmt = $pdo->prepare("UPDATE `players` SET `change` = ? WHERE `name` LIKE(?)");
	        $updateChangeStmt->execute(array(true, $winner));
	        $updateChangeStmt->execute(array(false, $loser));
        }
    }
    //Count how many players there are currently
    $countstmt = $pdo->prepare("SELECT COUNT(*) FROM `players`");
    $countstmt->execute();
    $count = $countstmt->fetch()[0];
    //Get every player into an array
    $playerstmt = $pdo->prepare("SELECT * FROM `players`");
    $playerstmt->execute();
    $players = array(array());
    //Loop through each player
    for ($i = 0; $i < $count; $i++) {
	    $player = $playerstmt->fetch();
	    $players[$i] = array("change" => $player['change'], "name" => $player['name'], "elo" => $player['elo']);
    }
    //Sort by elo
    usort($players, "sortByElo");
    //Print out the table of players
    for ($i = 0; $i < count($players); $i++) {
        $player = $players[$i];
        //Getting the correct change arrow
        if($player["change"] !== null) {
	        $img = $player["change"] ? "res/inc.png" : "res/dec.png";
	        $changehtml = "<td><img src='$img'/></td>";
        } else
            $changehtml = "<td><p class='dash'>-</p></td>";
        echo("<tr>
        <td>" . ($i + 1) . "</td>
        <td>" . $player["name"] . "</td>
        <td>" . $player["elo"] . "</td>
        $changehtml");
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
<?php echo isset($_SESSION['user']) ? "<button class='add' onclick='addPlayer()'>Add Player</button>" : ""; ?>
<?php echo isset($_SESSION['user']) ? "<form method='post'><button class='remove' onclick='removePlayer()'>Remove Player</button></form>" : ""; ?>
<?php
if(isset($_SESSION['user'])) {
	echo("
<form class='play' method='post'>
    <select name='winner'>
        <option></option>");
	for($i = 0; $i < count($players); $i++)
		echo("
        <option>{$players[$i]["name"]}</option>");
	echo("
    </select>
    <p> won against </p>
    <select name='loser'>
        <option></option>");
	for($i = 0; $i < count($players); $i++)
		echo("
        <option>{$players[$i]["name"]}</option>");
	echo("
    </select>
    <button type='submit'>Submit</button>
</form>
");
}
if(isset($invalidRank))
    if($invalidRank)
	    echo("<p>Invalid Player</p>");
if(isset($_POST['removerank'])) {
    $remove = $_POST['removerank'];
    if(!is_numeric($remove))
        $invalidRank = true;
    else {
        $remove = $players[$remove - 1]["name"];
        $removeStmt = $pdo->prepare("DELETE FROM `players` WHERE `name` LIKE(?)");
        $invalidRank = !$removeStmt->execute(array($remove));
    }
    echo("<script>window.location.href = window.location.href;</script>");
}
?>
</body>
</html>