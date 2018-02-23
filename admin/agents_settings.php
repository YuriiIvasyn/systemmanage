
<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}
require_once(__DIR__.'/../vendor/loader.php');

$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);
$current_user = $db->select("*")->from("users")->where('id = ?', $user_id)->execute()->fetchCollection()[0];
if($current_user->u_access_level==1) {
    $all_data = $db->select("*")->from("users")->where("u_access_level != 1")->execute()->fetchCollection();
    $data = $db->select("*")->from("users")->where("u_access_level != 1")->limit(20)->execute()->fetchCollection();
}
else{
    header('Location: /beta/index.php');
    exit;
}
$num_of_pag_page = (int)(count($all_data)/20);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scavolini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/icon.min.css">
    <link rel="stylesheet" href="../css/semantic.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="shortcut icon" href="#" type="image/x-icon">
</head>
<body>
<div class="wrapper">
    <main>
        <a href = "../index.php"> <img src="../img/logo-small.jpg" alt="" class="logo-2"> </a>
        <div class="ui one column stackable center aligned page grid">
            <div class="ui big input search list-search-form">
                <div class="ui labeled icon input">
                    <input  type="text" class="agents" placeholder="search agents" title="Type and press enter">
                    <i class="remove icon" style="cursor: pointer; pointer-events: all; "></i>
                </div>
            </div>
        </div>
        <table class="ui selectable large celled table agents-list">
            <tbody>
            <?php
            foreach ($data as $dataValue) {?>
                <tr  onclick="document.location = 'agent_edit.php?id=<?= $dataValue->id ?>';">

                    <td>

                        <?= $dataValue->u_name.' '.$dataValue->u_last_name ?>

                    </td>
                    <td><?= $dataValue->u_email ?></td>

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
                <a href="agent_add.php"><i class="add user icon"></i></a>
            </div>
        </div>
    </main>


</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/semantic.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>