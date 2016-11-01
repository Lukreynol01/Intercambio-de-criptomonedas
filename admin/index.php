<?php
error_reporting(0);
ob_start();
session_start(); 
if(file_exists("../install.php")) {
	header("Location: ../install.php");
} 
include("../includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("languages/English.php");
include("../includes/functions.php");
$a = protect($_GET['a']);
if(checkAdminSession()) {
include("sources/header.php");
switch($a) {
	case "exchanges": include("sources/exchanges.php"); break;
	case "companies": include("sources/companies.php"); break;
	case "users": include("sources/users.php"); break;
	case "testimonials": include("sources/testimonials.php"); break;
	case "settings": include("sources/settings.php"); break;
	case "faq": include("sources/faq.php"); break;
	case "pages": include("sources/pages.php"); break;
        case "emails": include("sources/emails.php"); break;
	case "logout": 
		unset($_SESSION['ec_a_uid']);
		unset($_SESSION['ec_a_user']);
		session_unset();
		session_destroy();
		header("Location: ./");
	break;
	default: include("sources/dashboard.php"); 
}
include("sources/footer.php");
} else { 
	include("sources/login.php");
}
mysqli_close($db);
?>