<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							// read the post from PayPal system and add 'cmd'
							$req = 'cmd=_notify-validate';

							foreach ($_POST as $key => $value) {
							$value = urlencode(stripslashes($value));
							$req .= "&$key=$value";
							}

							// post back to PayPal system to validate
							$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
							$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
							$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
							$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

							// assign posted variables to local variables
							$item_name = $_POST['item_name'];
							$item_number = $_POST['item_number'];
							$payment_status = $_POST['payment_status'];
							$payment_amount = $_POST['mc_gross'];
							$payment_currency = $_POST['mc_currency'];
							$txn_id = $_POST['txn_id'];
							$receiver_email = $_POST['receiver_email'];
							$payer_email = $_POST['payer_email'];
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$item_number'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
								$acc = $accountQuery->fetch_assoc();
								$date = date("d/m/Y H:i:s");
								if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
								$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$txn_id'");
								if($acc['include_fee'] == "1") {
									if (strpos($acc['fee'],'%') !== false) { 
										$amount = $row['amount_from'];
										$explode = explode("%",$acc['fee']);
										$fee_percent = 100+$explode[0];
										$new_amount = ($amount * 100) / $fee_percent;
										$new_amount = number_format($new_amount,2);
										$fee_amount = $amount-$new_amount;
										$amount = $amount+$fee_amount;
										$fee_text = $acc['fee'];
									} else {
										$amount = $row['amount_from'] + $acc['fee'];
										$fee_text = $acc['fee']." ".$row['currency_from'];
									}
									$currency = $row['currency_from'];
								} else {
									$amount = $row['amount_from'];
									$currency = $row['currency_from'];
									$fee_text = '0';
								}
								if (!$fp) {
									echo error("Cant connect to PayPal server.");
								} else {
								fputs ($fp, $header . $req);
								while (!feof($fp)) {
								$res = fgets ($fp, 1024);
								if (strcmp ($res, "VERIFIED") == 0) {

									if ($payment_status == 'Completed') {


											if ($receiver_email==$acc['a_field_1']) {

												if ($payment_amount == $amount && $payment_currency == $currency) {
													if($check_trans->num_rows>0) {
														echo info($lang['info_5']);
													} else {
														$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$txn_id','$payer_email','$uid','PayPal','$payment_amount','$payment_currency','$date')");
														$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$txn_id',updated='$time' WHERE id='$row[id]'");
														emailsys_payment_received($row['u_field_2'],$row['id']);
														echo success($lang['success_6']);
													}
												} else {
													echo error($lang['error_24']);
												}

											} else {
												echo error($lang['error_25']);
											}

									}

								}

								else if (strcmp ($res, "INVALID") == 0) {
									echo error($lang['error_29']);
								}
								}
								fclose ($fp);
								}  
							} else {
								echo error($lang['error_28']);
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php include("sources/footer.php"); ?>