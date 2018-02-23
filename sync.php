<?php
require_once(__DIR__.'/vendor/loader.php');
require_once(__DIR__.'/mailchimp/MailChimp.php');

use \DrewM\MailChimp\MailChimp;


$MailChimp = new MailChimp('acf1079b00451144e6f1fe3594015a9d-us7');
$db = new database\DB(DB_DSN, DB_USER, DB_PASSWORD);

$list_id = '120763049c';
$result = $MailChimp->get("lists/".$list_id."/members");


$s_db = $db->select("id, email")->from("data")->execute()->fetchCollection();
$object = (object) array();


foreach ($s_db as $s_dbs){
        $object->unsubscribed = 'subscription_status';
        $db->update("data", $object, "id = ?", $s_dbs->id);
}

foreach ($result['members'] as $results){
    $sync = $db->select("id, email")->from("data")->where('email = ?', $results['email_address'])->execute()->fetchCollection()[0];
    if($sync){
        $object->unsubscribed = '';
        $db->update("data", $object, "id = ?", $sync->id);
    }
}


