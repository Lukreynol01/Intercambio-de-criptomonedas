<?php
ob_start();
session_start();
error_reporting(0);
if(file_exists("./install.php")) {
	header("Location: ./install.php");
} 
include("includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("includes/functions.php");
include(getLanguage($settings['url'],null,null));
check_unpayed();
//include("includes/payouts.php");
if(checkSession()) {
	$time = time();
	$update = $db->query("UPDATE ec_users SET updated='$time' WHERE id='$_SESSION[ec_uid]'");
	if(idinfo($_SESSION['ec_uid'],"status") == "2") {
		unset($_SESSION['ec_uid']);
		unset($_SESSION['ec_user']);
		session_unset();
		session_destroy();
		header("Location: $settings[url]");
	}
} else {
	if($_COOKIE['ec_user_id']) {
		$_SESSION['ec_uid'] = $_COOKIE['ec_user_id'];
		$_SESSION['ec_user'] = $_COOKIE['ec_username'];
		header("Location: $settings[url]");
	}
}

include("sources/header.php");
$a = protect($_GET['a']);
switch($a) {
	case "login": include("sources/login.php"); break;
	case "register": include("sources/register.php"); break;
	case "password": include("sources/password.php"); break;
	case "account": include("sources/account.php"); break;
	case "page": include("sources/page.php"); break;
	case "pages": include("sources/pages.php"); break;
	case "testimonials": include("sources/testimonials.php"); break;
	case "exchange": include("sources/exchange.php"); break;
	case "become_payment": include("sources/become_payment.php"); break;
        case "blog": include("blog/index.php"); break;
	case "logout": 
		unset($_SESSION['ec_uid']);
		unset($_SESSION['ec_user']);
		unset($_COOKIE['ec_user_id']);
		unset($_COOKIE['ec_username']);
		setcookie("ec_user_id", "", time() - (86400 * 30), '/'); // 86400 = 1 day
		setcookie("ec_username", "", time() - (86400 * 30), '/'); // 86400 = 1 day
		session_unset();
		session_destroy();
		header("Location: $settings[url]");
	break;
	default: include("sources/homepage.php");
}
include("sources/footer.php");
mysqli_close($db);
?>