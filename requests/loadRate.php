<?php

error_reporting(0);

include("../includes/config.php");

include("../includes/functions.php");

$from = protect(filter_input(INPUT_GET,'from'));

$to = protect(filter_input(INPUT_GET,'to'));

$currency_from = protect(filter_input(INPUT_GET,'currency_from'));

$currency_to = protect(filter_input(INPUT_GET,'currency_to'));

//$query = $db->query("SELECT * FROM ec_companies WHERE name='$from'");

//$row = $query->fetch_assoc();

//$query2 = $db->query("SELECT * FROM ec_currencies WHERE cid='$row[id]' and company_from='$from' and company_to='$to' and currency_from='$currency_from' and currency_to='$currency_to'");

      $link = file_get_contents('https://api.coindesk.com/v1/bpi/currentprice.json');

      $CoinDesk = json_decode($link, true);

      

      $link2 = file_get_contents('https://api.coindesk.com/v1/bpi/currentprice/mxn.json');

      $CoinDesk2 = json_decode($link2, true);

      

//      $link3 = file_get_contents('http://ltc.blockr.io/api/v1/exchangerate/current');

//      $blockr = json_decode($link3, true);

      

      $rate = ($CoinDesk != "" ? $CoinDesk['bpi']['USD']['rate_float'] : $btcven_json_decode['BTC']['USD']); //749 USD

      $ratemxn = ($CoinDesk2 != "" ? $CoinDesk2['bpi']['MXN']['rate_float'] : $btcven_json_decode['BTC']['MXN']); //12500 MXN

      //$rateUSD= ($CoinDesk != "" ? $CoinDesk['bpi']['MXN']['rate_float'] : $btcven_json_decode['MXN']['USD']); //18 pesos



//      $ch = curl_init();

//      curl_setopt($ch, CURLOPT_URL, "http://ltc.blockr.io/api/v1/exchangerate/current");

//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//      $LiteTC1 = curl_exec($ch);

//      curl_close($ch);

//      $LiteTC2 = var_dump(json_decode($LiteTC1, true));

////https://api.bitso.com/v2/ticker //en pesos 12500 pesos

//$ch = curl_init('https://data.mexbt.com/ticker/btcmxn');

//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//    curl_setopt($ch, CURLOPT_HEADER, false);

//    $prices = curl_exec($ch);

//      foreach ($blockr as $indice) { //$indice => $valor)

//          echo  $indice;

//          foreach ($indice[1] as $indice2) {

//      echo  $indice2; //. ':' . $valor;

//      //echo $indice["DATA"];

//      }}

      //var_dump($valor);



// Este ciclo muestra todas las claves del array asociativo

// donde el valor equivale a "manzana"

//$nombreID = current($blockr); 

//    if ($nombreID == 'SUCCES') {

//        echo var_dump(key($blockr)).'<br />';

//   }

//      print_r(array_keys($blockr));



//      print_r(array_values($blockr));

//       $LiteTC3 = var_dump($blockr["DATA "[2]]["0"]["LTC"]); 

//        //echo $LiteTC3;

//        //echo $LiteTC;

//        //ECHO var_dump($link3[1][0][1][1]);

   

       $rateUSD = number_format($ratemxn/$rate,2);

//       $rateLTC = number_format($LiteTC3/$rateUSD,7);



//if($query2->num_rows>0) { 

	//$row2 = $query2->fetch_assoc();
        if ($currency_from == "LTC"){

                if ($currency_to == "USD"){

                    echo "1 USD = ".$rateLTC." LTC";

                }  elseif ($currency_to == "BTC"){

                    echo "1 BTC = ".$rateLTC." LTC";

                }  elseif($currency_to == "MXN"){

                    echo "1 MXN = ".$rateLTC." LTC"; //CONFIGURAR

                }  else {

                    echo "Hubo un error";

                }
                        } elseif ($currency_from == "MXN"){
            

                            if ($currency_to == "BTC"){

                                   echo "1 BTC = ". number_format($ratemxn,2)." MXN";

                            } elseif ($currency_to == "USD"){

                                    echo "1 USD = ".$rateUSD."MXN";

                            } elseif ($currency_to == "MXN"){

                                    echo "1 MXN = 1 MXN";

                            } else  {

                                    echo "Hubo error";

                            }

        } elseif ($currency_from == "BTC"){

                if ($currency_to == "USD"){

                    echo "1 BTC = ".$rate." USD";

                } elseif ($currency_to == "MXN") {

                    echo "1 BTC = ".number_format($ratemxn,2)." MXN";  

                } elseif ($currency_to == "LTC") {

                    echo "1 BTC = ".number_format($ratemxn,2)." LTC";  

                }  else {

                    echo "Hubo un error";

                }

        } elseif ($currency_from == "USD"){

          	if ($currency_to == "BTC"){

                    echo "1 BTC = ".$rate." USD";

                } elseif ($currency_to == "MXN") {

                    echo "1 USD = ".$rateUSD." MXN";  

                }  else {

                    echo "Hubo un error";

                } 

 

        } else  {

		      echo 'HA HABIDO ALGUN ERROR';
             	

	       }

?>