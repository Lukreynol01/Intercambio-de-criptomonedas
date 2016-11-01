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
$amount = protect($_GET['amount']);
$currency = protect($_GET['currency']);
$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$id'");
if($query->num_rows>0) {
	$time = time();
	$update = $db->query("UPDATE ec_exchanges SET status='2',updated='$time' WHERE id='$id'");
	$row = $query->fetch_assoc();
	$accQuery = $db->query("SELECT * FROM ec_companies WHERE name='$row[cfrom]'");
	$acc = $accQuery->fetch_assoc();
	if($row['cfrom'] == "PayPal") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
 		include("../includes/paypal_class.php");
		define('EMAIL_ADD', $acc['a_field_1']); // For system notification.
		define('PAYPAL_EMAIL_ADD', $acc['a_field_1']);
	
		// Setup class
		$p = new paypal_class( ); 				 // initiate an instance of the class.
		$p -> admin_mail = EMAIL_ADD; 
		$this_script = $settings['url']."payment.php?b=check&c=paypal&eid=".$row[id];
		$p->add_field('business', PAYPAL_EMAIL_ADD); //don't need add this item. if your set the $p -> paypal_mail.
		$p->add_field('return', $this_script.'&action=success');
		$p->add_field('cancel_return', $this_script.'&action=cancel');
		$p->add_field('notify_url', $this_script.'&action=ipn');
		$p->add_field('item_name', 'Exchange '.$amount.' '.$currency);
		$p->add_field('item_number', $row['id']);
		$p->add_field('amount', $amount);
		$p->add_field('currency_code', $currency);
		$p->add_field('cmd', '_xclick');
		$p->add_field('rm', '2');	// Return method = POST
						 
		$p->submit_paypal_post(); // submit the fields to paypal
		$return = '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#paypal_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "Skrill") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$return = '<div style="display:none;"><form action="https://www.moneybookers.com/app/payment.pl" method="post" id="skrill_form">
					  <input type="hidden" name="pay_to_email" value="'.$acc[a_field_1].'"/>
					  <input type="hidden" name="status_url" value="'.$settings[url].'payment.php?b=check&c=skrill"/> 
					  <input type="hidden" name="language" value="EN"/>
					  <input type="hidden" name="amount" value="'.$amount.'"/>
					  <input type="hidden" name="currency" value="'.$currency.'"/>
					  <input type="hidden" name="detail1_description" value="Exchange '.$amount.' '.$currency.'"/>
					  <input type="hidden" name="detail1_text" value="'.$row[id].'"/>
					  <input type="submit" class="btn btn-primary" value="Click to pay."/>
					</form></div>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#skrill_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "WebMoney") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		include("../includes/webmoney.inc.php");
		$paymentno = intval($row['id']);
		$wm_request = new WM_Request();
		  $wm_request->payment_amount = $amount;
		  $wm_request->payment_desc = 'Exchange '.$amount.' '.$currency;
		  $wm_request->payment_no = $paymentno;
		  $wm_request->payee_purse = $acc['a_field_1'];
		  $wm_request->sim_mode = WM_ALL_SUCCESS;
		  $wm_request->result_url = $settings['url']."payment.php?b=check&c=webmoney&d=result";
		  $wm_request->success_url = $settings['url']."payment.php?b=check&c=webmoney&d=success";
		  $wm_request->success_method = WM_POST;
		  $wm_request->fail_url = $settings['url']."payment.php?b=check&c=webmoney&d=fail";
		  $wm_request->fail_method = WM_POST;
		  $wm_request->extra_fields = array('FIELD1'=>'VALUE 1', 'FIELD2'=>'VALUE 2');
		  $wm_action = 'https://merchant.wmtransfer.com/lmi/payment.asp';
		  $wm_btn_label = 'Pay Webmoney';
		  $wm_request->SetForm();
			$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#webmoney_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		echo $return;
	} elseif($row['cfrom'] == "Payeer") {
                emailsys_new_exchange($row['u_field_2'],$row['id']);
		$m_shop = $acc['a_field_1'];
		$m_orderid = $get['id'];
		$m_amount = number_format($amount, 2, '.', '');
		$m_curr = $currency;
		$desc = 'Exchange '.$amount.' '.$currency;
		$m_desc = base64_encode($desc);
		$m_key = $acc['a_field_2'];

		$arHash = array(
			$m_shop,
			$m_orderid,
			$m_amount,
			$m_curr,
			$m_desc,
			$m_key
		);
		$sign = strtoupper(hash('sha256', implode(':', $arHash)));
		$return = '<div style="display:none;"><form method="GET" id="payeer_form" action="https://payeer.com/merchant/">
		<input type="hidden" name="m_shop" value="'.$m_shop.'">
		<input type="hidden" name="m_orderid" value="'.$m_orderid.'">
		<input type="hidden" name="m_amount" value="'.$m_amount.'">
		<input type="hidden" name="m_curr" value="'.$m_curr.'">
		<input type="hidden" name="m_desc" value="'.$m_desc.'">
		<input type="hidden" name="m_sign" value="'.$sign.'">
		<!--
		<input type="hidden" name="form[ps]" value="2609">
		<input type="hidden" name="form[curr[2609]]" value="USD">
		-->
		<input type="submit" name="m_process" value="Pay with Payeer" />
		</form></div>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#payeer_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
            } elseif($row['cfrom'] == "MercadoPago") {
                                include("../includes/mpago_class.php");
 
                                emailsys_new_exchange($row['u_field_2'],$row['id']);
                                 $return = '<form action="https://www.mercadopago.com/checkout/init" id="mpago_form" method="post" enctype="application/x-www-form-urlencoded" target="" >
                                     <!-- Autenticacion y hash MD5 -->
                                        <input type="hidden" name="client_id" value="8800231436497281" />
                                        <input type="hidden" name="md5" value="'.$md5.'"/>

                                    <!-- Datos obligatorios del item -->
                                        <input type="hidden" name="item_title" value="'.$desc.'"/>
                                        <input type="hidden" name="item_quantity" value="1"/>
                                        <input type="hidden" name="item_currency_id" value="'.$currency.'"/>
                                        <input type="hidden" name="item_unit_price" value="'.$amount.'"/>

                                    <!-- Datos opcionales -->
                                        <input type="hidden" name="item_id" value="'.$orderid.'"/>
                                    <!--<input type="hidden" name="external_reference" value=""/>

                                        <input type="hidden" name="payer_name" value=""/>
                                        <input type="hidden" name="payer_surname" value=""/> -->
                                        <input type="hidden" name="payer_email" value="'.$m_shop.'"/>
                                        <input type="hidden" name="back_url_success" value= "'.$RETURNURL.'"/>
                                   <!-- Boton de pago clase de boton siguiente: lightblue-rn-m-tr   o  lightblue-M-Ov-ArOn-->
                                        <div style="text-align:left"><button  type="submit"  class="lightblue-M-Ov-ArOn" name="MP-Checkout">Pagar</button></div>
                                            </form>' ;
                                
                                $return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
                                $return .= '<script type="text/javascript">$(document).ready(function() { $("#mpago_form").submit(); });</script>';
                                $return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
                                echo $return;
                                
	} elseif($row['cfrom'] == "Perfect Money") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$return = '<div style="display:none;">
				<form action="https://perfectmoney.is/api/step1.asp" id="pm_form" method="POST">
					<input type="hidden" name="PAYEE_ACCOUNT" value="'.$acc[a_field_1].'">
					<input type="hidden" name="PAYEE_NAME" value="'.$settings[name].'">
					<input type="hidden" name="PAYMENT_ID" value="'.$row[id].'">
					<input type="text"   name="PAYMENT_AMOUNT" value="'.$amount.'"><BR>
					<input type="hidden" name="PAYMENT_UNITS" value="'.$currency.'">
					<input type="hidden" name="STATUS_URL" value="'.$settings[url].'payment.php?b=check&c=perfectmoney&d=status">
					<input type="hidden" name="PAYMENT_URL" value="'.$settings[url].'payment.php?b=check&c=perfectmoney&d=complete">
					<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="NOPAYMENT_URL" value="'.$settings[url].'payment.php?b=check&c=perfectmoney&d=failed">
					<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="SUGGESTED_MEMO" value="">
					<input type="hidden" name="BAGGAGE_FIELDS" value="IDENT"><br>
					<input type="submit" name="PAYMENT_METHOD" value="Pay Now!" class="tabeladugme"><br><br>
					</form></div>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#pm_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "OKPay") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$return = '<form  method="post" id="okpay_form" action="https://checkout.okpay.com/">
					   <input type="hidden" name="ok_receiver" value="'.$acc[a_field_1].'"/>
					   <input type="hidden" name="ok_item_1_name" value="Exchange '.$amount.' '.$currency.'"/>
					   <input type="hidden" name="ok_item_1_price" value="'.$amount.'"/>
					   <input type="hidden" name="ok_item_1_id" value="'.$row[id].'"/>
					   <input type="hidden" name="ok_currency" value="'.$currency.'"/>
					</form>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#okpay_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "AdvCash") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$arHash = array(
			$acc[a_field_1],
			$settings[name],
			$amount,
			$currency,
			$acc[a_field_2],
			$row[id]
		);
		$sign = strtoupper(hash('sha256', implode(':', $arHash)));
		$return = '<div style="display:none;">
					<form method="GET" id="advcash_form" action="https://wallet.advcash.com/sci/">
					<input type="hidden" name="ac_account_email" value="'.$acc[a_field_1].'">
					<input type="hidden" name="ac_sci_name" value="'.$settings[name].'">
					<input type="hidden" name="ac_amount" value="'.$amount.'">
					<input type="hidden" name="ac_currency" value="'.$currency.'">
					<input type="hidden" name="ac_order_id" value="'.$row[id].'">
					<input type="hidden" name="ac_sign"
					value="'.$sign.'">
					<input type="hidden" name="ac_success_url" value="'.$settings[url].'payment.php?b=check&c=advcash&d=success" />
					 <input type="hidden" name="ac_success_url_method" value="GET" />
					 <input type="hidden" name="ac_fail_url" value="'.$settings[url].'payment.php?b=check&c=advcash&d=fail" />
					 <input type="hidden" name="ac_fail_url_method" value="GET" />
					 <input type="hidden" name="ac_status_url" value="'.$settings[url].'payment.php?b=check&c=advcash&d=status" />
					 <input type="hidden" name="ac_status_url_method" value="GET" />
					<input type="hidden" name="ac_comments" value="Exchange '.$amount.' '.$currency.'">
					</form>
					</div>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#advcash_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "Entromoney") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		include("../includes/entromoney.php");
		$config = array();
		$config['sci_user'] = $acc['a_field_1'];
		$config['sci_id'] 	= $acc['a_field_2'];
		$config['sci_pass'] = $acc['a_field_3'];
		$config['receiver'] = $acc['a_field_4'];

		// Call lib sci
		try {
			$sci = new Paygate_Sci($config);
		}
		catch (Paygate_Exception $e) {
			exit($e->getMessage());
		}
		$return = '';
		$input = array();
		$input['sci_user'] 		= $config['sci_user'];
		$input['sci_id'] 		= $config['sci_id'];
		$input['receiver'] 		= $config['receiver'];
		$input['amount'] 		= $amount;
		$input['desc'] 			= 'Exchange '.$amount.' '.$currency;
		$input['payment_id'] 	= $row['id'];
		$input['up_1'] 			= 'user_param_1';
		$input['up_2'] 			= 'user_param_2';
		$input['up_3'] 			= 'user_param_3';
		$input['up_4'] 			= 'user_param_4';
		$input['up_5'] 			= 'user_param_5';
		$input['url_status'] 	= $settings[url].'payment.php?b=check&c=entromoney&d=status';
		$input['url_success'] 	= $settings[url].'payment.php?b=check&c=entromoney&d=success';
		$input['url_fail'] 		= $settings[url].'payment.php?b=check&c=entromoney&d=fail';

		// Create hash
		$input['hash']			= $sci->create_hash($input);
		?>
		<form action="<?php echo Paygate_Sci::URL_SCI; ?>" id="entromoney_form" method="post">
			<?php foreach ($input as $p => $v): ?>
				<input type="hidden" name="<?php echo $p; ?>" value="<?php echo $v; ?>">
			<?php endforeach; ?>
		</form>
		<?php
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#entromoney_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "Payza") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$return = '<form method="post" id="payza_form" action="https://secure.payza.com/checkout" >
				<input type="hidden" name="ap_merchant" value="'.$acc[a_field_1].'"/>
				<input type="hidden" name="ap_purchasetype" value="item-goods"/>
				<input type="hidden" name="ap_itemname" value="Exchange '.$amount.' '.$currency.'"/>
				<input type="hidden" name="ap_amount" value="'.$amount.'"/>
				<input type="hidden" name="ap_currency" value="'.$currency.'"/>

				<input type="hidden" name="ap_quantity" value="1"/>
				<input type="hidden" name="ap_itemcode" value="'.$row[id].'"/>
				<input type="hidden" name="ap_description" value=""/>
				<input type="hidden" name="ap_returnurl" value="'.$settings[url].'payment.php?b=check&c=payza&d=results"/>
				<input type="hidden" name="ap_cancelurl" value="'.$settings[url].'payment.php?b=check&c=payza&d=cancel"/>

				<input type="hidden" name="ap_taxamount" value="0"/>
				<input type="hidden" name="ap_additionalcharges" value="0"/>
				<input type="hidden" name="ap_shippingcharges" value="0"/> 

				<input type="hidden" name="ap_discountamount" value="0"/> 
				<input type="hidden" name="apc_1" value="Blue"/>

			</form>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#payza_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "SolidTrust Pay") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		$return = ' <form action="https://solidtrustpay.com/handle.php" method="post" id="solid_form">
						<input type=hidden name="merchantAccount" value="'.$acc[a_field_1].'" />
						<input type="hidden" name="sci_name" value="'.$acc[a_field_2].'">
						<input type="hidden" name="amount" value="'.$amount.'">
						<input type=hidden name="currency" value="'.$currency.'" />
						 <input type="hidden" name="notify_url" value="'.$settings[url].'payment.php?b=check&c=solidtrustpay&d=notify">
						  <input type="hidden" name="confirm_url" value="'.$settings[url].'payment.php?b=check&c=solidtrustpay&d=confirm">
						   <input type="hidden" name="return_url" value="'.$settings[url].'payment.php?b=check&c=solidtrustpay&d=return">
						<input type=hidden name="item_id" value="Exchange: '.$amount.' '.$currency.'" />
						<input type=hidden name="user1" value="'.$row[id].'" />
					  </form>';
		$return .= '<script type="text/javascript" src="'.$settings[url].'assets/js/jquery-1.10.2.js"></script>';
		$return .= '<script type="text/javascript">$(document).ready(function() { $("#solid_form").submit(); });</script>';
		$return .= '<div id="processing"><i class="fa fa-spin fa-refresh" style="font-size:90px;"></i><br/><h3>'.$lang[processing].'</h3></div>';
		echo $return;
	} elseif($row['cfrom'] == "Bank Transfer") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
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
											<td><span class="pull-left"><?php echo $lang['amount']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['description']; ?></span></td>
											<td><span class="pull-right">Exchange: <?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2"><h4><?php echo $lang['data_about_transfer']; ?></h4></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_holder_name']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_1']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_holder_location']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_2']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_name']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_3']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_account_number']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_4']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['bank_swift']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_5']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['our_email_address']; ?></span></td>
											<td><span class="pull-right"><?php echo $settings['email']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2">
												<span class="pull-left">
												<?php echo $lang['info_1']; ?>
												</span>
											</td>
										</tr>
									</table>
		<?php
	} elseif($row['cfrom'] == "Western Union") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
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
											<td><span class="pull-left"><?php echo $lang['amount']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['description']; ?></span></td>
											<td><span class="pull-right">Exchange: <?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2"><h4><?php echo $lang['data_about_transfer']; ?></h4></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['name']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_1']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['location']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_2']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['our_email_address']; ?></span></td>
											<td><span class="pull-right"><?php echo $settings['email']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2">
												<span class="pull-left">
												<?php echo $lang['info_2']; ?>
												</span>
											</td>
										</tr>
									</table>
		<?php
	} elseif($row['cfrom'] == "Moneygram") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
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
											<td><span class="pull-left"><?php echo $lang['amount']; ?></span></td>
											<td><span class="pull-right"><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['description']; ?></span></td>
											<td><span class="pull-right">Exchange: <?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2"><h4><?php echo $lang['data_about_transfer']; ?></h4></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['name']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_1']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['location']; ?></span></td>
											<td><span class="pull-right"><?php echo $acc['a_field_2']; ?></span></td>
										</tr>
										<tr>
											<td><span class="pull-left"><?php echo $lang['our_email_address']; ?></span></td>
											<td><span class="pull-right"><?php echo $settings['email']; ?></span></td>
										</tr>
										<tr>
											<td colspan="2">
												<span class="pull-left">
												<?php echo $lang['info_3']; ?>
												</span>
											</td>
										</tr>
									</table>
		<?php
	} elseif($row['cfrom'] == "Bitcoin") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		include( "../includes/cryptobox.class.php" );

	$options = array( 
	"public_key"  => $acc['a_field_1'], 		// place your public key from gourl.io
	"private_key" => $acc['a_field_2'], 		// place your private key from gourl.io
	"webdev_key" => "", 		// optional, gourl affiliate key
	"orderID"     => $row['id'], // few your users can have the same orderID but combination 'orderID'+'userID' should be unique. 
								// for example, on premium page you can use for all visitors: orderID="premium" and userID="" (empty).
	"userID" 	  => $row['uid'], 		// optional; when userID value is empty - system will autogenerate unique identifier for every user and save it in cookies
	"userFormat"  => "COOKIE", 	// save your user identifier userID in cookies. Available values: COOKIE, SESSION, IPADDRESS, MANUAL 
	"amount" 	  => $amount,			// amount in cryptocurrency or in USD below
	"amountUSD"   => 0,  		// price is 2 USD; it will convert to cryptocoins amount, using Live Exchange Rates
								// For convert fiat currencies Euro/GBP/etc. to USD, use function convert_currency_live() 
	"period"      => "24 HOUR",	// payment valid period, after 1 day user need to pay again
	"iframeID"    => "",    	// optional; when iframeID value is empty - system will autogenerate iframe html payment box id
	"language" 	  => "EN" 		// english, please contact us and we can add your language	
	);  
	// IMPORTANT: Please read description of options here - https://gourl.io/api-php.html#options  

	
	// Initialise Payment Class
	$box1 = new Cryptobox ($options);

	// Display Payment Box or successful payment result   
	$paymentbox = $box1->display_cryptobox();

	// Log
	$message = "";
	
	// A. Process Received Payment
	if ($box1->is_paid()) 
	{ 
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";
		
		$message .= "<br>".$box1->amount_paid()." ".$box1->coin_label()."  received<br>";
		
		// Your code here to handle a successful cryptocoin payment/captcha verification
		// For example, give user 24 hour access to your member pages
		// Please use also IPN function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "") for update db records, etc
		// ...
	}  
	else $message .= "The payment has not been made yet";

	
	// B. One-time Process Received Payment
	if ($box1->is_paid() && !$box1->is_processed()) 
	{
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";	
	
		// Your code here - for example, publish order number for user
		// ...

		// Also you can use $box1->is_confirmed() - return true if payment confirmed 
		// Average transaction confirmation time - 10-20min for 6 confirmations  
		
		// Set Payment Status to Processed
		$box1->set_status_processed(); 
		
		// Optional, cryptobox_reset() will delete cookies/sessions with userID and 
		// new cryptobox with new payment amount will be show after page reload.
		// Cryptobox will recognize user as a new one with new generated userID
		// $box1->cryptobox_reset(); 
	}
        function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "")
        {
			$tx_id = $payment_details['tx'];
			$order = $payment_details['order'];
			$date = date("d/m/Y H:i");
			$ip = $_SERVER['REMOTE_ADDR'];
			$time = time();
			$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$order'");
			if($query->num_rows>0) {
				$row = $query->fetch_assoc();
				$checkTransaction = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$tx_id' and company='Bitcoin'");
				if($checkTransaction->num_rows==0) {
					$insert = $db->query("INSERT ec_transactions (uid,ip,payee,txn_id,company,amount,currency,date) VALUES ('$row[uid]','$ip','0','$tx_id','Bitcoin','$amount','$currency','$date')");
					$update = $db->query("UPDATE ec_exchanges SET status='3',updated='$time',transaction_id='$tx_id' WHERE id='$row[id]'");
				}
			}
			return true;
         }
		 echo '<script src="'.$settings[url].'assets/cryptobox/cryptobox.min.js" type="text/javascript"></script>';
		echo $paymentbox;
		echo $message;
	} elseif($row['cfrom'] == "Litecoin") {
	emailsys_new_exchange($row['u_field_2'],$row['id']);
		include( "../includes/cryptobox.class.php" );

	$options = array( 
	"public_key"  => $acc['a_field_1'], 		// place your public key from gourl.io
	"private_key" => $acc['a_field_2'], 		// place your private key from gourl.io
	"webdev_key" => "", 		// optional, gourl affiliate key
	"orderID"     => $row['id'], // few your users can have the same orderID but combination 'orderID'+'userID' should be unique. 
								// for example, on premium page you can use for all visitors: orderID="premium" and userID="" (empty).
	"userID" 	  => $row['uid'], 		// optional; when userID value is empty - system will autogenerate unique identifier for every user and save it in cookies
	"userFormat"  => "COOKIE", 	// save your user identifier userID in cookies. Available values: COOKIE, SESSION, IPADDRESS, MANUAL 
	"amount" 	  => $amount,			// amount in cryptocurrency or in USD below
	"amountUSD"   => 0,  		// price is 2 USD; it will convert to cryptocoins amount, using Live Exchange Rates
								// For convert fiat currencies Euro/GBP/etc. to USD, use function convert_currency_live() 
	"period"      => "24 HOUR",	// payment valid period, after 1 day user need to pay again
	"iframeID"    => "",    	// optional; when iframeID value is empty - system will autogenerate iframe html payment box id
	"language" 	  => "EN" 		// english, please contact us and we can add your language	
	);  
	// IMPORTANT: Please read description of options here - https://gourl.io/api-php.html#options  

	
	// Initialise Payment Class
	$box1 = new Cryptobox ($options);

	// Display Payment Box or successful payment result   
	$paymentbox = $box1->display_cryptobox();

	// Log
	$message = "";
	
	// A. Process Received Payment
	if ($box1->is_paid()) 
	{ 
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";
		
		$message .= "<br>".$box1->amount_paid()." ".$box1->coin_label()."  received<br>";
		
		// Your code here to handle a successful cryptocoin payment/captcha verification
		// For example, give user 24 hour access to your member pages
		// Please use also IPN function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "") for update db records, etc
		// ...
	}  
	else $message .= "The payment has not been made yet";

	
	// B. One-time Process Received Payment
	if ($box1->is_paid() && !$box1->is_processed()) 
	{
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";	
	
		// Your code here - for example, publish order number for user
		// ...

		// Also you can use $box1->is_confirmed() - return true if payment confirmed 
		// Average transaction confirmation time - 10-20min for 6 confirmations  
		
		// Set Payment Status to Processed
		$box1->set_status_processed(); 
		
		// Optional, cryptobox_reset() will delete cookies/sessions with userID and 
		// new cryptobox with new payment amount will be show after page reload.
		// Cryptobox will recognize user as a new one with new generated userID
		// $box1->cryptobox_reset(); 
	}
        function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "")
        {
			$tx_id = $payment_details['tx'];
			$order = $payment_details['order'];
			$date = date("d/m/Y H:i");
			$ip = $_SERVER['REMOTE_ADDR'];
			$time = time();
			$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$order'");
			if($query->num_rows>0) {
				$row = $query->fetch_assoc();
				$checkTransaction = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$tx_id' and company='Litecoin'");
				if($checkTransaction->num_rows==0) {
					$insert = $db->query("INSERT ec_transactions (uid,ip,payee,txn_id,company,amount,currency,date) VALUES ('$row[uid]','$ip','0','$tx_id','Litecoin','$amount','$currency','$date')");
					$update = $db->query("UPDATE ec_exchanges SET status='3',updated='$time',transaction_id='$tx_id' WHERE id='$row[id]'");
				}
			}
			return true;
         }
		 echo '<script src="'.$settings[url].'assets/cryptobox/cryptobox.min.js" type="text/javascript"></script>';
		echo $paymentbox;
		echo $message;
	} elseif($row['cfrom'] == "Dogecoin") {
		emailsys_new_exchange($row['u_field_2'],$row['id']);
		include( "../includes/cryptobox.class.php" );

	$options = array( 
	"public_key"  => $acc['a_field_1'], 		// place your public key from gourl.io
	"private_key" => $acc['a_field_2'], 		// place your private key from gourl.io
	"webdev_key" => "", 		// optional, gourl affiliate key
	"orderID"     => $row['id'], // few your users can have the same orderID but combination 'orderID'+'userID' should be unique. 
								// for example, on premium page you can use for all visitors: orderID="premium" and userID="" (empty).
	"userID" 	  => $row['uid'], 		// optional; when userID value is empty - system will autogenerate unique identifier for every user and save it in cookies
	"userFormat"  => "COOKIE", 	// save your user identifier userID in cookies. Available values: COOKIE, SESSION, IPADDRESS, MANUAL 
	"amount" 	  => $amount,			// amount in cryptocurrency or in USD below
	"amountUSD"   => 0,  		// price is 2 USD; it will convert to cryptocoins amount, using Live Exchange Rates
								// For convert fiat currencies Euro/GBP/etc. to USD, use function convert_currency_live() 
	"period"      => "24 HOUR",	// payment valid period, after 1 day user need to pay again
	"iframeID"    => "",    	// optional; when iframeID value is empty - system will autogenerate iframe html payment box id
	"language" 	  => "EN" 		// english, please contact us and we can add your language	
	);  
	// IMPORTANT: Please read description of options here - https://gourl.io/api-php.html#options  

	
	// Initialise Payment Class
	$box1 = new Cryptobox ($options);

	// Display Payment Box or successful payment result   
	$paymentbox = $box1->display_cryptobox();

	// Log
	$message = "";
	
	// A. Process Received Payment
	if ($box1->is_paid()) 
	{ 
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";
		
		$message .= "<br>".$box1->amount_paid()." ".$box1->coin_label()."  received<br>";
		
		// Your code here to handle a successful cryptocoin payment/captcha verification
		// For example, give user 24 hour access to your member pages
		// Please use also IPN function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "") for update db records, etc
		// ...
	}  
	else $message .= "The payment has not been made yet";

	
	// B. One-time Process Received Payment
	if ($box1->is_paid() && !$box1->is_processed()) 
	{
		$message .= "Thank you! We receive your payment. Will be notified by email when order was processed.";	
	
		// Your code here - for example, publish order number for user
		// ...

		// Also you can use $box1->is_confirmed() - return true if payment confirmed 
		// Average transaction confirmation time - 10-20min for 6 confirmations  
		
		// Set Payment Status to Processed
		$box1->set_status_processed(); 
		
		// Optional, cryptobox_reset() will delete cookies/sessions with userID and 
		// new cryptobox with new payment amount will be show after page reload.
		// Cryptobox will recognize user as a new one with new generated userID
		// $box1->cryptobox_reset(); 
	}
        function cryptobox_new_payment($paymentID = 0, $payment_details = array(), $box_status = "")
        {
			$tx_id = $payment_details['tx'];
			$order = $payment_details['order'];
			$date = date("d/m/Y H:i");
			$ip = $_SERVER['REMOTE_ADDR'];
			$time = time();
			$query = $db->query("SELECT * FROM ec_exchanges WHERE id='$order'");
			if($query->num_rows>0) {
				$row = $query->fetch_assoc();
				$checkTransaction = $db->query("SELECT * FROM ec_transactions WHERE txn_id='$tx_id' and company='Dogecoin'");
				if($checkTransaction->num_rows==0) {
					$insert = $db->query("INSERT ec_transactions (uid,ip,payee,txn_id,company,amount,currency,date) VALUES ('$row[uid]','$ip','0','$tx_id','Dogecoin','$amount','$currency','$date')");
					$update = $db->query("UPDATE ec_exchanges SET status='3',updated='$time',transaction_id='$tx_id' WHERE id='$row[id]'");
				}
			}
			return true;
         }
		 echo '<script src="'.$settings[url].'assets/cryptobox/cryptobox.min.js" type="text/javascript"></script>';
		echo $paymentbox;
		echo $message;
	} else {
		echo error($lang['error_34']);
	}
} else {
	echo error($lang['error_32']);
}
?>