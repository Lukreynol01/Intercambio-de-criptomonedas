<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$exchange_id = protect($_GET['exchange_id']);
							$query = $db->query("SELECT * FROM ec_exchanges WHERE exchange_id='$exchange_id'");
							?>
							<h4>Exchange ID: <?php echo $exchange_id; ?></h4>
							<?php
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								?>
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
												<td><span class="pull-left"><?php echo $lang['sell_e_currency']; ?></span></td>
												<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left"><?php echo $lang['buy_e_currency']; ?></span></td>
												<td><span class="pull-right"><?php echo $row['amount_to']; ?> <?php echo $row['currency_to']; ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left"><?php echo $lang['exchange_rate']; ?></span></td>
												<td><span class="pull-right"><?php echo $row['rate_from']; ?> <?php echo $row['currency_from']; ?> = <?php echo $row['rate_to']; ?> <?php echo $row['currency_to']; ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left"><?php echo $lang['user']; ?></span></td>
												<td><span class="pull-right"><?php if($row['uid']>0) { if($_SESSION['ec_uid'] == $row['uid']) { echo idinfo($row['uid'],"username"); } else { $string = idinfo($row['uid'],"username"); if(strlen($string) > 3) $string = substr($string, 0, 3).'**********'; echo $string; } } else { echo 'Anonymous'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left"><?php echo $lang['user_ip_address']; ?></span></td>
												<td><span class="pull-right"><?php if($row['ip']) { if($_SESSION['ec_uid'] == $row['uid']) { echo $row['ip']; } else { $string = $row['ip']; if(strlen($string) > 3) $string = substr($string, 0, 3).'***.***.**'; echo $string; } } else { echo '-'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left"><?php echo $lang['transaction_id']; ?></span></td>
												<td><span class="pull-right"><?php if($row['transaction_id']) { if($_SESSION['ec_uid'] == $row['uid']) { echo $row['transaction_id']; } else { $string = $row['transaction_id']; if(strlen($string) > 6) $string = substr($string, 0, 6).'**********'; echo $string; } } else { echo '-'; } ?></span></td>
											</tr>  
										</table>
									</div>
									<div class="col-md-6">
										<table class="table table-striped">
											<tr>
												<td><span class="pull-left"><?php echo $lang['created_on']; ?></span></td>
												<td><span class="pull-right"><?php if($row['created']>0) { echo date("d/m/Y H:i:s",$row['created']); } else { echo '-'; } ?></span></td>
											</tr>
											<tr>
												<td><span class="pull-left"><?php echo $lang['updated_on']; ?></span></td>
												<td><span class="pull-right"><?php if($row['updated']>0) { echo date("d/m/Y H:i:s",$row['updated']); } else { echo '-'; } ?></span></td>
											</tr> 
											<tr>
												<td><span class="pull-left"><?php echo $lang['expired_on']; ?></span></td>
												<td><span class="pull-right"><?php if($row['expired']>0) { echo date("d/m/Y H:i:s",$row['expired']); } else { echo '-'; } ?></span></td>
											</tr> 
										</table>
										<?php
										if(checkSession()) {
											if($_SESSION['ec_uid'] == $row['uid']) {
												if($row['status'] == "2") {
													?>
													<a href="<?php echo $settings['url']; ?>become_payment/<?php echo $row['id']; ?>" class="btn btn-primary"><?php echo $lang['btn_become_payment']; ?></a>
													<?php
												}
											}
										}
										?>
									</div>
								</div>
								<?php
							} else {
								echo error($lang['error_2']);
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>