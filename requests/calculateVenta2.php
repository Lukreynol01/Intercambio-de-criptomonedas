<?php
//ob_start();
//session_start();
//error_reporting(0);

include("../includes/config.php");
//$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
//if ($db->connect_errno) {
//    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
//}
//$db->set_charset("utf8");
//$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
//$settings = $settingsQuery->fetch_assoc();
//$settings = "madebyadmin-02c2afd565d3f19-031d7390-13823-e12";
include("../includes/functions.php");
$from = protect(filter_input(INPUT_GET,'from'));
$to = protect(filter_input(INPUT_GET,'to'));
$currency_from = protect(filter_input(INPUT_GET,'currency_from'));
$currency_to = protect(filter_input(INPUT_GET,'currency_to'));
$amountx = protect(filter_input(INPUT_GET,'amount'));
$amount = str_ireplace(",",".",$amountx);
$amountid = protect(filter_input(INPUT_GET,'amountid'));
$ch = curl_init();
      //curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/tobtc?currency=USD&value=1");
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //$conversion = curl_exec($ch);
      // Use curl to get current prices and 15 minute averages for all currencies from Blockchain.info's exchange rates API
      curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/ticker");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $prices = json_decode(curl_exec($ch), true);
      curl_close($ch);
$rate = $prices['USD']['sell'];
if ($amountid == "mainAmountReceive") {
    if(empty($amount)) { echo '0'; 
    }
        elseif(!is_numeric($amount)) { echo '0'; 
        }
            elseif($amount < 0) { echo '0';
            } else {
                    if($from == "Bitcoin") {
				$result =  ($amount / $rate)/0.1 ;
				echo number_format($result,7, ".", "");
				//$row2['rate_to'] = $prices['USD']['sell'];				
                    } elseif($to == "Bitcoin") {
				$result = ($amount*$rate)/0.1;
				echo number_format($result,2, ".", ",");
				//$row2['rate_from'] = $prices['USD']['sell'];
                    } else {
				$result = $amount*$rate;
				echo number_format($result,2, ".", "");
			}
            //echo '0';
            }
} elseif($amountid == "mainAmountSend") { //elseif $to
    if(empty($amount)) { echo '0'; 
    }
        elseif(!is_numeric($amount)) { echo '0';
        }
        elseif($amount < 0) { echo '0'; 
        }else {
                if($to == "Bitcoin") {
				//$usd_price = $amount;     # Let cost of elephant be 10$
				$result =  ($amount / $rate)/0.1 ;
				echo number_format($result,7, ".", "");
				//$row2['rate_from'] = $prices['USD']['sell'];
		} elseif($from == "Bitcoin") { //or $to == "Litecoin" or $to == "Dogecoin"
				$result = ($amount*$rate)/0.1;
				echo number_format($result,2, ".", ",");
					//$row2['rate_to'] = $prices['USD']['sell'];
		} else {
				$result = $amount*$rate;
				echo number_format($result,2, ".", ",");
		} 
         //echo '0';
        }
			
    }

?>

