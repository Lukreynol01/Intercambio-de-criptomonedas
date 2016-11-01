<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							$transaction_id = $_POST['transaction_id'];
							$merchant_id = $_POST['pay_to_email'];
							$item_number = $_POST['detail1_text'];
							$item_name = $_POST['detail1_description'];
							$mb_amount = $_POST['mb_amount'];
							$mb_currency = $_POST['mb_currency'];
							$date = date("d/m/Y H:i:s");
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$item_number'");
							if($query->num_rows>0) {
							$row = $query->fetch_assoc();
							$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
							$acc = $accountQuery->fetch_assoc();
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
							if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
							$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$transaction_id'");
							$concatFields = $_POST['merchant_id']
											.$_POST['transaction_id']
											.strtoupper(md5($acc['a_field_2']))
											.$_POST['mb_amount']
											.$_POST['mb_currency']
											.$_POST['status'];

										$MBEmail = $acc['a_field_1'];

										// Ensure the signature is valid, the status code == 2,
										// and that the money is going to you
										if (strtoupper(md5($concatFields)) == $_POST['md5sig']
											&& $_POST['status'] == 2
											&& $_POST['pay_to_email'] == $MBEmail)
										{
											// payment successfully...
											if($merchant_id == $acc['a_field_1']) {
													if($mb_amount == $amount && $mb_currency == $currency) {
														if($check_trans->num_rows>0) {
															echo info($lang['info_5']);
														} else {
															$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$transaction_id','$payer_email','$uid','Skrill','$mb_amount','$mb_currency','$date')");
															$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$transaction_id',updated='$time' WHERE id='$row[id]'");
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
										else
										{
											echo error($lang['error_25']);
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