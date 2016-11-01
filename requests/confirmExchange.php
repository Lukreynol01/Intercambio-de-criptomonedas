<?php
ob_start();
session_start();
error_reporting(0);
include("../includes/config.php");
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");
$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
$settings = $settingsQuery->fetch_assoc();
include("../includes/functions.php");
include(getLanguage($settings['url'],null,2));
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$id'");
if($query->num_rows>0) {
	$row = $query->fetch_assoc();
	$accQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
	$acc = $accQuery->fetch_assoc();
	if($acc['include_fee'] == "1") {
		if (strpos($acc['fee'],'%') !== false) { 
			$amount = $row['amount_from'];
			$explode = explode("%",$acc['fee']);
			$fee_percent = 100+$explode[0];
			$new_amount = ($amount * 100) / $fee_percent;
			$new_amount = round($new_amount,2);
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
	?>
								<div>
									<table class="table table-striped">
										<tr>
											<td colspan="2"><h4><?php echo $row['cfrom']; ?> <i class="fa fa-exchange"></i> <?php echo $row['cto']; ?></h4></td>
										</tr>
										<tr>
											<td><span class="pull-left"><b>Exchange ID</b></span></td>
											<td><span class="pull-right"><b><?php echo $row['exchange_id']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['you_will_sell']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['you_will_buy']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['amount_to']; ?> <?php echo $row['currency_to']; ?></span></td>
										</tr>
										<?php if($row['cto'] == "Bitcoin" or $row['cto'] == "Litecoin" or $row['cto'] == "Dogecoin") { ?>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your']; ?> <?php echo $row['cto']; ?> <?php echo $lang['address']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_1']; ?></span></td>
										</tr>
										<?php } elseif($row['cto'] == "Western Union" or $row['cto'] == "Moneygram") { ?>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your_name']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_3']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your_location']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_4']; ?></span></td>
										</tr>
										<?php } elseif($row['cto'] == "Bank Transfer") { ?>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your_name']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_3']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your_location']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_4']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_name']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_5']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_account_number']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_6']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_swift']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_7']; ?></span></td>
										</tr>
										<?php } else { ?>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your']; ?> <?php echo $row['cto']; ?> <?php echo $lang['account']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_1']; ?></span></td>
										</tr>
										<?php } ?>
										<tr>
											<td><span class="pull-left"><?php echo $lang['your_email_address']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['u_field_2']; ?></span></td>
										</tr>
									</table>
									<br>
									<table class="table table-striped">
										<tr>
											<td><span class="pull-left"><?php echo $row['cfrom']; ?> <?php echo $lang['fee']; ?></span></td>
											<td><span class="pull-right"><?php echo $fee_text; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['total_for_payment']; ?></span></td>
											<td><span class="pull-right"><?php echo $amount; ?> <?php echo $currency; ?></span></td>
										</tr>
									</table>
									<div class="row">
										<div class="col-sm-6 col-md-6 col-lg-6">
											<button type="button" class="btn btn-success btn-block" onclick="makeExchange('<?php echo $row['id']; ?>','<?php echo $amount; ?>','<?php echo $currency; ?>');"><i class="fa fa-check"></i> <?php echo $lang['btn_confirm_order']; ?></button>
											<br>
										</div>
										<div class="col-sm-6 col-md-6 col-lg-6">
											<button type="button" class="btn btn-danger btn-block" onclick="cancelExchange('<?php echo $row['id']; ?>');"><i class="fa fa-times"></i> <?php echo $lang['btn_cancel_order']; ?></button>
											<br>
										</div>
									</div>
								</div>
	<?php
} else {
	echo error($lang['error_32']);
}
?>