<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=exchanges"); }
$row = $query->fetch_assoc();
?>
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Exchanges / Explore / <?php echo $row['exchange_id']; ?></h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<?php
					if(isset($_POST['btn_update'])) {
						$status = protect($_POST['status']);
						$pin = protect($_POST['pin']);
						$reason = protect($_POST['reason']);
						if(empty($status)) { echo error("Please select status to update."); }
						elseif($row['cto'] == "Western Union" && $status == "4" && empty($pin)) { echo error("Please enter $row[cto] PIN."); }
						elseif($row['cto'] == "Moneygram" && $status == "4" && empty($pin)) { echo error("Please enter $row[cto] PIN."); }
						elseif($status == "6" && empty($reason)) { echo error("Please enter the reason why refuse exchange."); }
						else {
							$time = time();
							$update = $db->query("UPDATE ec_exchanges SET status='$status',updated='$time' WHERE id='$row[id]'");
							$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$id'");
							$row = $query->fetch_assoc();
							if($status == "4") {
								echo success("Status changed to: Processed. User will be notified by email.");
								emailsys_notify_exchange_processed($row['id'],$pin);
							} elseif($status == "6") {
								echo success("Status changed to: Refused. Need to return user money to their account. User will be notified by email.");
								emailsys_notify_exchange_denied($row['id'],$reason);	
							} else {
								echo success("Your changes was saved successfully.");
							}
						}
					}
					?>
				</div>	
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-heading">Order information</div>
						<div class="panel-body">
							<div class="row">
									<div class="col-md-12">
										<table class="table table-striped">
											<tr>
												<td colspan="2"><h4><?php echo $row['cfrom']; ?> <i class="fa fa-exchange"></i> <?php echo $row['cto']; ?> <span class="pull-right"><?php echo decodeStatus($row['status']); ?></span></h4></td>
											</tr>
										</table>
										
									</div>
									<div class="col-md-6">
										<table class="table table-striped">
											<tr>
												<td colspan="2"><b>Order details</b></td>
											</tr>
											<tr>
												<td><span class="pull-left">Sell e-currency</span></td>
												<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left">Buy e-currency</span></td>
												<td><span class="pull-right"><?php echo $row['amount_to']; ?> <?php echo $row['currency_to']; ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">Exchange rate</span></td>
												<td><span class="pull-right"><?php echo $row['rate_from']; ?> <?php echo $row['currency_from']; ?> = <?php echo $row['rate_to']; ?> <?php echo $row['currency_to']; ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">User</span></td>
												<td><span class="pull-right"><?php if($row['uid']>0) { echo '<a href="./?a=users&b=exchanges&id='.$row[uid].'">'.idinfo($row[uid],"username").'</a>'; } else { echo 'Anonymous'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">User IP Address</span></td>
												<td><span class="pull-right"><?php	echo $row['ip']; ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">Transaction ID</span></td>
												<td><span class="pull-right"><?php if($row['transaction_id']) { echo $row['transaction_id']; } else { echo '-'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">Created on</span></td>
												<td><span class="pull-right"><?php if($row['created']>0) { echo date("d/m/Y H:i:s",$row['created']); } else { echo '-'; } ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left">Updated on</span></td>
												<td><span class="pull-right"><?php if($row['updated']>0) { echo date("d/m/Y H:i:s",$row['updated']); } else { echo '-'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left">Expired on</span></td>
												<td><span class="pull-right"><?php if($row['expired']>0) { echo date("d/m/Y H:i:s",$row['expired']); } else { echo '-'; } ?></span></td>
											</tr> 
										</table>
									</div>
									<div class="col-md-6">
										<table class="table table-striped">
											<tr>
												<td colspan="2"><b>Payout details</b></td>
											</tr>
											<?php if($row['cto'] == "Bank Transfer") { ?>
											<tr>
												<td><span class="pull-left">Name</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_3']; ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left">Location</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_4']; ?></span></td>
											</tr>	
											<tr>
												<td><span class="pull-left">Bank name</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_5']; ?></span></td>
											</tr>	
											<tr>
												<td><span class="pull-left">Bank account Number/IBAN</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_6']; ?></span></td>
											</tr>	
											<tr>
												<td><span class="pull-left">Bank SWIFT</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_7']; ?></span></td>
											</tr>	
											<?php } elseif($row['cto'] == "Western Union" or $row['cto'] == "Moneygram") { ?>
											<tr>
												<td><span class="pull-left">Name</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_3']; ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left">Location</span></td>
												<td><span class="pull-right"><?php echo $row['u_field_4']; ?></span></td>
											</tr>
											<?php } else { ?>
											<tr>
												<td><span class="pull-left">User <?php echo $row['cto']; ?> <?php if($row['cto'] == "Bitcoin" or $row['cto'] == "Litecoin" or $row['cto'] == "Dogecoin") { echo 'address'; } else { echo 'account'; } ?></span></td>
												<td><span class="pull-right"><?php echo $row['u_field_1']; ?></span></td>
											</tr>
											<?php } ?>
											<tr>
												<td colspan="2"><span class="pull-right">Payout amount: <?php echo $row['amount_to']; ?> <?php echo $row['currency_to']; ?></span></td>
											</tr>
										</table>
									</div>
								</div>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">Update status</div>
						<div class="panel-body">
							<script type="text/javascript">
								function checkStatus(id) {
									if	(id == "4") {
										$("#pinshowhide").show();
									} else {
										$("#pinshowhide").hide();
									}
									
									if (id == "6") {
										$("#reasonshowhide").show();
									} else {
										$("#reasonshowhide").hide();
									}
								}
							</script>
							
							<?php if($row['status'] == "4") { ?>
								You've already processed this order!
							<?php } elseif($row['status'] == "5") { ?>
								The time for which the consumer had to pay the order has expired. You can not change this status.
							<?php } elseif($row['status'] == "6") { ?>
								This order is refused. I.e. You must give back user money to their <?php echo $row['cfrom']; ?> account.
							<?php } elseif($row['status'] == "7") { ?>
								The order has been cancaled by user.
							<?php } else { ?>
							<form action="" method="POST">
								<div class="form-group">
									<label>Status</label>
									<select name="status" class="form-control" onchange="checkStatus(this.value);">
										<option value="1" <?php if($row['status'] == "1") { echo 'selected'; } ?>>Awaiting Confirmation</option>
										<option value="2" <?php if($row['status'] == "2") { echo 'selected'; } ?>>Awaiting Payment</option>
										<option value="3" <?php if($row['status'] == "3") { echo 'selected'; } ?>>Processing</option>
										<option value="4" <?php if($row['status'] == "4") { echo 'selected'; } ?>>Processed</option>
										<option value="6" <?php if($row['status'] == "6") { echo 'selected'; } ?>>Refused</option>
									</select>
								</div>
								<?php if($row['cto'] == "Western Union" or $row['cto'] == "Moneygram") { ?>
								<div class="form-group" id="pinshowhide" style="display:none;">
									<label><?php echo $row['cto']; ?> PIN</label>
									<input type="text" class="form-control" name="pin">
								</div>
								<?php } ?>
								<div class="form-group" id="reasonshowhide" style="display:none;">
									<label>Reason</label>
									<textarea class="form-control" rows="5" name="reason" placeholder="The reason why refuse exchange"></textarea>
								</div>
								<button type="submit" class="btn btn-primary" name="btn_update"><i class="fa fa-check"></i> Update</button>
							</form>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>