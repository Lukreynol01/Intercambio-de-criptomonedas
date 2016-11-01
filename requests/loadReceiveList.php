<?php
ob_start();
session_start();
error_reporting(0);
include("../includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("../includes/functions.php");
include(getLanguage($settings['url'],null,2));
$from = protect($_GET['from']);
if(!empty($from)) {
	$query = $db->query("SELECT * FROM ec_companies WHERE name='$from'");
	$row = $query->fetch_assoc();
	$list = explode(",",$row['receive_list']);
	$i=1;
	foreach($list as $l) {
		if (strpos($l,'//') !== false) { } else {
			if($i == 1) { $sel = 'selected'; } else { $sel = ''; }
			echo '<option value="'.$l.'" '.$sel.'>'.$l.'</option>';
			$i++;
		}
	}
} else {	
	echo '<option value="">'.$lang[error_33].'</option>';
}
?>