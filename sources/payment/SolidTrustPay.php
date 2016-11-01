<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							if($d == "notify") {
								$tr_id = $_POST['tr_id'];
								$amount = $_POST['amount'];
								$currency = $_POST['currency'];
								$orderid = $_POST['user1'];
								$merchant = $_POST['merchantAccount'];
								$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$orderid'");
								if($query->num_rows>0) {
										$row = $query->fetch_assoc();
										$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
										$acc = $accountQuery->fetch_assoc();
										$date = date("d/m/Y H:i:s");
										if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
										$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$tr_id'");
										$sci_pwd = $acc['a_field_3'];
										$sci_pwd = md5($sci_pwd.'s+E_a*');  //encryption for db
										$hash_received = MD5($_POST['tr_id'].":".MD5($sci_pwd).":".$_POST['amount'].":".$_POST['merchantAccount'].":".$_POST['payerAccount']);

											if ($hash_received == $_POST['hash']) {
													if($merchant == $acc['a_field_1']) {
																		if($check_trans->num_rows>0) {
																			echo info($lang['info_5']);
																		} else {
																			$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$tr_id','$payer_email','$uid','SolidTrust Pay','$amount','$currency','$date')");
																			$time = time();
																			$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$tr_id',updated='$time' WHERE id='$row[id]'");
																			emailsys_payment_received($row['u_field_2'],$row['id']);
																			echo success($lang['success_6']);
																		}
																} else { 
																	echo error($lang['error_25']);
																}
											}
											else {
												echo error($lang['error_29']);
											} 
								} else {
									echo error($lang['error_28']);
								}
							} elseif($d == "confirm") {
								$tr_id = $_POST['tr_id'];
								$amount = $_POST['amount'];
								$currency = $_POST['currency'];
								$orderid = $_POST['user1'];
								$merchant = $_POST['merchantAccount'];
								$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$orderid'");
								if($query->num_rows>0) {
										$row = $query->fetch_assoc();
										$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
										$acc = $accountQuery->fetch_assoc();
										$date = date("d/m/Y H:i:s");
										if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
										$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$tr_id'");
										$sci_pwd = $acc['a_field_3'];
										$sci_pwd = md5($sci_pwd.'s+E_a*');  //encryption for db
										$hash_received = MD5($_POST['tr_id'].":".MD5($sci_pwd).":".$_POST['amount'].":".$_POST['merchantAccount'].":".$_POST['payerAccount']);

											if ($hash_received == $_POST['hash']) {
													if($merchant == $acc['a_field_1']) {
																		if($check_trans->num_rows>0) {
																			echo info($lang['info_5']);
																		} else {
																			$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$tr_id','$payer_email','$uid','SolidTrust Pay','$amount','$currency','$date')");
																			$time = time();
																			$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$tr_id',updated='$time' WHERE id='$row[id]'");
																			emailsys_payment_received($row['u_field_2'],$row['id']);
																			echo success($lang['success_6']);
																		}
																} else { 
																	echo error($lang['error_25']);
																}
											}
											else {
												echo error($lang['error_29']);
											} 
								} else {
									echo error($lang['error_28']);
								}
							} elseif($d == "return") {
								echo success($lang['success_6']);
							} else { echo error($lang['error_30']); }
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php include("sources/footer.php"); ?>