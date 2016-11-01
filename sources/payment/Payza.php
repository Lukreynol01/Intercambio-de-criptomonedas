<?php include("sources/header.php"); ?>
<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4>Payment status</h4>
							<?php
							$receivedSecurityCode = $_POST['ap_securitycode'];
							$receivedMerchantEmailAddress = $_POST['ap_merchant'];	
							$transactionStatus = $_POST['ap_status'];
							$testModeStatus = $_POST['ap_test'];	 
							$purchaseType = $_POST['ap_purchasetype'];
							$totalAmountReceived = $_POST['ap_totalamount'];
							$feeAmount = $_POST['ap_feeamount'];
							$netAmount = $_POST['ap_netamount'];
							$transactionReferenceNumber = $_POST['ap_referencenumber'];
							$currency = $_POST['ap_currency']; 	
							$transactionDate= $_POST['ap_transactiondate'];
							$transactionType= $_POST['ap_transactiontype'];
							
							//Setting the customer's information from the IPN post variables
							$customerFirstName = $_POST['ap_custfirstname'];
							$customerLastName = $_POST['ap_custlastname'];
							$customerAddress = $_POST['ap_custaddress'];
							$customerCity = $_POST['ap_custcity'];
							$customerState = $_POST['ap_custstate'];
							$customerCountry = $_POST['ap_custcountry'];
							$customerZipCode = $_POST['ap_custzip'];
							$customerEmailAddress = $_POST['ap_custemailaddress'];
							
							//Setting information about the purchased item from the IPN post variables
							$myItemName = $_POST['ap_itemname'];
							$myItemCode = $_POST['ap_itemcode'];
							$myItemDescription = $_POST['ap_description'];
							$myItemQuantity = $_POST['ap_quantity'];
							$myItemAmount = $_POST['ap_amount'];
							
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$myItemCode'");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								$accountQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
								$acc = $accountQuery->fetch_assoc();
								$date = date("d/m/Y H:i:s");
								if(checkSession()) { $uid = $_SESSION['suid']; } else { $uid = 0; }
								$check_trans = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$transactionReferenceNumber'");
								//Setting extra information about the purchased item from the IPN post variables
								$additionalCharges = $_POST['ap_additionalcharges'];
								$shippingCharges = $_POST['ap_shippingcharges'];
								$taxAmount = $_POST['ap_taxamount'];
								$discountAmount = $_POST['ap_discountamount'];
							 
								//Setting your customs fields received from the IPN post variables
								$myCustomField_1 = $_POST['apc_1'];
								$myCustomField_2 = $_POST['apc_2'];
								$myCustomField_3 = $_POST['apc_3'];
								$myCustomField_4 = $_POST['apc_4'];
								$myCustomField_5 = $_POST['apc_5'];
								$myCustomField_6 = $_POST['apc_6'];
								if($c == "results") {
									if ($receivedMerchantEmailAddress != $acc['a_field_1']) {
										echo error($lang['error_25']);
									}
									else {	
										//Check if the security code matches
										if ($receivedSecurityCode != $acc['a_field_2']) {
											echo error($lang['error_24']);
										}
										else {
											if ($transactionStatus == "Success") {
												if ($testModeStatus == "1") {
													// Since Test Mode is ON, no transaction reference number will be returned.
													// Your site is currently being integrated with Payza IPN for TESTING PURPOSES
													// ONLY. Don't store any information in your production database and 
													// DO NOT process this transaction as a real order.
												}
												else {
													if($check_trans->num_rows>0) {
																	echo error($lang['info_5']);
																} else {
																	$insert = $db->query("INSERT transactions (txn_id,payee,uid,company,amount,currency,time) VALUES ('$transactionReferenceNumber','$customerEmailAddress','$uid','Entromoney','$totalAmountReceived','$currency','$transactionDate')");
																	$time = time();
																	$update = $db->query("UPDATE ec_exchanges SET status='3',transaction_id='$transactionReferenceNumber',updated='$time' WHERE id='$row[id]'");
																	emailsys_payment_received($row['u_field_2'],$row['id']);
																	echo success($lang['success_6']);
																}
												}			
											}
											else {
												echo error($lang['error_29']);
											}
										}
									}
								} elseif($c == "cancel") {
									$update = $db->query("UPDATE ec_exchanges SET status='6' WHERE id='$row[id]'");
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