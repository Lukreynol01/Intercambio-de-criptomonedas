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
$company_from = protect($_POST['company_from']);
$from = $company_from;
$company_to = protect($_POST['company_to']);
$to = $company_to;
$currency_from = protect($_POST['currency_from']);
$currency_to = protect($_POST['currency_to']);
$amount_from = protect($_POST['amount_from']);
$amount_from = str_ireplace(",","",$amount_from);
$amount_to = protect($_POST['amount_to']);
$amount_to = str_ireplace(",","",$amount_to);
$u_field_1 = protect($_POST['u_field_1']);
$u_field_2 = protect($_POST['u_field_2']);
$u_field_3 = protect($_POST['u_field_3']);
$u_field_4 = protect($_POST['u_field_4']);
$u_field_5 = protect($_POST['u_field_5']);
$u_field_6 = protect($_POST['u_field_6']);
$u_field_7 = protect($_POST['u_field_7']);
$u_field_8 = protect($_POST['u_field_8']);
$u_field_9 = protect($_POST['u_field_9']);
$u_field_10 = protect($_POST['u_field_10']);
$time = time();
$accQuery = $db->query("SELECT * FROM ec_companies WHERE name='$company_from'");
$acc = $accQuery->fetch_assoc();
$ccQuery = $db->query("SELECT * FROM ec_currencies WHERE company_from='$company_from' and company_to='$company_to' and currency_from='$currency_from' and currency_to='$currency_to'");
if($ccQuery->num_rows>0) {
	$err_cur = 0;
	$cc = $ccQuery->fetch_assoc();
} else {
	$err_cur = 1;
}
$exchange_id = randomHash(7)."-".randomHash(13)."-".randomHash(6);
if(checkSession()) {
	$uid = $_SESSION['ec_uid'];
} else {
	$uid = 0;
}
$ip = $_SERVER['REMOTE_ADDR'];

if(empty($acc['a_field_1'])) { 
	$data['status'] = "error";
	$data['msg'] = error("$lang[error_35] $company_from.");
} elseif($err_cur == 1) { 
	$data['status'] = "error";
	$data['msg'] = error("$lang[error_36] $company_from.");
} elseif(empty($company_from) or empty($company_to) or empty($currency_from) or empty($currency_to) or empty($amount_from) or empty($amount_to) or empty($u_field_2)) {
	$data['status'] = "error";
	$data['msg'] = error($lang['error_5']);
} elseif(!is_numeric($amount_from)) {
	$data['status'] = "error";
	$data['msg'] = error($lang['error_38']);
} elseif(!is_numeric($amount_to)) {
	$data['status'] = "error";
	$data['msg'] = error($lang['error_38']);
} elseif($acc['minimal_amount'] > $amount_from) {
	$data['status'] = "error";
	$data['msg'] = error("$lang[error_39] $acc[minimal_amount] $cc[currency_from].");
} elseif($amount_from > $acc['maximum_amount']) {
	$data['status'] = "error";
	$data['msg'] = error("$lang[error_40] $acc[maximum_amount] $cc[currency_from].");
} elseif($amount_to > $cc['reserve']) {
	$data['status'] = "error";
	$data['msg'] = error("$lang[error_41] $cc[reserve] $cc[currency_to].");	
} elseif(!isValidEmail($u_field_2)) { 
	$data['status'] = "error";
	$data['msg'] = error($lang['error_8']);	
}
elseif($to == "PayPal" && !isValidEmail($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Skrill" && !isValidEmail($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "WebMoney" && empty($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "MercadoPago" && strlen($u_field_1)<8) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Payeer" && strlen($u_field_1)<8) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Perfect Money" && strlen($u_field_1)<7) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "AdvCash" && !isValidEmail($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "OKPay" && strlen($u_field_1)<8) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Entromoney" && strlen($u_field_1)<9) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Payza" && !isValidEmail($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "SolidTrust Pay" && empty($u_field_1)) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[account]."); }
elseif($to == "Bitcoin" && strlen($u_field_1)<20) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[address]."); }
elseif($to == "Litecoin" && strlen($u_field_1)<20) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[address]."); }
elseif($to == "Dogecoin" && strlen($u_field_1)<20) { $data['status'] = "error"; $data['msg'] = error("$lang[please_enter_valid] $to $lang[address]."); }
elseif($to == "Bank Transfer" && empty($u_field_3)) { $data['status'] = "error"; $data['msg'] = error($lang['error_42']); }
elseif($to == "Bank Transfer" && empty($u_field_4)) { $data['status'] = "error"; $data['msg'] = error($lang['error_43']); }
elseif($to == "Bank Transfer" && empty($u_field_5)) { $data['status'] = "error"; $data['msg'] = error($lang['error_44']); }
elseif($to == "Bank Transfer" && empty($u_field_6)) { $data['status'] = "error"; $data['msg'] = error($lang['error_45']); }
elseif($to == "Bank Transfer" && empty($u_field_7)) { $data['status'] = "error"; $data['msg'] = error($lang['error_46']); }
elseif($to == "Moneygram" && empty($u_field_3)) { $data['status'] = "error"; $data['msg'] = error($lang['error_42']); }
elseif($to == "Moneygram" && empty($u_field_4)) { $data['status'] = "error"; $data['msg'] = error($lang['error_43']); }
elseif($to == "Western union" && empty($u_field_3)) { $data['status'] = "error"; $data['msg'] = error($lang['error_42']); }
elseif($to == "Western union" && empty($u_field_4)) { $data['status'] = "error"; $data['msg'] = error($lang['error_43']); 
} else {
	$data['status'] = "success";
	$insert = $db->query("INSERT ec_exchanges (uid,cfrom,cto,currency_from,currency_to,amount_from,amount_to,rate_from,rate_to,status,created,ip,exchange_id,u_field_1,u_field_2,u_field_3,u_field_4,u_field_5,u_field_6,u_field_7,u_field_8,u_field_9,u_field_10) VALUES ('$uid','$company_from','$company_to','$currency_from','$currency_to','$amount_from','$amount_to','$cc[rate_from]','$cc[rate_to]','1','$time','$ip','$exchange_id','$u_field_1','$u_field_2','$u_field_3','$u_field_4','$u_field_5','$u_field_6','$u_field_7','$u_field_8','$u_field_9','$u_field_10')");
	$query = $db->query("SELECT * FROM ec_exchanges WHERE exchange_id='$exchange_id'");
	$row = $query->fetch_assoc();
	$data['msg'] = $row['id'];
}

echo json_encode($data);
?>