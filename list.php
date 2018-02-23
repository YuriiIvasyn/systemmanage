<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}
require_once(__DIR__.'/vendor/loader.php');

$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);
$current_user = $db->select("*")->from("users")->where('id = ?', $user_id)->execute()->fetchCollection()[0];


$mas_user = $db->select("*")->from("users")->where('u_access_level != 1')->execute()->fetchCollection();
//print_r($mas_user);


if($current_user->u_access_level==1) {
    $all_data = $db->select("*")->from("data")->execute()->fetchCollection();
    $data = $db->select("*")->from("data")->limit(20)->execute()->fetchCollection();
}
else{
    $all_data = $db->select("*")->from("data")->where("u_creator_id = ?",$user_id)->execute()->fetchCollection();
    $data = $db->select("*")->from("data")->where("u_creator_id = ?",$user_id)->limit(20)->execute()->fetchCollection();
}
$num_of_pag_page = (int)(count($all_data)/20);
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
<main>
	<a href = "index.php"> <img src="img/logo-small.jpg" alt="" class="logo-2"> </a>
	<div class="ui one column stackable center aligned page grid">
		<div class="ui big input search list-search-form">
            <div class="ui labeled icon input">
                <input  type="text" class="customer" placeholder="search customer" title="Type and press enter">
                <i class="remove icon" style="cursor: pointer; pointer-events: all; "></i>
            </div>
            <div class="users">
                <div data-type="element" data-element="button" class="ui large floating dropdown theme button">
                    <span class="text">Select agent</span>
                    <i class="dropdown icon"></i>
                    <div class="menu ui transition hidden" tabindex="-1">
                        <div data-value="" class="item">All</div>
                        <?php
                        foreach ($mas_user as $mas_users) {?>
                            <div data-value="<?= $mas_users->id ?>" class="item"><?= $mas_users->u_name ?></div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>

		</div>


	</div>
	<table class="ui selectable large celled table list-table">
		<tbody>
			<?php
				foreach ($data as $dataValue) {?>
                        <tr  onclick="document.location = 'edit.php?id=<?= $dataValue->id ?>';" class="id-<?= $dataValue->id ?>">
                            <td>
                                    <?= $dataValue->name.' '.$dataValue->last_name ?>
                            </td>
                            <td><?= $dataValue->city ?></td>
                            <td><?= $dataValue->added ?></td>
                            <td><?= ($dataValue->c_review=='1')? "<i class='telegram icon'></i>" : " " ?></td>
                        </tr>

				<?php }
			 ?>

		</tbody>
	</table>
    <div class="ui demo buttons pagination-buttons">
        <div class="ui button active">1</div>
        <?php for($i = 0; $i!=$num_of_pag_page; $i++): ?>
            <div class="ui button"><?=$i+2?></div>
        <?php endfor; ?>
    </div>

	<div class="bottom-nav">
		<div class="right">
			<a href="add.php"><i class="add user icon"></i></a>
		</div>
	</div>
</main>


</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/semantic.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
