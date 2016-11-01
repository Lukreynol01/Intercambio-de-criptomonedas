    <!-- Header -->
<script type="text/javascript">
	function borrar() {
                    $(':text').val('0');    
		}
</script>
<!--<script type="text/javascript">
function padDigits1(value) {
    if (document.getElementById("#mainCompanySend") === "Bitcoin" ){
        b = Array(Math.max(2 - String(value).length + 1, 0)).join(0) + value;
        $('#mainAmountSend').val(b);
    }
}
function padDigits2(value) {
        if (document.getElementById("#mainReceiveList") === "Bitcoin" ){
        b = Array(Math.max(2 - String(value).length + 1, 0)).join(0) + value;
        $('#mainAmountReceive').val(b);
    }
}
</script>-->
    <header class="cover">
        <div class="container"><h2 class="intro blue-text white-shadow">Invierte para tu futuro con Bitcoin</h2>
<h3 class="blue-text white-shadow">Tu camino hacia la libertad financiera comienza aqu&iacute;</h3>
<div class="row">
				<div class="col-md-6">
					<h2><?php echo $lang['want_to_sell']; ?></h2>
					<div class="input-group input-group-lg">
					  <span class="input-group-addon" id="sizing-addon1">
						<img src="assets/icons/PayPal.png" id="sellCompanyImage" width="25px" height="25px" style="padding:0px;margin:0px;float:left;"> 
						<select style="background:none;border:none;" id="mainCompanySend"  onchange="borrar();mainChangeSellCompany(this.value);">
							<?php
							$i=1;
							$query = $db->query("SELECT * FROM ec_companies WHERE allow_send='1' ORDER BY id");
							if($query->num_rows>0) {
								while($row = $query->fetch_assoc()) {
								    if($i == 1) {
										$first = array();
										$first['id'] = $row['id'];
										$first['name'] = $row['name'];
										$first['receive_list'] = $row['receive_list'];
										$explode = explode(",",$first['receive_list']);
										$first['receive'] = $explode[0];
									}
									echo '<option value="'.$row[name].'">'.$row[name].'</option>';
									$i++;
								}
							} else {
								echo '<option value="">NaN</option>';
							}
							?>
						</select>
					  </span>
					  <input type="text" class="form-control input-lg"  value="0" id="mainAmountSend" onkeyup="mainChangeSellValue(this.value);" onfocus="selecciona_value(this)">
					  <span class="input-group-addon" id="sizing-addon1">
						<select style="background:none;border:none;" id="mainCurrencySend" onchange="mainChangeSellCurrency(this.value);">
							<option value="USD">USD</option>
						</select>
					  </span>
					</div><!-- /input-group -->
                                </div>
				<div class="col-md-6">
					<h2><?php echo $lang['want_to_buy']; ?></h2>
					<div class="input-group input-group-lg">
					  <span class="input-group-addon" id="sizing-addon1">
						<img src="assets/icons/Bitcoin.png" id="buyCompanyImage" width="25px" height="25px" style="padding:0px;margin:0px;float:left;">
						<select style="background:none;border:none;" id="mainReceiveList" onchange="mainChangeBuyCompany(this.value);clearfield();">
							<?php
							$list3 = explode(",",$first['receive_list']);
							foreach($list3 as $l) {
								if (strpos($l,'//') !== false) { } else {
									if($l == "Bitcoin") { $sel = 'selected'; } else { $sel = ''; }
									echo '<option value="'.$l.'" '.$sel.'>'.$l.'</option>';
								}
							}
							?>
						</select>
					  </span>
					  <input type="text" class="form-control input-lg"  value="0" id="mainAmountReceive" onkeyup="mainChangeBuyValue(this.value);" onfocus="selecciona_value(this)">
					  <span class="input-group-addon" id="sizing-addon1">
						<select style="background:none;border:none;" id="mainCurrencyReceive" onchange="mainChangeBuyCurrency(this.value);">
							<option value="BTC">BTC</option>
						</select>
					  </span>
					</div><!-- /input-group -->
				</div>
                <div class="col-md-12">

					<div class="row">
						<div class="col-md-6 text-right">
							<h5 class="text-muted"><?php echo $lang['reserve']; ?>: <span id="mainReserve">-</span></h5>
						</div>
						<div class="col-md-6 text-left">
							<h5 class="text-muted"><?php echo $lang['rate']; ?>: <span id="mainRate">-</span></h5>
						</div>
					</div>
					<br><?php if(checkSession()) { $myslang = $lang['btn_exchange'];
                                        $datatarget = "#exchange_form";
                                        } else {$myslang = $lang['Create my account'];
                                        $datatarget = "";
                                        $href = "location.href='register';";}  ?>
					<center>
						<button type="button" onclick="<?php echo $href; ?>" class="btn btn-primary btn-lg" id="exchange_button" data-toggle="modal" data-target="<?php echo $datatarget; ?>"><i class="fa fa-refresh"></i> <?php echo $myslang; ?></button>
					</center>
                </div>
            </div>
        </div>
    </header>
	
	<!-- exchange popup -->
