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
$from = protect($_GET['from']);
$to = protect($_GET['to']);
$currency_from = protect($_GET['currency_from']);
$query = $db->query("SELECT * FROM ec_companies WHERE name='$from'");
$row = $query->fetch_assoc();
$query2 = $db->query("SELECT * FROM ec_currencies WHERE cid='$row[id]' and company_from='$from' and company_to='$to' and currency_from='$currency_from' ORDER BY id");
if($query2->num_rows>0) {
	while($row2 = $query2->fetch_assoc()) {
		if (strpos($list,$row2['currency_to']) !== false) { } else {
			echo '<option value="'.$row2[currency_to].'">'.$row2[currency_to].'</option>';
		}
		$list .= $row2['currency_to'].",";
	}
} else {
	echo '<option value="0">NaN</option>';
}
?>