<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4><?php echo $lang['payment_status']; ?></h4>
							<?php
							$ac_src_wallet = $_GET['ac_src_wallet'];
							$ac_dest_wallet = $_GET['ac_dest_wallet'];
							$ac_amount = $_GET['ac_amount'];
							$ac_merchant_currency = $_GET['ac_merchant_currency'];
							$ac_transfer = $_GET['ac_transfer'];
							$ac_start_date = $_GET['ac_start_date'];
							$ac_order_id = $_GET['ac_order_id'];
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$ac_order_id'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
								$acc = $accountQuery->fetch_assoc();
								if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
								$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$ac_transfer'");
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
								if($d == "success") {
									if($ac_dest_wallet == $acc['a_field_1']) {
										if($ac_amount == $amount or $ac_merchant_currency == $currency) {
											if($check_trans->num_rows>0) {
												echo info($lang['info_5']);
											} else {
												$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$ac_transfer','$ac_src_wallet','$uid','AdvCash','$ac_amount','$ac_merchant_currency','$ac_start_date')");
												$time = time();
												$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$ac_transfer',updated='$time' WHERE id='$row[id]'");
												emailsys_payment_received($row['u_field_2'],$row['id']);
												echo success($lang['success_6']);
											}
										} else {
											echo error($lang['error_24']);
										}
									} else { 
										echo error($lang['error_25']);
									}
								} elseif($d == "status") {
									if($ac_dest_wallet == $acc['a_field_1']) {
										if($ac_amount == $amount or $ac_merchant_currency == $currency) {
											if($check_trans->num_rows>0) {
												echo info($lang['info_5']);
											} else {
												$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$ac_transfer','$ac_src_wallet','$uid','AdvCash','$ac_amount','$ac_merchant_currency','$ac_start_date')");
												$time = time();
												$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$ac_transfer',updated='$time' WHERE id='$row[id]'");
												emailsys_payment_received($row['u_field_2'],$row['id']);
												echo success($lang['success_6']);
											}
										} else {
											echo error($lang['error_24']);
										}
									} else { 
										echo error($lang['error_25']);
									}
								} elseif($d == "fail") {
									$time = time();
									$update = $db->query("UPDATE ec_exchanges SET status='6',updated='$time' WHERE id='$row[id]'");
									echo error($lang['error_26']);
								} else {
									echo error($lang['error_27']);
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