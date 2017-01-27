<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/styles.css"/>
    <title>Round Rock Rankings</title>
</head>
<body>
<ul>
    <li class="active"><a href="#">Rankings</a></li>
    <li><a href="login.php">Login</a></li>
</ul>
<h1>Rankings</h1>
<table>
    <tr>
        <th class="rank">Rank</th>
        <th class="name">Name</th>
        <th class="elo">Elo</th>
        <th class="rankchange">Change</th>
    </tr>
    <?php
    $dsn = "sqlite:".__DIR__."/db/rrtt.sqlite";
    $pdo = new PDO($dsn);
    $countstmt = $pdo->prepare("SELECT COUNT(*) FROM players");
    $countstmt->execute();
    $count = $countstmt->fetch()[0];
    $playerstmt = $pdo->prepare("SELECT * FROM players");
    $players = $playerstmt->execute();
    for ($i = 0; $i < $count; $i++) {
        $player = $playerstmt->fetch();
        $change = $player['change'];
        $img = $change ? "res/inc.png" : "res/dec.png";
        echo("<tr>\r\n");
        echo("\t\t<td>". $player["rank"] ."</td>\r\n");
        echo("\t\t<td>". $player["name"] ."</td>\r\n");
        echo("\t\t<td>". $player["elo"] ."</td>\r\n");
        echo("\t\t<td><img src='$img'/></td>\r\n");
        echo("\t</tr>\r\n");
    }
    ?>
</table>
</body>
</html>