<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    header('Location: /beta/login.php');
    exit;
}

require_once(__DIR__.'/../vendor/loader.php');
require_once(__DIR__.'/../mailchimp/MailChimp.php');

use \DrewM\MailChimp\MailChimp;
$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_GET['id'])) exit;


$object = $db->select("*")->from("users")->where('id = ?', $_GET['id'])->execute()->fetchCollection()[0];
if($object==null || $object->u_access_level == 1){
    header('Location: /beta/agents_settings.php');
    exit;
}
$current_user = $db->select("*")->from("users")->where('id = ?', $user_id)->execute()->fetchCollection()[0];
if($current_user->u_access_level != 1) {
    header('Location: /beta/index.php');
    exit;
}

if (isset($_POST['u_name'])) {
    $the_sault = '#4ds$';

    if(isset($_POST['u_pass_hash']) && $_POST['u_pass_hash']!=""){
        $_POST['u_pass_hash'] = md5($_POST['u_pass_hash'].$the_sault);
    }
    else $_POST['u_pass_hash'] = $object->u_pass_hash;

    $db->update("users", $_POST, "id = ?", $_GET['id']);
    $MailChimp = new MailChimp('acf1079b00451144e6f1fe3594015a9d-us7');

    $list_id = '86b071f4c1';

    $result = $MailChimp->post("lists/$list_id", array(
        "members" => array(
            array(
                'email_address' => $_POST['u_email'],
                'status'        => 'subscribed',
                'merge_fields'  =>  array(
                    'FNAME' => $_POST['u_name'],
                    'LNAME' => $_POST['u_last_name'],
                )
            )
        ),
        'update_existing'  => true
    ));
    //var_dump($result);
    header('Location: agents_settings.php');
}
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
        <a href = "../index.php"> <img src="../img/logo-small.jpg" alt="" class="logo-2"></a>
        <div class="ui one column stackable center aligned page grid">
            <h2 class="ui header">Edit agent</h2>
        </div>
        <form class="ui big form" method="post">
            <div class="field">
                <label>Name</label>
                <input type="text" name="u_name" placeholder="Insert agent's name" value="<?= $object->u_name ?>">
            </div>
            <div class="field">
                <label>Surame</label>
                <input type="text" name="u_last_name" placeholder="Insert agent's surname" value="<?= $object->u_last_name ?>">
            </div>
            <div class="field">
                <label>email</label>
                <input type="email" name="u_email" placeholder="Insert agent's email" value="<?= $object->u_email ?>">
            </div>
            <div class="field">
                <label>new password</label>
                <input type="password" name="u_pass_hash" placeholder="Insert agent's password">
            </div>
            <hr>
            <div class="ui one column stackable center aligned page grid">
                <button class="ui massive secondary button ">Save agent</button>
            </div>
        </form>
    </main>
</div>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/semantic.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
