<?php
//Start session
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
    <li class='active'><a
                href=''><?php echo isset($_SESSION['user']) ? "Rankings (logged in as " . $_SESSION['user'] . ")" : "Rankings" ?></a>
    </li>
    <?php echo isset($_SESSION['user']) ? "<li class='signout'><a href='signout.php'>Sign Out</a></li>" : "<li><a href='login.php'>Login</a></li>\r\n" ?>
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
?>
<h1>Rankings</h1>
<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Elo</th>
        <th>Change</th>
        <?php echo isset($_SESSION['user']) ? "<th>Match</th>\r\n" : "" ?>
    </tr>
    <?php
    //Setting up the PDO
    $dsn = "sqlite:" . __DIR__ . "/db/rrtt.sqlite";
    $pdo = new PDO($dsn);
    //Count how many players there are currently
    $countstmt = $pdo->prepare("SELECT COUNT(*) FROM players");
    $countstmt->execute();
    $count = $countstmt->fetch()[0];
    //Get every player into an array
    $playerstmt = $pdo->prepare("SELECT * FROM players");
    $playerstmt->execute();
    //Loop through each player
    for ($i = 0; $i < $count; $i++) {
        $player = $playerstmt->fetch();
        //Getting the correct change arrow
        $img = $player['change'] ? "res/inc.png" : "res/dec.png";
        echo("<tr>
        <td>" . $player["rank"] . "</td>
        <td>" . $player["name"] . "</td>
        <td>" . $player["elo"] . "</td>
        <td><img src='$img'/></td>");
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
<?php echo isset($_SESSION['user']) ? "<button class='add'>Add Player</button>\r\n" : ""; ?>
</body>
</html>