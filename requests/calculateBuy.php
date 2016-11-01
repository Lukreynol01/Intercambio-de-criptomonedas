<?php



include("../includes/config.php");

include("../includes/functions.php");

$from = protect(filter_input(INPUT_GET,'from'));

$to = protect(filter_input(INPUT_GET,'to'));

$currency_from = protect(filter_input(INPUT_GET,'currency_from'));

$currency_to = protect(filter_input(INPUT_GET,'currency_to'));

$amountx = protect(filter_input(INPUT_GET,'amount'));

$amount = str_ireplace(",",".",$amountx);

      $link = file_get_contents('https://api.coindesk.com/v1/bpi/currentprice/mxn.json');

      $CoinDesk = json_decode($link, true);

      $rate = ($CoinDesk != "" ? $CoinDesk['bpi']['USD']['rate_float'] : $btcven_json_decode['BTC']['USD']);

      $ratemxn = ($CoinDesk != "" ? $CoinDesk['bpi']['MXN']['rate_float'] : $btcven_json_decode['BTC']['MXN']);

      //$rateUSD = ($CoinDesk != "" ? $CoinDesk['bpi']['MXN']['rate_float'] : $btcven_json_decode['MXN']['USD']);

      $rateUSD = ($ratemxn/$rate);



if(empty($amount)) { echo '0'; 

    }

        elseif(!is_numeric($amount)) { echo '0'; 

        } else {

            if($amount < 0) { echo '0';

            } else {

                

                switch ($currency_to) {

                    case "BTC":              

                                if($currency_from == "USD"){

                                //   do{

								$result = ($amount/$rate);

								//} while($result*$rate != $amount);

								echo number_format($result,8);

                                } elseif($currency_from == "MXN") {

                                    //do{

                                $result = ($amount/$ratemxn) ;

                                //} while($result*$ratemxn != $amount);

                                echo number_format($result,8);

                                }

                                break;

                    case "USD":

                        

                                if($currency_from == "BTC"){

                                 //   do{

								$result = $amount*$rate ;

								//} while($result/$rate != $amount);

								echo number_format($result,2);

                                } elseif($currency_from == "MXN") {

                                 //   do{

                                $result = $amount/$rateUSD;

		                		//} while($result*$rateUSD != $amount);

                                echo number_format($result,2);

                                } elseif($currency_from == "USD") {

                                    

                                $result = $amount;

				

                                echo number_format($result,2);

                                }

                                break;

                    case "MXN":

                    

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

                               }elseif($currency_from == "MXN") {

                                 

 				                $result = $amount;

				                echo number_format($result,2);

                               }

		                break;

                    default:

			echo 'HA HABIDO ALGUN ERROR';	

		    }  

            }

        }

?>