<!--    <link href="<?php echo $settings['url']; ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">-->
    <div class="portfolio-modal modal fade" id="exchange_form" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                                <!-- Bootstrap Core JavaScript -->

<!--                            				<script src="<?php //echo $settings['url']; ?>assets/js/jquery-1.12.3.js"></script>
							<script src="<?php //echo $settings['url']; ?>assets/js/bootstrap.min.js"></script>
							<script src="<?php// echo $settings['url']; ?>assets/js/functions.js"></script>-->

							<div id="exchange_payment"></div>
							<div id="exchange_confirm"></div>
							<div id="exchange_results">
								<div id="validate_results"></div>
								<form id="exchange_data_form">
								 <h3><?php echo $lang['want_to_sell']; ?></h3>
									<div class="input-group">
									  <span class="input-group-addon" id="sizing-addon1">
										<img src="assets/icons/PayPal.png" id="sellCompanyImage" width="20px" height="20px" style="padding:0px;margin:0px;"> 
										<span id="formCompanyFrom">PayPal</span>
									  </span>
									  <input type="text" class="form-control" id="amount_from" disabled value="0">
									  <span class="input-group-addon" id="sizing-addon1">
										<span id="formCurrencyFrom">USD</span>
									  </span>
									</div><!-- /input-group -->

									<h3><?php echo $lang['want_to_buy']; ?></h3>
									<div class="input-group">
									  <span class="input-group-addon" id="sizing-addon1">
										<img src="assets/icons/Bitcoin.png" id="buyCompanyImage" width="20px" height="20px" style="padding:0px;margin:0px;"> 
										<span id="formCompanyTo">Bitcoin</span>
									  </span>
									  <input type="text" class="form-control" id="amount_to" disabled value="0">
									  <span class="input-group-addon" id="sizing-addon1">
										<span id="formCurrencyTo">BTC</span>
									  </span>
									</div><!-- /input-group -->
									<input type="hidden" id="company_from" name="company_from">
									<input type="hidden" id="amount_from" name="amount_from">
									<input type="hidden" id="currency_from" name="currency_from">
									<input type="hidden" id="company_to" name="company_to">
									<input type="hidden" id="amount_to" name="amount_to">
									<input type="hidden" id="currency_to" name="currency_to">
									
									<div id="formAccount">
										<h3><?php echo $lang['your']; ?> <span id="formCompanyTo">Bitcoin</span> <span id="accAddress"><?php echo $lang['address']; ?></span><span id="accAccount" style="display:none;"><?php echo $row['account']; ?></span></h3>
										<input type="text" class="form-control" name="u_field_1">
									</div>
									<div id="formBank" style="display:none;">
										<h3><?php echo $lang['your_name']; ?></h3>
										<input type="text" class="form-control" name="u_field_3" id="s_name"> 
										
										<h3><?php echo $lang['your_location']; ?></h3>
										<input type="text" class="form-control" name="u_field_4" id="s_location"> 
										
										<h3><?php echo $lang['bank_name']; ?></h3>
										<input type="text" class="form-control" name="u_field_5" id="s_bankname"> 
										
										<h3><?php echo $lang['bank_account_number']; ?></h3>
										<input type="text" class="form-control" name="u_field_6" id="s_bankiban"> 
										
										<h3><?php echo $lang['bank_swift']; ?></h3>
										<input type="text" class="form-control" name="u_field_7" id="s_bankswift"> 
									</div>
									<div id="formWM" style="display:none;">
										<h3><?php echo $lang['your_name']; ?></h3>
										<input type="text" class="form-control" name="u_field_3" id="s_name"> 
										
										<h3><?php echo $lang['your_location']; ?></h3>
										<input type="text" class="form-control" name="u_field_4" id="s_location"> 
										
									</div>
									
									<h3><?php echo $lang['your_email_address']; ?></h3>
									<input type="text" class="form-control" name="u_field_2">
									<input type="hidden" id="url" value="<?php echo $settings['url']; ?>">
									<br>
									<button type="button" class="btn btn-primary btn-lg" id="exchange_button" onclick="validateForm();"><i class="fa fa-refresh"></i> <?php echo $lang['btn_exchange']; ?></button>
                                                                        <div class="panel-body">De preferencia envianos un correo a <a href="mailto:admin@intermoneda.com?Subject=Aviso%20de%20compra" target="_top">admin@intermoneda.com</a> avisando tu compra con tu numero ID, para dar seguimiento a tu compra.</div>
                                                                </form>
                                                                
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- exchange popup -->

	<div align="center">
        <img src="<?php echo $settings['url']; ?>assets/imgs/banner/btc.png" width="120" height="90" alt="bitcoin" border="0"></a>
	<img src="<?php echo $settings['url']; ?>assets/imgs/banner/mc.png" width="120" height="90" alt="master card" border="0"></a>
	<img src="<?php echo $settings['url']; ?>assets/imgs/banner/payeer.png" width="120" height="90" alt="payeer" border="0"></a>
	<img src="<?php echo $settings['url']; ?>assets/imgs/banner/paypal.png" width="120" height="90" alt="paypal" border="0"></a>
	<img src="<?php echo $settings['url']; ?>assets/imgs/banner/logo-mp.png" width="75" height="50" alt="MercadoPago" border="0"></a>
	<img src="<?php echo $settings['url']; ?>assets/imgs/banner/visa.png" width="120" height="90" alt="visa" border="0"></a>
        <img src="<?php echo $settings['url']; ?>assets/imgs/banner/litecoin_logo.png" width="120" height="90" alt="litecoin" border="0"></a>
        <img src="<?php echo $settings['url']; ?>assets/imgs/banner/logo-okpay.png" width="150" height="50" alt="okpay" border="0"></a>
        
	</div>
    <section>
        <div class="container">
            
            <div class="row">
               				<div class="col-md-8">
					<div class="panel panel-default">
           
          <!-- Chart -->  
       
    <div id="chart_div" style="width: 100%; height: 500px;"></div> 
          <!-- Chart -->                                  
						<div class="panel-body">
							<h4><?php echo $lang['latest_exchanges']; ?></h4>
							<table class="table table-hover">
								<thead>
									<tr>
										<th><?php echo $lang['from']; ?></th>
										<th><?php echo $lang['to']; ?></th>
										<th><?php echo $lang['amount']; ?></th>
										<th>Exchange ID</th>
										<th><?php echo $lang['status']; ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
								$query = $db->query("SELECT * FROM ec_exchanges ORDER BY id DESC LIMIT 10");
								if($query->num_rows>0) {
									while($row = $query->fetch_assoc()) {
									?>
									<tr>
										<td><?php echo getIcon($row['cfrom'],"20px","20px"); ?> <?php echo $row['cfrom']; ?></td>
										<td><?php echo getIcon($row['cto'],"20px","20px"); ?> <?php echo $row['cto']; ?></td>
										<td><?php echo $row['amount_from']; ?> <?php echo $row['currency_from']; ?></td>
										<td>
											<a href="<?php echo $settings['url']; ?>exchange/<?php echo $row['exchange_id']; ?>">
												<?php
												$string = $row['exchange_id'];
												if(strlen($string) > 20) $string = substr($string, 0, 20).'...';
												echo $string;
												?>
											</a>
										</td>
										<td><?php echo decodeStatus($row['status']); ?></td>
									</tr>
									<?php
									}
								} else {
									echo '<tr><td colspan="5">'.$lang[still_no_exchanges].'</td></tr>';
								}
								?>
								</tbody>
							</table>
						</div>	
                                            
                                            
                                            
                                            
                                          </div>  
   
					
                                    <div class="fb-comments" data-href="<?php echo $settings['url']; ?>" data-width="100%" data-numposts="8" order-by = "social" data-order-by = "social"></div>
                                   
				</div>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-body">
							<h4><?php echo $lang['track_exchange']; ?></h4>
							<?php echo $lang['info_4']; ?>
							<br><br>
								<div class="input-group">
								  <input type="text" class="form-control" id="track_id" placeholder="Exchange ID">
								  <span class="input-group-btn">
									<button class="btn btn-default" onclick="trackExchange();" type="button"><?php echo $lang['btn_track']; ?></button>
								  </span>
								</div><!-- /input-group -->
						</div>	
					</div>
					
					<div class="panel panel-default">
						<div class="panel-body">
							<h4><?php echo $lang['testimonials']; ?></h4>
							<?php
							$query = $db->query("SELECT * FROM ec_testimonials WHERE status='1' ORDER BY RAND() LIMIT 1");
							if($query->num_rows>0) {
								$row = $query->fetch_assoc();
								?>
								<blockquote>
								  <p><?php echo $row['content']; ?></p>
								  <footer><?php echo idinfo($row['uid'],"username"); ?></footer>
								</blockquote>
								<a href="<?php echo $settings['url']; ?>testimonials"><?php echo $lang['view_all']; ?></a>
								<?php
							} else {
								echo $lang['no_testimonials'];
							}
							?>
						</div>	
					</div>
                                        <div class="panel panel-default">
						<div class="panel-body">
							
                                                                  <a class="twitter-timeline"  href="https://twitter.com/hashtag/bitcoin" data-widget-id="749367552061550592">Tweets sobre #bitcoin</a>
                                                                   <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          
						</div>	
					</div>
				</div>
			</div>
        </div>
    </section>
        	<!-- exchange banner -->
