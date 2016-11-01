<?php
$cid = protect($_GET['cid']);
$query = $db->query("SELECT * FROM ec_companies WHERE id='$cid'");
if($query->num_rows==0) { header("Location: ./?a=companies"); }
$row = $query->fetch_assoc();
?>
	<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['name']; ?> / Account</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if($row['name'] == "PayPal") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									if(empty($a_field_1)) { echo error("Please enter your PayPal email address."); }
									elseif(!isValidEmail($a_field_1)) { echo error("Please enter valid PayPal email address."); }
									elseif(empty($a_field_2)) { echo error("Please enter your API Username."); }
									elseif(empty($a_field_3)) { echo error("Please enter your API Password."); }
									elseif(empty($a_field_4)) { echo error("Please enter your API Signature."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your PayPal email address</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>API Username</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>API Password</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>API Signature</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Skrill") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your Skrill email address."); }
									elseif(!isValidEmail($a_field_1)) { echo error("Please enter valid Skrill email address."); }
									elseif(empty($a_field_2)) { echo error("Please enter your Skrill merchant secret."); } 
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your Skrill email address</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Your Skrill merchant secret</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "WebMoney") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									if(empty($a_field_1)) { echo error("Please enter your WebMoney account."); }
									elseif(strlen($a_field_1)<12) { echo error("Please enter valid WebMoney account."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your WebMoney account</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "MercadoPago") { 
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your CLIENT_ID:."); }
									//elseif(strlen($a_field_1)<8) { echo error("Please enter valid Payeer account."); }
									elseif(empty($a_field_2)) { echo error("Please enter your CLIENT_SECRET:"); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>CLIENT_ID de MercadoPago</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>CLIENT_SECRET de MercadoPago</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
                                                              }  elseif($row['name'] == "Payeer") { 
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your Payeer account."); }
									elseif(strlen($a_field_1)<8) { echo error("Please enter valid Payeer account."); }
									elseif(empty($a_field_2)) { echo error("Please enter your Payeer secret key."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your Payeer account</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Secret key</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Perfect Money") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									if(empty($a_field_1)) { echo error("Please enter your Perfect Money account."); }
									elseif(strlen($a_field_1)<7) { echo error("Please enter valid Perfect Money account."); }
									elseif(empty($a_field_3)) { echo error("Please enter your Perfect Money Account ID or API Name."); }
									elseif(empty($a_field_2)) { echo error("Please enter valid Perfect Money passpharce."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your Perfect Money account</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Account ID or API NAME</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>Passpharse</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
										<small>Alternate Passphrase you entered in your Perfect Money account.</small>
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "OKPay") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									if(empty($a_field_1)) { echo error("Please enter your OKPay account."); }
									elseif(strlen($a_field_1)<12) { echo error("Please enter valid OKPay account."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your OKPay account</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "AdvCash") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									if(empty($a_field_1)) { echo error("Please enter your AdvCash email address."); }
									elseif(!isValidEmail($a_field_1)) { echo error("Please enter valid AdvCash email address."); }
									elseif(empty($a_field_2)) { echo error("Please enter API name."); }
									elseif(empty($a_field_3)) { echo error("Please enter API password."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your AdvCash email address</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>(For payout function) API Name</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>(For payout function) API Password</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Entromoney") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									$a_field_5 = protect($_POST['a_field_5']);
									$a_field_6 = protect($_POST['a_field_6']);
									if(empty($a_field_1)) { echo error("Please enter your account id."); }
									elseif(empty($a_field_2)) { echo error("Please enter your receiver."); }
									elseif(empty($a_field_3)) { echo error("Please enter SCI ID."); }
									elseif(empty($a_field_4)) { echo error("Please enter SCI PASS."); }
									elseif(empty($a_field_5)) { echo error("Please enter API ID."); }
									elseif(empty($a_field_6)) { echo error("Please enter API PASS."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4',a_field_5='$a_field_5',a_field_6='$a_field_6' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your Entromoney Account ID</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Your Entromoney Receiver (Example: U11111111 or E1111111)</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>SCI ID</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>SCI PASS</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<div class="form-group">
										<label>API ID</label>
										<input type="text" class="form-control" name="a_field_5" value="<?php echo $row['a_field_5']; ?>">
									</div>
									<div class="form-group">
										<label>API PASS</label>
										<input type="text" class="form-control" name="a_field_6" value="<?php echo $row['a_field_6']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Payza") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your Payza email address."); }
									elseif(empty($a_field_2)) { echo error("Please enter your Payza Merchant IPN SECURITY CODE."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_2='$a_field_2',a_field_1='$a_field_1' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your Payza address</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>IPN SECURITY CODE</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "SolidTrust Pay") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									if(empty($a_field_1)) { echo error("Please enter your SolidTrust Pay account."); }
									elseif(empty($a_field_2)) { echo error("Please enter your SolidTrust Pay SCI Name."); }
									elseif(empty($a_field_2)) { echo error("Please enter your SolidTrust Pay SCI Password."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_3='$a_field_3',a_field_2='$a_field_2',a_field_1='$a_field_1' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your SolidTrust Pay address</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>SCI Name</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>SCI Password</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Bank Transfer") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									$a_field_5 = protect($_POST['a_field_5']);
									if(empty($a_field_1) or empty($a_field_2) or empty($a_field_3) or empty($a_field_4) or empty($a_field_5)) { echo error("Please complete all fields."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4',a_field_5='$a_field_5' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Bank Account Holder's Name</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1'] ?>">
									</div>
									<div class="form-group">
										<label>Bank Account Number/IBAN</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<div class="form-group">
										<label>SWIFT Code</label>
										<input type="text" class="form-control" name="a_field_5" value="<?php echo $row['a_field_5']; ?>">
									</div>
									<div class="form-group">
										<label>Bank Name in Full</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>Bank Branch Country, City, Address</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Western Union") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your full name."); }
									elseif(empty($a_field_2)) { echo error("Please enter your location."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your full name</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Your location</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Moneygram") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									if(empty($a_field_1)) { echo error("Please enter your full name."); }
									elseif(empty($a_field_2)) { echo error("Please enter your location."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<form action="" method="POST">
									<div class="form-group">
										<label>Your full name</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Your location</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Bitcoin") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									if(empty($a_field_1)) { echo error("Please enter your PaymentBox Public Key."); }
									elseif(empty($a_field_2)) { echo error("Please enter your PaymentBox Private Key."); }
									elseif(empty($a_field_3)) { echo error("Please enter your API KEY."); }
									elseif(empty($a_field_4)) { echo error("Please enter your PIN."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<small>To get API Public and Private key need to register account in <a href="https://gourl.io">www.gourl.io</a>, and to create Payment Box for Bitcoin. System will generate automaticlly Public and Private key and you must enter it in form below.</small>
								<form action="" method="POST">
									<div class="form-group">
										<label>Public Key</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Private Key</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) API KEY (Get it from <a href="https://block.io/">https://block.io/</a>)</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) PIN (Your Block.io PIN)</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Litecoin") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									if(empty($a_field_1)) { echo error("Please enter your PaymentBox Public Key."); }
									elseif(empty($a_field_2)) { echo error("Please enter your PaymentBox Private Key."); }
									elseif(empty($a_field_3)) { echo error("Please enter your API KEY."); }
									elseif(empty($a_field_4)) { echo error("Please enter your PIN."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<small>To get API Public and Private key need to register account in <a href="https://gourl.io">www.gourl.io</a>, and to create Payment Box for Litecoin. System will generate automaticlly Public and Private key and you must enter it in form below.</small>
								<form action="" method="POST">
									<div class="form-group">
										<label>Public Key</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Private Key</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) API KEY (Get it from <a href="https://block.io/">https://block.io/</a>)</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) PIN (Your Block.io PIN)</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} elseif($row['name'] == "Dogecoin") {
								if(isset($_POST['btn_setup'])) {
									$a_field_1 = protect($_POST['a_field_1']);
									$a_field_2 = protect($_POST['a_field_2']);
									$a_field_3 = protect($_POST['a_field_3']);
									$a_field_4 = protect($_POST['a_field_4']);
									if(empty($a_field_1)) { echo error("Please enter your PaymentBox Public Key."); }
									elseif(empty($a_field_2)) { echo error("Please enter your PaymentBox Private Key."); }
									elseif(empty($a_field_3)) { echo error("Please enter your API KEY."); }
									elseif(empty($a_field_4)) { echo error("Please enter your PIN."); }
									else {
										$update = $db->query("UPDATE ec_companies SET a_field_1='$a_field_1',a_field_2='$a_field_2',a_field_3='$a_field_3',a_field_4='$a_field_4' WHERE id='$row[id]'");
										header("Location: ./?a=companies");
									}
								}
								?>
								<small>To get API Public and Private key need to register account in <a href="https://gourl.io">www.gourl.io</a>, and to create Payment Box for Dogecoin. System will generate automaticlly Public and Private key and you must enter it in form below.</small>
								<form action="" method="POST">
									<div class="form-group">
										<label>Public Key</label>
										<input type="text" class="form-control" name="a_field_1" value="<?php echo $row['a_field_1']; ?>">
									</div>
									<div class="form-group">
										<label>Private Key</label>
										<input type="text" class="form-control" name="a_field_2" value="<?php echo $row['a_field_2']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) API KEY (Get it from <a href="https://block.io/">https://block.io/</a>)</label>
										<input type="text" class="form-control" name="a_field_3" value="<?php echo $row['a_field_3']; ?>">
									</div>
									<div class="form-group">
										<label>(for Payout function) PIN (Your Block.io PIN)</label>
										<input type="text" class="form-control" name="a_field_4" value="<?php echo $row['a_field_4']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_setup"><i class="fa fa-cog"></i> Setup</button>
								</form>
								<?php
							} else {
								header("Location: ./?a=companies");
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>