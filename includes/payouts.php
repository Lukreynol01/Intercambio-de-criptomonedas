<?php
function ProcessPayouts() {
	ProcessEntromoneyPayouts();
	ProcessPerfectmoneyPayouts();
	ProcessBitcoinPayouts();
	ProcessLitecoinPayouts();
	ProcessDogecoinPayouts();
	ProcessPaypalPayouts();
}

function GetEntromoneyBalance($account) {
	global $db, $settings;
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Entromoney'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3']) or empty($acc['a_field_4'])) {
		$log = '[Entromoney] We can`t process Entromoney exchanges. Please configure Entromoney account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
		return 0;
	} else {
		$config = array();
		$config['api_user']	= $acc['a_field_1'];
		$config['api_id']	= $acc['a_field_5'];
		$config['api_pass']	= $acc['a_field_6'];
		 
		// Call lib
		try {
			$api = new Paygate_Api($config);
		}
		catch (Paygate_Exception $e) {
			exit($e->getMessage());
		}
		 
		// Get balance of all purses
		//$res = $api->balance();
		//print_r($res);
		 
		// Get balance of purse
		$res = $api->balance($account);
		return $res->result;
	}
}

function ProcessEntromoneyPayouts() {
	global $db, $settings;
	include("includes/entromoney_api.php");
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Entromoney'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_5']) or empty($acc['a_field_6'])) {
		$log = '[Entromoney] We can`t process Entromoney exchanges. Please configure Entromoney account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='Entromoney' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetEntromoneyBalance($acc['a_field_2']);
				if($row['amount_to'] > $balance) {
					$log = '[Entromoney] We can`t process Entromoney exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$config = array();
					$config['api_user']	= $acc['a_field_1'];
					$config['api_id']	= $acc['a_field_5'];
					$config['api_pass']	= $acc['a_field_6'];
					 
					// Call lib
					try {
						$api = new Paygate_Api($config);
					}
					catch (Paygate_Exception $e) {
						exit($e->getMessage());
					}
					 
					// Send money
					$desc = 'Exchange '.$row[amount_from].' '.$row[currency_from];
					$sender_purse	= $acc['a_field_2'];
					$receiver_purse	= $row['u_field_1'];
					$amount			= $row['amount_to'];
					$desc			= $desc;
					$payment_id		= $row['exchange_id'];
					$res = $api->transfer($sender_purse, $receiver_purse, $amount, $desc, $payment_id);
					$transaction_id = $res->result->batch;
					$uid = $row['uid'];
					$ip = $row['ip'];
					$time = time();
					$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','Entromoney','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
					$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
					$log = '[Entromoney] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				}
			}
		}
	}
}

function GetPerfectmoneyBalance() {
	global $db, $settings;
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Perfect Money'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[PerfectMoney] We can`t process Perfect Money exchanges. Please configure Perfect Money account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
		return 0;
	} else {
		$f=fopen('https://perfectmoney.is/acct/balance.asp?AccountID='.$acc[a_field_3].'&PassPhrase='.$acc[a_field_2].'', 'rb');

		if($f===false){
		   $log = '[PerfectMoney] We can`t process Perfect Money exchanges. Cant connect to Perfect Money server.';
			$time = time();
			$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
			return 0;
		   exit;
		}

		// getting data
		$out=array(); $out="";
		while(!feof($f)) $out.=fgets($f);

		fclose($f);

		// searching for hidden fields
		if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
		    $log = '[PerfectMoney] We can`t process Perfect Money exchanges. Invalid output.';
			$time = time();
			$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
			return 0;
		   exit;
		}

		// putting data to array
		$ar="";
		foreach($result as $item){
		   $key=$item[1];
		   $ar[$key]=$item[2];
		}

		$account = $acc['a_field_1'];
		return $ar[$account];
	}
}

