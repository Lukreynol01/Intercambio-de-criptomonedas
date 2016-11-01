<?php
ob_start();
session_start();
error_reporting(0);
include("../../includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("../../includes/functions.php");
$from = protect($_GET['from']);
$query = $db->query("SELECT * FROM ec_currencies WHERE currency_from='$from'");
if($query->num_rows>0) {
	while($row = $query->fetch_assoc()) {
		if (strpos($list,$row['currency_to']) !== false) { } else {
			echo '<option value="'.$row[currency_to].'">'.$row[currency_to].'</option>';
		}
		$list .= $row['currency_to'].",";
	}
} else {
	echo '<option value="">NaN</option>';
}
?>