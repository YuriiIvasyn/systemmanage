<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}

require_once(__DIR__.'/vendor/loader.php');

$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);
$current_user = $db->select("*")->from("users")->where("id = ?", $user_id)->execute()->fetchCollection()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Scavolini</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/icon.min.css">
	<link rel="stylesheet" href="css/semantic.min.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="#" type="image/x-icon">
</head>
<body>
<div class="wrapper">
	<header>
<a href = "index.php"><img class="logo" src="img/logo.jpg" alt=""> </a>

		<img class="town" src="img/town.jpg" alt="">
		<div class="ui one column stackable center aligned page grid">
			<h1 class="ui header">New York Store</h1>
		</div>
        <div class="ui one column stackable center aligned page grid">
            <h2 class="ui header"><?=$current_user->u_name." ".$current_user->u_last_name?></h2>
        </div>
		<div class="bottom-nav">
            <div class="left">
                <a href="logout.php"><i class="power icon"></i></a>
                <?=($current_user->u_access_level == 1)? '<a href="admin/agents_settings.php"><i class="setting icon"></i></a>': ''?>
            </div>
            <div class="right">
				<a href="list.php"><i class="sidebar icon"></i></a>
				<a href="add.php"><i class="add user icon"></i></a>
			</div>
		</div>
	</header>


</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/semantic.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>