function ProcessPerfectmoneyPayouts() {
	global $db, $settings;
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Perfect Money'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[PerfectMoney] We can`t process Perfect Money exchanges. Please configure Perfect Money account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='Perfect Money' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetPerfectmoneyBalance();
				if($row['amount_to'] > $balance) {
					$log = '[PerfectMoney] We can`t process Perfect Money exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$f=fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.$acc[a_field_3].'&PassPhrase='.$acc[a_field_2].'&Payer_Account='.$acc[a_field_1].'&Payee_Account='.$row[u_field_1].'&Amount='.$row[amount_to].'&PAY_IN=1&PAYMENT_ID='.$row[id].'', 'rb');

					if($f===false){
						 $log = '[PerfectMoney] We can`t process Perfect Money exchanges. Cant connect to Perfect Money server.';
							$time = time();
							$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
							return 0;
						   exit;
					}

					// getting data
					$out=array(); $out="";
					while(!feof($f)) $out.=fgets($f);

					fclose($f);

					// searching for hidden fields
					if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
					   $log = '[PerfectMoney] We can`t process Perfect Money exchanges. Invalid output.';
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
						return 0;
					   exit;
					}

					$ar="";
					foreach($result as $item){
					   $key=$item[1];
					   $ar[$key]=$item[2];
					}
					
					$transaction_id = $ar['PAYMENT_BATCH_NUM'];
					$uid = $row['uid'];
					$ip = $row['ip'];
					$time = time();
					$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','Perfect Money','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
					$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
					$log = '[PerfectMoney] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				}
			}
		}
	}
}

function GetBitcoinBalance() {
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Bitcoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Bitcoin] We can`t process Bitcoin exchanges. Please configure Bitcoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
		return 0;
	} else {
		$apiKey = $acc['a_field_3'];
		$pin = $acc['a_field_4'];
		$version = 2; // the API version

		$block_io = new BlockIo($apiKey, $pin, $version);

		$balance = $block_io->get_balance();
		return $balance->data->available_balance;
	}
}

function ProcessBitcoinPayouts() {
	global $db, $settings;
	include("includes/block_io.php");
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Bitcoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Bitcoin] We can`t process Bitcoin exchanges. Please configure Bitcoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='Bitcoin' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetBitcoinBalance();
				if($row['amount_to'] > $balance) {
					$log = '[Bitcoin] We can`t process Bitcoin exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$apiKey = $acc['a_field_3'];
					$pin = $acc['a_field_4'];
					$version = 2; // the API version

					$block_io = new BlockIo($apiKey, $pin, $version);
					$amount = $row['amount_to'];
					$address = $row['u_field_1'];
					$payment = $block_io->withdraw(array('amounts' => $amount, 'to_addresses' => $address, 'pin' => $pin));
					if($payment->status == "success") {
						$transaction_id = $payment->data->tx_id;
						$uid = $row['uid'];
						$ip = $row['ip'];
						$time = time();
						$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','Bitcoin','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
						$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
						$log = '[Bitcoin] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
					} else {
						$log = '[Bitcoin] We can`t exchange #<b>'.$row[exchange_id].'. '.$payment->data->error_message;
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");	
					}
				}
			}
		}
	}
}

function GetLitecoinBalance() {
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Litecoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Litecoin] We can`t process Bitcoin exchanges. Please configure Bitcoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
		return 0;
	} else {
		$apiKey = $acc['a_field_3'];
		$pin = $acc['a_field_4'];
		$version = 2; // the API version

		$block_io = new BlockIo($apiKey, $pin, $version);

		$balance = $block_io->get_balance();
		return $balance->data->available_balance;
	}
}

function ProcessLitecoinPayouts() {
	global $db, $settings;
	include("includes/block_io.php");
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Litecoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Litecoin] We can`t process Litecoin exchanges. Please configure Litecoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='Litecoin' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetLitecoinBalance();
				if($row['amount_to'] > $balance) {
					$log = '[Litecoin] We can`t process Litecoin exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$apiKey = $acc['a_field_3'];
					$pin = $acc['a_field_4'];
					$version = 2; // the API version

					$block_io = new BlockIo($apiKey, $pin, $version);
					$amount = $row['amount_to'];
					$address = $row['u_field_1'];
					$payment = $block_io->withdraw(array('amounts' => $amount, 'to_addresses' => $address, 'pin' => $pin));
					if($payment->status == "success") {
						$transaction_id = $payment->data->tx_id;
						$uid = $row['uid'];
						$ip = $row['ip'];
						$time = time();
						$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','Litecoin','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
						$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
						$log = '[Litecoin] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
					} else {
						$log = '[Litecoin] We can`t exchange #<b>'.$row[exchange_id].'. '.$payment->data->error_message;
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");	
					}
				}
			}
		}
	}
}

