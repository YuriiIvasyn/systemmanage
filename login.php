<?php session_start();
$user_satus = $_SESSION["user_id"];
if(isset($user_satus)){
    header('Location: /beta/index.php');
    exit;
}

require_once(__DIR__ . '/vendor/loader.php');

$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);
if(isset($_POST['email']) && isset($_POST['password'])){
        $the_sault = '#4ds$';
        $result_hash =
        $object = $db->select("*")->from("users")->where('u_email = ? AND u_pass_hash = ?', array($_POST['email'],md5($_POST['password'].$the_sault)))->execute()->fetchCollection()[0];
        if (is_null($object)){

        }
        if($object->u_email == $_POST['email']){
            $_SESSION["user_id"] = $object->id;
            header('Location: /beta/index.php');
        }


}
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
	<div class="wrapper login-page">
		<main>
			<a href = "index.php"> <img src="img/logo-small.jpg" alt="" class="logo-2"> </a>
			<br>
			<form class="ui big form" method="post">
				<div class="field">
					<label>Email</label>
					<input type="email" name="email" placeholder="Enter your email">
				</div>
				<div class="field">
					<label>Password</label>
					<input type="password" name="password" placeholder="Enter your password">
				</div>
				<div class="ui one column stackable center aligned page grid">
					<button class="ui massive secondary button ">Log in</button>
				</div>
			</form>
		</main>
	</div>

	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/semantic.min.js"></script>
	<script src="js/main.js"></script>
</body>
</html>