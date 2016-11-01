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
$amount = protect($_GET['amount']);
$amount = str_ireplace(",",".",$amount);
$query = $db->query("SELECT * FROM ec_companies WHERE name='$from'");
$row = $query->fetch_assoc();
if(empty($amount)) { echo '0'; }
elseif(!is_numeric($amount)) { echo '0'; }
else {
	$query2 = $db->query("SELECT * FROM ec_currencies WHERE cid='$row[id]' and company_from='$from' and company_to='$to' and currency_from='$currency_from' and currency_to='$currency_to'");
	if($query2->num_rows>0) { 
		$row2 = $query2->fetch_assoc();
		if($amount < 0) { echo '0'; } else {
			
                    
                    
                    
if($currency_to == "BTC") {
                        
                                if($currency_from == "USD"){
                                 //   do{
				$result = ($amount/$rate) ;
				//} while($result*$rate != $amount);
				echo var_dump(number_format($result,7));
                                } elseif($currency_from == "MXN") {
                                //    do{
                                $result = ($amount/$ratemxn) ;
                                //} while($result*$ratemxn != $amount);
                                echo number_format($result,2);
                                }
                    } elseif($currency_to == "USD") {
                        
                                if($currency_from == "BTC"){
                                //    do{
				$result = ($amount*$rate) ;
				//} while($result/$rate != $amount);
				echo number_format($result,2);
                                } elseif($currency_from == "MXN") {
                                //    do{
                                $result = ($amount/$rateUSD) ;
				//} while($result*$ratemxn != $amount);
                                echo number_format($result,2);
                                } elseif($currency_from == "USD") {
                                //    do{
                                $result = $amount;
				//} while($result*$ratemxn != $amount);
                                echo number_format($result,2);
                                }
                    } elseif($currency_to == "MXN") {
                    
                                if($currency_from == "BTC"){
                                //    do{
				$result = $amount*$ratemxn;
                                //} while($result/$ratemxn != $amount);
				echo number_format($result,2);
                                } elseif($currency_from == "USD") {
                                 //   do{
 				$result = $amount*$rateUSD;
				//} while($result/$rateUSD != $amount);
                                echo number_format($result,2);
                               }
		} else {
			echo 'HA HABIDO ALGUN ERROR';	
		}
                    
                    
                    
                    
		}
	} else {
		echo '0';
	}
}
?>