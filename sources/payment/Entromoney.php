<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							include("includes/entromoney.php");
							$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='Entromoney'");
								$acc = $accountQuery->fetch_assoc();
							$config = array();
							$config['sci_user'] = $acc['a_field_1'];
							$config['sci_id'] 	= $acc['a_field_2'];
							$config['sci_pass'] = $acc['a_field_3'];
							$config['receiver'] = $acc['a_field_4'];
							try {
								$sci = new Paygate_Sci($config);
							}
							catch (Paygate_Exception $e) {
								exit($e->getMessage());
							}

							$input = array();
							$input['hash'] = $_POST['hash'];

							// Decode hash
							$error = '';
							$tran = $sci->query($input, $error);
							foreach($tran as $v => $k) {
								$trans[$v] = $k;
							}
							$date = date("d/m/Y H:i:s");
							$status = $trans['status'];
							$payment_id = $trans['payment_id'];
							$receiver = $trans['account_purse'];
							$sender = $trans['purse'];
							$eamount = $trans['amount'];
							$batch = $trans['batch'];
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$trans[payment_id]'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
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
								$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$batch'");
								if($d == "status") {
									if($error) {
										echo error($error);
									} else {
										if($status == "completed") {
											if($check_trans->num_rows>0) {
																	echo info($lang['info_5']);
																} else {
																	$insert = $db->query("INSERT transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$batch','$sender','$uid','Entromoney','$eamount','$currency','$date')");
																	$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$batch',updated='$time' WHERE id='$row[id]'");
														emailsys_payment_received($row['u_field_2'],$row['id']);
														echo success($lang['success_6']);
																}
										} else {
											echo error($lang['error_29']);
										}
									}
								} elseif($d == "success") {
									if($error) {
										echo error($error);
									} else {
										if($status == "completed") {
											if($check_trans->num_rows>0) {
																	echo info($lang['info_5']);
																} else {
																	$insert = $db->query("INSERT transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$batch','$sender','$uid','Entromoney','$eamount','$currency','$date')");
																	$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$batch',updated='$time' WHERE id='$row[id]'");
														emailsys_payment_received($row['u_field_2'],$row['id']);
														echo success($lang['success_6']);
																}
										} else {
											echo error($lang['error_29']);
										}
									}
								} elseif($d == "fail") {
									$time = time();
									$update = $db->query("UPDATE ec_exchanges SET status='6',updated='$time' WHERE id='$row[id]'");
									echo error($lang['error_26']);
								} else {
									echo error($lang['error_29']);
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