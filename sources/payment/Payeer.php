<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4><?php echo $lang['payment_status']; ?></h4>
							<?php
							if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
							{
								$m_operation_id = $_POST['m_operation_id'];
								$m_operation_date = $_POST['m_operation_date'];
								$m_orderid = $_POST['m_orderid'];
								$m_amount = $_POST['m_amount'];
								$m_currency = $_POST['m_curr'];
								$query = $db->query("SELECT * FROM ecexchanges WHERE id='$m_orderid'");
								if($query->num_rows>0) {
									$row = $query->fetch_assoc();
									$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
									$acc = $accountQuery->fetch_assoc();
									if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
									$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$m_operation_id'");
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
									$m_key = $acc['a_field_2'];
									$arHash = array($_POST['m_operation_id'],
											$_POST['m_operation_ps'],
											$_POST['m_operation_date'],
											$_POST['m_operation_pay_date'],
											$_POST['m_shop'],
											$_POST['m_orderid'],
											$_POST['m_amount'],
											$_POST['m_curr'],
											$_POST['m_desc'],
											$_POST['m_status'],
											$m_key);
									$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
									if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
									{
											if($m_amount == $amount or $m_currency == $currency) {
												if($check_trans->num_rows>0) {
													echo error($lang['info_5']);
												} else {
													$insert = $db->query("INSERT ec_transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$m_operation_id','','$uid','Payeer','$m_amount','$m_currency','$m_operation_date')");
													$time = time();
														$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$m_operation_id',updated='$time' WHERE id='$row[id]'");
													emailsys_payment_received($row['u_field_2'],$row['id']);
													echo success($lang['success_6']);
												}
											} else {
												echo error($lang['error_24']);
											}
									} else {
										$update = $db->query("UPDATE exchanges SET status='6' WHERE id='$row[id]'");
										echo error($lang['error_27']);
									}
								} else {
									echo error($lang['error_28']);
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