function GetDogecoinBalance() {
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Dogecoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Dogecoin] We can`t process Dogecoin exchanges. Please configure Dogecoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
		return 0;
	} else {
		$apiKey = $acc['a_field_3'];
		$pin = $acc['a_field_4'];
		$version = 2; // the API version

		$block_io = new BlockIo($apiKey, $pin, $version);

		$balance = $block_io->get_balance();
		return $balance->data->available_balance;
	}
}

function ProcessDogecoinPayouts() {
	global $db, $settings;
	include("includes/block_io.php");
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Litecoin'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3'])) {
		$log = '[Dogecoin] We can`t process Dogecoin exchanges. Please configure Dogecoin account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='Dogecoin' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetLitecoinBalance();
				if($row['amount_to'] > $balance) {
					$log = '[Dogecoin] We can`t process Dogecoin exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$apiKey = $acc['a_field_3'];
					$pin = $acc['a_field_4'];
					$version = 2; // the API version

					$block_io = new BlockIo($apiKey, $pin, $version);
					$amount = $row['amount_to'];
					$address = $row['u_field_1'];
					$payment = $block_io->withdraw(array('amounts' => $amount, 'to_addresses' => $address, 'pin' => $pin));
					if($payment->status == "success") {
						$transaction_id = $payment->data->tx_id;
						$uid = $row['uid'];
						$ip = $row['ip'];
						$time = time();
						$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','Dogecoin','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
						$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
						$log = '[Dogecoin] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
					} else {
						$log = '[Dogecoin] We can`t exchange #<b>'.$row[exchange_id].'. '.$payment->data->error_message;
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");	
					}
				}
			}
		}
	}
}

function GetPaypalBalance() {
	global $db, $settings;
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='PayPal'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3']) or empty($acc['a_field_4'])) {
		$log = '[PayPal] We can`t process PayPal exchanges. Please configure PayPal account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$API_Password = $acc['a_field_3'];
		$API_Signature = $acc['a_field_4'];
		$environment = '';
		$API_UserName = $acc['a_field_2'];
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if ( "sandbox" === $environment || "beta-sandbox" === $environment )
		{
			$API_Endpoint = "https://api-3t.{$environment}.paypal.com/nvp";
		}
		$version = urlencode( "51.0" );
		$ch = curl_init( );
		curl_setopt( $ch, CURLOPT_URL, $API_Endpoint );
		curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		$nvpreq = "METHOD=GetBalance&VERSION={$version}&PWD={$API_Password}&USER={$API_UserName}&SIGNATURE={$API_Signature}";
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $nvpreq );
		$httpResponse = curl_exec( $ch );
		if ( !$httpResponse )
		{
			exit( "GetBalance failed: ".curl_error( $ch )."(".curl_errno( $ch ).")" );
		}
		$httpResponseAr = explode( "&", urldecode( $httpResponse ) );
		$httpParsedResponseAr = array( );
		foreach ( $httpResponseAr as $i => $value )
		{
			$tmpAr = explode( "=", $value );
			if ( 1 < sizeof( $tmpAr ) )
			{
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
		if ( 0 == sizeof( $httpParsedResponseAr ) || !array_key_exists( "ACK", $httpParsedResponseAr ) )
		{
			exit( "Invalid HTTP Response for POST request({$nvpreq}) to {$API_Endpoint}." );
		}
		if ( "Success" != $httpParsedResponseAr['ACK'] )
		{
			$arr_ACCOUNT_DATA['ERROR'] = "<b>GetBalance failed:</b> <br>";
		}
		$arr_ACCOUNT_DATA['BALANCE'] = $httpParsedResponseAr;
		return $arr_ACCOUNT_DATA['BALANCE']['L_AMT0'];
	}
}

