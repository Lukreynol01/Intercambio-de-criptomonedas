<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							if($d == "result") {
								require_once 'includes/webmoney.inc.php';

								  $wm_prerequest = new WM_Prerequest();
								  if ($wm_prerequest->GetForm() == WM_RES_OK)
								  {
									 $orderid = $wm_prerequest->payment_no;
									   $merchant = $wm_prerequest->payee_purse;
									   $send_amount = $wm_prerequest->payment_amount;
									   $trans_id = $wm_prerequest->sys_trans_no;
									   $date = $wm_prerequest->sys_trans_date;
									   $payer = $wm_prerequest->payer_wm;
									   $query = $db->query("SELECT * FROM ec_exchanges WHERE id='$orderid'");
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
										$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$trans_id'");
										if (
										  $wm_prerequest->payment_no == $row['id'] &&
										  $wm_prerequest->payee_purse == $acc['a_field_1'] &&
										  $wm_prerequest->payment_amount == $amount
										)
										{
											if($check_trans->num_rows>0) {
												echo info($lang['info_5']);
											} else {
												$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$trans_id','$payer','$uid','WebMoney','$send_amount','$payment_currency','$date')");
												$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$trans_id',updated='$time' WHERE id='$row[id]'");
												emailsys_payment_received($row['u_field_2'],$row['id']);
												echo success($lang['success_6']);
											}
										}
										else
										{
										  echo error($lang['error_29']);
										}
									  }
								} else {
									echo error($lang['error_28']);
								}
							} elseif($d == "success") {
							require_once 'includes/webmoney.inc.php';

								 $wm_prerequest = new WM_Prerequest();
								  if ($wm_prerequest->GetForm() == WM_RES_OK)
								  {
									 $orderid = $wm_prerequest->payment_no;
									   $merchant = $wm_prerequest->payee_purse;
									   $send_amount = $wm_prerequest->payment_amount;
									   $trans_id = $wm_prerequest->sys_trans_no;
									   $date = $wm_prerequest->sys_trans_date;
									   $payer = $wm_prerequest->payer_wm;
									   $query = $db->query("SELECT * FROM ec_exchanges WHERE id='$orderid'");
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
										$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$trans_id'");
										if (
										  $wm_prerequest->payment_no == $row['id'] &&
										  $wm_prerequest->payee_purse == $acc['a_field_1'] &&
										  $wm_prerequest->payment_amount == $amount
										)
										{
											if($check_trans->num_rows>0) {
												echo info($lang['info_5']);
											} else {
												$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$trans_id','$payer','$uid','WebMoney','$send_amount','$payment_currency','$date')");
												$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$trans_id',updated='$time' WHERE id='$row[id]'");
												emailsys_payment_received($row['u_field_2'],$row['id']);
												echo success($lang['success_6']);
											}
										}
										else
										{
										  echo error($lang['error_29']);
										}
									  }
								} else {
									echo error($lang['error_28']);
								}
							} elseif($d == "fail") {
							require_once 'includes/webmoney.inc.php';
								  $wm_result = new WM_Result();
								  $wm_result->method = WM_POST;
								   $orderid = $wm_result->payment_no;
								  if ($wm_result->GetForm() == WM_RES_OK)
								  {	
									$time = time();
									$update = $db->query("UPDATE ec_exchanges SET status='6',updated='$time' WHERE id='$orderid'");
										echo error($lang['error_26']);
									}
							} else {
								echo error($lang['error_30']);
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php include("sources/footer.php"); ?>