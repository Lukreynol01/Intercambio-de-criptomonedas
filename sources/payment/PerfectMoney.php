<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
								$orderid = $_POST['PAYMENT_ID'];
								$eamount = $_POST['PAYMENT_AMOUNT'];
								$ecurrency = $_POST['PAYMENT_UNITS'];
								$buyer = $_POST['PAYEE_ACCOUNT'];
								$trans_id = $_POST['PAYMENT_BATCH_NUM'];
								$date = date("d/m/Y H:i:s");
								$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$orderid'");
								if($query->num_rows>0) {
									$row = $query->fetch_assoc();
									$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
									$acc = $accountQuery->fetch_assoc();
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
									$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$trans_id'");
									$alternate = strtoupper(md5($acc['a_field_2']));
									define('ALTERNATE_PHRASE_HASH', $alternate);
									$string=
										  $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
										  $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
										  $_POST['PAYMENT_BATCH_NUM'].':'.
										  $_POST['PAYER_ACCOUNT'].':'.ALTERNATE_PHRASE_HASH.':'.
										  $_POST['TIMESTAMPGMT'];
									$hash=strtoupper(md5($string));
									if($hash==$_POST['V2_HASH']){ // proccessing payment if only hash is valid

									   /* In section below you must implement comparing of data you recieved
									   with data you sent. This means to check if $_POST['PAYMENT_AMOUNT'] is
									   particular amount you billed to client and so on. */

									   if($_POST['PAYMENT_AMOUNT']==$amount && $_POST['PAYEE_ACCOUNT']==$acc['u_field_1'] && $_POST['PAYMENT_UNITS']==$currency){

											if($check_trans->num_rows>0) {
													echo info($lang['info_5']);
												} else {
													$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$trans_id','$buyer','$uid','Perfect Money','$eamount','$ecurrency','$date')");
													$time = time();
													$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$trans_id',updated='$time' WHERE id='$row[id]'");
													emailsys_payment_received($row['u_field_2'],$row['id']);
													echo success($lang['success_6']);
												}
										 
									   }else{ // you can also save invalid payments for debug purposes

											echo error($lang['error_31']);
									   }


									}else{ // you can also save invalid payments for debug purposes

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