function ProcessPaypalPayouts() {
	global $db, $settings;
	$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='PayPal'");
	$acc = $accountQuery->fetch_assoc();
	if(empty($acc['a_field_1']) or empty($acc['a_field_2']) or empty($acc['a_field_3']) or empty($acc['a_field_4'])) {
		$log = '[PayPal] We can`t process PayPal exchanges. Please configure PayPal account settings.';
		$time = time();
		$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
	} else {
		$query = $db->query("SELECT * FROM ec_exchanges WHERE cto='PayPal' and status='3'");
		if($query->num_rows>0) {
			while($row = $query->fetch_assoc()) {
				$balance = GetPaypalBalance();
				if($row['amount_to'] > $balance) {
					$log = '[PayPal] We can`t process PayPal exchanges. The account does not have enough cash.';
					$time = time();
					$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
				} else {
					$arr_PAYMENT_RESULT = array( );
					$arr_PAYPAL['API_NAME'] = '';
					$arr_PAYPAL['ACCOUNT'] = $acc['a_field_2'];
					$arr_PAYPAL['MAIN_PASSWORD'] = $acc['a_field_3'];
					$arr_PAYPAL['ALT_PASSWORD'] = $acc['a_field_4'];
					$MEMO = 'Exchange from '.$row[company_form].' to '.$row[company_to].' for '.$row[amount_from].' '.$row[amount_currency];
					$i = 0;
					$nvpStr = "&EMAILSUBJECT={$MEMO}&RECEIVERTYPE=EmailAddress&CURRENCYCODE={$row['currency_to']}";
					$nvpStr .= "&L_EMAIL{$i}={$row['u_field_1']}&L_Amt{$i}={$row['amount_to']}&L_UNIQUEID{$i}={$row['exchange_id']}&L_NOTE{$i}={$MEMO}";
					$API_Endpoint = "https://api-3t.paypal.com/nvp";
					if ( "sandbox" === $arr_PAYPAL['API_NAME'] || "beta-sandbox" === $arr_PAYPAL['API_NAME'] )
					{
						$API_Endpoint = "https://api-3t.{$arr_PAYPAL['API_NAME']}.paypal.com/nvp";
					}
					$version = urlencode( "51.0" );
					$ch = curl_init( );
					curl_setopt( $ch, CURLOPT_URL, $API_Endpoint );
					curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
					curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
					curl_setopt( $ch, CURLOPT_POST, 1 );
					$nvpreq = "METHOD=MassPay&VERSION={$version}&PWD={$arr_PAYPAL['MAIN_PASSWORD']}&USER={$arr_PAYPAL['ACCOUNT']}&SIGNATURE={$arr_PAYPAL['ALT_PASSWORD']}".$nvpStr;
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $nvpreq );
					$httpResponse = curl_exec( $ch );
					if ( !$httpResponse )
					{
						$arr_PAYMENT_RESULT['ERROR'] = "PayPal AutoPay failed: ".curl_error( $ch )."(".curl_errno( $ch ).")";
					}
					$httpResponseAr = explode( "&", urldecode( $httpResponse ) );
					$httpParsedResponseAr = array( );
					foreach ( $httpResponseAr as $i => $value )
					{
						$tmpAr = explode( "=", $value );
						if ( 1 < sizeof( $tmpAr ) )
						{
							$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
						}
					}
					if ( 0 == sizeof( $httpParsedResponseAr ) || !array_key_exists( "ACK", $httpParsedResponseAr ) )
					{
						$arr_PAYMENT_RESULT['ERROR'] = "Invalid HTTP Response for POST request({$nvpreq}) to {$API_Endpoint}.";
					}
					if ( "Success" != $httpParsedResponseAr['ACK'] )
					{
					}
					else
					{
						$arr_PAYMENT_RESULT['BATCH'] = $httpParsedResponseAr['CORRELATIONID'];
					}
					if($arr_PAYMENT_RESULT['BATCH']) {
						$transaction_id = $arr_PAYMENT_RESULT['BATCH'];
						$uid = $row['uid'];
						$ip = $row['ip'];
						$time = time();
						$insert = $db->query("INSERT ec_payouts (uid,ip,transaction_id,cto,amount,currency,account,time) VALUES ('$uid','$ip','$transaction_id','PayPal','$row[amount_to]','$row[currency_to]','$row[u_field_1]','$time')");
						$update = $db->query("UPDATE ec_exchanges SET status='4' WHERE id='$row[id]'");
						$log = '[PayPal] Exchange #<b>'.$row[exchange_id].'</b> was processed. Total payed: '.$row[amount_to].' '.$row[currency_to].'.';
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");
					} else {
						$log = '[PayPal] We can`t exchange #<b>'.$row[exchange_id].'. '.$arr_PAYMENT_RESULT['ERROR'];
						$time = time();
						$insert = $db->query("INSERT ec_payouts_logs (log_text,time) VALUES ('$log','$time')");	
					}
				}
			}
		}
	}
}

ProcessPayouts();
?>