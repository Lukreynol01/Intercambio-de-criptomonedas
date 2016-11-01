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
$currency_to = protect($_GET['currency_to']);
$query = $db->query("SELECT * FROM ec_companies WHERE name='$from'");
$row = $query->fetch_assoc();
$query2 = $db->query("SELECT * FROM ec_currencies WHERE cid='$row[id]' and company_from='$from' and company_to='$to' and currency_from='$currency_from' and currency_to='$currency_to'");
if($query2->num_rows>0) { 
	$row2 = $query2->fetch_assoc();
	echo $row2['rate_from']." ".$row2['currency_from']." = ".$row2['rate_to']." ".$row2['currency_to'];
} else {
	echo '-';
}
?>