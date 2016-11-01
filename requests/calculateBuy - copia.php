<?php
ob_start();
session_start();
error_reporting(0);
include($settings['url']."includes/config.php");
include($settings['url']."includes/functions.php");
$from = protect(filter_input(INPUT_GET,'from'));
$to = protect(filter_input(INPUT_GET,'to'));
$currency_from = protect(filter_input(INPUT_GET,'currency_from'));
$currency_to = protect(filter_input(INPUT_GET,'currency_to'));
$amountx = protect(filter_input(INPUT_GET,'amount'));
$amount = str_ireplace(",",".",$amountx);
$amountid = protect(filter_input(INPUT_GET,'amountid'));
      $link = file_get_contents('http://api.coindesk.com/v1/bpi/currentprice.json');
      $CoinDesk = json_decode($link, true);
      $rate = ($CoinDesk != "" ? $CoinDesk['bpi']['USD']['rate_float'] : $btcven_json_decode['BTC']['USD']);
      $ratemxn = ($CoinDesk != "" ? $CoinDesk['bpi']['MXN']['rate_float'] : $btcven_json_decode['BTC']['MXN']);
      //$rateUSD = ($CoinDesk != "" ? $CoinDesk['bpi']['MXN']['rate_float'] : $btcven_json_decode['MXN']['USD']);
      $rateUSD = number_format($ratemxn/$rate,2);

if(empty($amount)) { echo '0'; 
    }
        elseif(!is_numeric($amount)) { echo '0'; 
        } else {
            if($amount < 0) { echo '0';
            } else {
                    if($from == "Bitcoin") {
                        
                                if($currency_to == "USD"){
                                 //   do{
				$result = ($amount/$rate) ;
				//} while($result*$rate != $amount);
				echo var_dump(number_format($result,7));
                                } elseif($currency_to == "MXN") {
                                //    do{
                                $result = ($amount/$ratemxn) ;
                                //} while($result*$ratemxn != $amount);
                                echo number_format($result,7);
                                }
                    } elseif($to == "Bitcoin") {
                        
                                if($currency_from == "USD"){
                                //    do{
				$result = ($amount*$rate) ;
				//} while($result/$rate != $amount);
				echo number_format($result,2);
                                } elseif($currency_from == "MXN") {
                                //    do{
                                $result = ($amount*$ratemxn) ;
				//} while($result/$ratemxn != $amount);
                                echo number_format($result,2);
                                }
                    } elseif($from == "MercadoPago") {
                    
                                if($currency_to == "BTC"){
                                //    do{
				$result = $amount*$ratemxn;
                                //} while($result/$ratemxn != $amount);
				echo number_format($result,2);
                                } elseif($currency_to == "USD") {
                                 //   do{
 				$result = $amount*$rateUSD;
				//} while($result/$rateUSD != $amount);
                                echo number_format($result,2);
                                }
                    } elseif($to == "MercadoPago") {
                    
                                if($currency_from == "BTC"){
                                //    do{
				$result = $amount/$ratemxn;
				//} while($result*$ratemxn != $amount);
                                echo number_format($result,7, ".", "");
                                } elseif($currency_from == "USD") {
                                //    do{
                                $result = $amount/$rateUSD;
				//} while($result*$rateUSD != $amount);
                                echo number_format($result,2);
                                }
                    } elseif($currency_from == $currency_to) {
				$result = $amount;
				echo number_format($result,2);
                                
		} else {
			echo 'HA HABIDO ALGUN ERROR';	
		}
            }
            }
?>
