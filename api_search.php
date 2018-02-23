<?php session_start();
$user_id = $_SESSION["user_id"];
if(!isset($user_id)){
    echo "false";
    exit;
}
require_once(__DIR__.'/vendor/loader.php');
$offset = $_POST['offset'];
$limit = $_POST['limit'];
$u_agent = $_POST['u_agent'];

$search_param = $_POST['search'].'%';
$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);

$current_user = $db->select("*")->from("users")->where('id = ?', $user_id)->execute()->fetchCollection()[0];
if($_POST['searchType']=="agents" && $current_user->u_access_level==1){
    $data = $db->select("*")->from("users")->where("(u_name LIKE ? OR u_last_name LIKE ? ) AND u_access_level != 1", array($search_param,$search_param))->limit($limit, $offset)->execute()->fetchCollection();
    $the_data = $db->select("*")->from("users")->where("(u_name LIKE ? OR u_last_name LIKE ? ) AND u_access_level != 1", array($search_param,$search_param))->execute()->fetchCollection();
    echo count($the_data)."salut-somesd".json_encode($data);
}
else{
    if($current_user->u_access_level==1){
        if($u_agent){
            $data = $db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ?) AND u_creator_id IN (?)", array($search_param,$search_param, $u_agent))->limit($limit, $offset)->execute()->fetchCollection();
            $data_count = count($db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ?) AND u_creator_id IN (?) ", array($search_param,$search_param,$u_agent))->execute()->fetchCollection());

            echo $data_count."salut-somesd".json_encode($data);
        }
        else {
            $data = $db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ?)", array($search_param, $search_param))->limit($limit, $offset)->execute()->fetchCollection();
            $data_count = count($db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ?)", array($search_param, $search_param))->execute()->fetchCollection());

            echo $data_count . "salut-somesd" . json_encode($data);
        }

    }
    else{
        $data = $db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ?) AND u_creator_id = ?", array($search_param, $search_param, $user_id))->limit($limit, $offset)->execute()->fetchCollection();
        $data_count = count($db->select("*")->from("data")->where("(name LIKE ? OR last_name LIKE ? ) AND u_creator_id = ? ", array($search_param, $search_param, $user_id))->execute()->fetchCollection());

        echo $data_count."salut-somesd".json_encode($data);
    }

}
?>