<?php
$cid = protect($_GET['cid']);
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_currencies WHERE id='$id'");
if($query->num_rows==0) { header("Location: ./?a=companies&b=currencies&cid=$cid"); }
$row = $query->fetch_assoc();
$companyQuery = $db->query("SELECT * FROM ec_companies WHERE id='$row[cid]'");
$row2 = $companyQuery->fetch_assoc();
?> 
<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Companies / <?php echo $row['company_from']; ?> / Currencies / Edit</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<script type="text/javascript">
								function changeCur1(value) {
									$("#cur_from").html(value);
								}
								
								function changeCur2(value) {
									$("#cur_to").html(value);
								}
								
								function autoSelect(value) {
									if(value == "Bitcoin") {
										$("#currency_to").val("BTC");
										changeCur2("BTC");
									} else if(value == "Litecoin") {
										$("#currency_to").val("LTC");
										changeCur2("LTC");
									} else if(value == "Dogecoin") {
										$("#currency_to").val("DOGE");
										changeCur2("DOGE");
									} else { }
								}
							</script>
							
							<?php
						if(isset($_POST['btn_save'])) {
							$cid = $row['id'];
							$currency_from = protect($_POST['currency_from']);
							$currency_to = protect($_POST['currency_to']);
							$company_from = $row['company_from'];
							$company_to = protect($_POST['company_to']);
							$rate_from = protect($_POST['rate_from']);
							$rate_to = protect($_POST['rate_to']);
							$reserve = protect($_POST['reserve']);
							$check = $db->query("SELECT * FROM ec_currencies WHERE cid='$cid' and company_from='$company_from' and company_to='$company_to' and currency_from='$currency_from' and currency_to='$currency_to'");
							if(empty($currency_from) or empty($currency_to) or empty($company_from) or empty($company_to) or empty($rate_from) or empty($rate_to) or empty($reserve)) { echo error("All fields are required."); }
							elseif(!is_numeric($rate_from)) { echo error("Please enter exchange rate with numbers. Eg: 0.99 , 1.23 and etc."); }
							elseif(!is_numeric($rate_to)) { echo error("Please enter exchange rate with numbers. Eg: 0.99 , 1.23 and etc."); }
							elseif(!is_numeric($reserve)) { echo error("Please enter reserve with numbers."); }
							else {
								$update = $db->query("UPDATE ec_currencies SET company_from='$company_from',company_to='$company_to',currency_from='$currency_from',currency_to='$currency_to',rate_from='$rate_from',rate_to='$rate_to',reserve='$reserve' WHERE id='$id'");
								$query = $db->query("SELECT * FROM ec_currencies WHERE id='$id'");
								$row = $query->fetch_assoc();
								echo success("Your changes was saved successfully.");
							}
						}
						?>
						<form action="" method="POST">
							<div class="form-group">
								<label>From</label>
								<input type="text" class="form-control" name="company_from" disabled value="<?php echo $row['company_from']; ?>">
							</div>
							<div class="form-group">
								<label>To</label>
								<select class="form-control" name="company_to" onchange="autoSelect(this.value);">
									<?php
									$list = explode(",",$row2['receive_list']);
									foreach($list as $l) {
										if (strpos($l,'//') !== false) { } else {
											if($row['company_to'] == $l) { $sel = 'selected'; } else { $sel = ''; }
											echo '<option value="'.$l.'" '.$sel.'>'.$l.'</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>From currency</label>
								<select class="form-control" name="currency_from" onchange="changeCur1(this.value);">
									<?php if($row['name'] == "Bitcoin") { ?>
										<option value="BTC">BTC - Bitcoin</option>
									<?php } elseif($row['name'] == "Litecoin") { ?>
										<option value="LTC">LTC - Litecoin</option>
									<?php } elseif($row['name'] == "Dogecoin") { ?>
										<option value="DOGE">DOGE - Dogecoin</option>
									<?php } else { ?>
											<option value="">----- Coins -----</option>
											<option value="BTC" <?php if($row['currency_from'] == "BTC") { echo 'selected'; } ?>>BTC - Bitcoin</option>
											<option value="LTC" <?php if($row['currency_from'] == "LTC") { echo 'selected'; } ?>>LTC - Litecoin</option>
											<option value="DOGE" <?php if($row['currency_from'] == "DOGE") { echo 'selected'; } ?>>DOGE - Dogecoin</option>
											<option value="">----- Valuta -----</option>
											<option value="AED" <?php if($row['currency_from'] == "AED") { echo 'selected'; } ?>>AED - United Arab Emirates Dirham</option>
											<option value="AFN" <?php if($row['currency_from'] == "AFN") { echo 'selected'; } ?>>AFN - Afghanistan Afghani</option>
											<option value="ALL" <?php if($row['currency_from'] == "ALL") { echo 'selected'; } ?>>ALL - Albania Lek</option>
											<option value="AMD" <?php if($row['currency_from'] == "AMD") { echo 'selected'; } ?>>AMD - Armenia Dram</option>
											<option value="ANG" <?php if($row['currency_from'] == "ANG") { echo 'selected'; } ?>>ANG - Netherlands Antilles Guilder</option>
											<option value="AOA" <?php if($row['currency_from'] == "AOA") { echo 'selected'; } ?>>AOA - Angola Kwanza</option>
											<option value="ARS" <?php if($row['currency_from'] == "ARS") { echo 'selected'; } ?>>ARS - Argentina Peso</option>
											<option value="AUD" <?php if($row['currency_from'] == "AUD") { echo 'selected'; } ?>>AUD - Australia Dollar</option>
											<option value="AWG" <?php if($row['currency_from'] == "AWG") { echo 'selected'; } ?>>AWG - Aruba Guilder</option>
											<option value="AZN" <?php if($row['currency_from'] == "AZN") { echo 'selected'; } ?>>AZN - Azerbaijan New Manat</option>
											<option value="BAM" <?php if($row['currency_from'] == "BAM") { echo 'selected'; } ?>>BAM - Bosnia and Herzegovina Convertible Marka</option>
											<option value="BBD" <?php if($row['currency_from'] == "BBD") { echo 'selected'; } ?>>BBD - Barbados Dollar</option>
											<option value="BDT" <?php if($row['currency_from'] == "BDT") { echo 'selected'; } ?>>BDT - Bangladesh Taka</option>
											<option value="BGN" <?php if($row['currency_from'] == "BGN") { echo 'selected'; } ?>>BGN - Bulgaria Lev</option>
											<option value="BHD" <?php if($row['currency_from'] == "BHD") { echo 'selected'; } ?>>BHD - Bahrain Dinar</option>
											<option value="BIF" <?php if($row['currency_from'] == "BIF") { echo 'selected'; } ?>>BIF - Burundi Franc</option>
											<option value="BMD" <?php if($row['currency_from'] == "BMD") { echo 'selected'; } ?>>BMD - Bermuda Dollar</option>
											<option value="BND" <?php if($row['currency_from'] == "BND") { echo 'selected'; } ?>>BND - Brunei Darussalam Dollar</option>
											<option value="BOB" <?php if($row['currency_from'] == "BOB") { echo 'selected'; } ?>>BOB - Bolivia Boliviano</option>
											<option value="BRL" <?php if($row['currency_from'] == "BRL") { echo 'selected'; } ?>>BRL - Brazil Real</option>
											<option value="BSD" <?php if($row['currency_from'] == "BSD") { echo 'selected'; } ?>>BSD - Bahamas Dollar</option>
											<option value="BTN" <?php if($row['currency_from'] == "BTN") { echo 'selected'; } ?>>BTN - Bhutan Ngultrum</option>
											<option value="BWP" <?php if($row['currency_from'] == "BWP") { echo 'selected'; } ?>>BWP - Botswana Pula</option>
											<option value="BYR" <?php if($row['currency_from'] == "BYR") { echo 'selected'; } ?>>BYR - Belarus Ruble</option>
											<option value="BZD" <?php if($row['currency_from'] == "BZD") { echo 'selected'; } ?>>BZD - Belize Dollar</option>
											<option value="CAD" <?php if($row['currency_from'] == "CAD") { echo 'selected'; } ?>>CAD - Canada Dollar</option>
											<option value="CDF" <?php if($row['currency_from'] == "CDF") { echo 'selected'; } ?>>CDF - Congo/Kinshasa Franc</option>
											<option value="CHF" <?php if($row['currency_from'] == "CHF") { echo 'selected'; } ?>>CHF - Switzerland Franc</option>
											<option value="CLP" <?php if($row['currency_from'] == "CLP") { echo 'selected'; } ?>>CLP - Chile Peso</option>
											<option value="CNY" <?php if($row['currency_from'] == "CNY") { echo 'selected'; } ?>>CNY - China Yuan Renminbi</option>
											<option value="COP" <?php if($row['currency_from'] == "COP") { echo 'selected'; } ?>>COP - Colombia Peso</option>
											<option value="CRC" <?php if($row['currency_from'] == "CRC") { echo 'selected'; } ?>>CRC - Costa Rica Colon</option>
											<option value="CUC" <?php if($row['currency_from'] == "CUC") { echo 'selected'; } ?>>CUC - Cuba Convertible Peso</option>
											<option value="CUP" <?php if($row['currency_from'] == "CUP") { echo 'selected'; } ?>>CUP - Cuba Peso</option>
											<option value="CVE" <?php if($row['currency_from'] == "CVE") { echo 'selected'; } ?>>CVE - Cape Verde Escudo</option>
											<option value="CZK" <?php if($row['currency_from'] == "CZK") { echo 'selected'; } ?>>CZK - Czech Republic Koruna</option>
											<option value="DJF" <?php if($row['currency_from'] == "DJF") { echo 'selected'; } ?>>DJF - Djibouti Franc</option>
											<option value="DKK" <?php if($row['currency_from'] == "DKK") { echo 'selected'; } ?>>DKK - Denmark Krone</option>
											<option value="DOP" <?php if($row['currency_from'] == "DOP") { echo 'selected'; } ?>>DOP - Dominican Republic Peso</option>
											<option value="DZD" <?php if($row['currency_from'] == "DZD") { echo 'selected'; } ?>>DZD - Algeria Dinar</option>
											<option value="EGP" <?php if($row['currency_from'] == "EGP") { echo 'selected'; } ?>>EGP - Egypt Pound</option>
											<option value="ERN" <?php if($row['currency_from'] == "ERN") { echo 'selected'; } ?>>ERN - Eritrea Nakfa</option>
											<option value="ETB" <?php if($row['currency_from'] == "ETB") { echo 'selected'; } ?>>ETB - Ethiopia Birr</option>
											<option value="EUR" <?php if($row['currency_from'] == "EUR") { echo 'selected'; } ?>>EUR - Euro Member Countries</option>
											<option value="FJD" <?php if($row['currency_from'] == "FJD") { echo 'selected'; } ?>>FJD - Fiji Dollar</option>
											<option value="FKP" <?php if($row['currency_from'] == "FKP") { echo 'selected'; } ?>>FKP - Falkland Islands (Malvinas) Pound</option>
											<option value="GBP" <?php if($row['currency_from'] == "GBP") { echo 'selected'; } ?>>GBP - United Kingdom Pound</option>
											<option value="GEL" <?php if($row['currency_from'] == "GEL") { echo 'selected'; } ?>>GEL - Georgia Lari</option>
											<option value="GGP" <?php if($row['currency_from'] == "GGP") { echo 'selected'; } ?>>GGP - Guernsey Pound</option>
											<option value="GHS" <?php if($row['currency_from'] == "GHS") { echo 'selected'; } ?>>GHS - Ghana Cedi</option>
											<option value="GIP" <?php if($row['currency_from'] == "GIP") { echo 'selected'; } ?>>GIP - Gibraltar Pound</option>
											<option value="GMD" <?php if($row['currency_from'] == "GMD") { echo 'selected'; } ?>>GMD - Gambia Dalasi</option>
											<option value="GNF" <?php if($row['currency_from'] == "GNF") { echo 'selected'; } ?>>GNF - Guinea Franc</option>
											<option value="GTQ" <?php if($row['currency_from'] == "GTQ") { echo 'selected'; } ?>>GTQ - Guatemala Quetzal</option>
											<option value="GYD" <?php if($row['currency_from'] == "GYD") { echo 'selected'; } ?>>GYD - Guyana Dollar</option>
											<option value="HKD" <?php if($row['currency_from'] == "HKD") { echo 'selected'; } ?>>HKD - Hong Kong Dollar</option>
											<option value="HNL" <?php if($row['currency_from'] == "HNL") { echo 'selected'; } ?>>HNL - Honduras Lempira</option>
											<option value="HPK" <?php if($row['currency_from'] == "HPK") { echo 'selected'; } ?>>HRK - Croatia Kuna</option>
											<option value="HTG" <?php if($row['currency_from'] == "HTG") { echo 'selected'; } ?>>HTG - Haiti Gourde</option>
											<option value="HUF" <?php if($row['currency_from'] == "HUF") { echo 'selected'; } ?>>HUF - Hungary Forint</option>
											<option value="IDR" <?php if($row['currency_from'] == "IDR") { echo 'selected'; } ?>>IDR - Indonesia Rupiah</option>
											<option value="ILS" <?php if($row['currency_from'] == "ILS") { echo 'selected'; } ?>>ILS - Israel Shekel</option>
											<option value="IMP" <?php if($row['currency_from'] == "IMP") { echo 'selected'; } ?>>IMP - Isle of Man Pound</option>
											<option value="INR" <?php if($row['currency_from'] == "INR") { echo 'selected'; } ?>>INR - India Rupee</option>
											<option value="IQD" <?php if($row['currency_from'] == "IQD") { echo 'selected'; } ?>>IQD - Iraq Dinar</option>
											<option value="IRR" <?php if($row['currency_from'] == "IRR") { echo 'selected'; } ?>>IRR - Iran Rial</option>
											<option value="ISK" <?php if($row['currency_from'] == "ISK") { echo 'selected'; } ?>>ISK - Iceland Krona</option>
											<option value="JEP" <?php if($row['currency_from'] == "JEP") { echo 'selected'; } ?>>JEP - Jersey Pound</option>
											<option value="JMD" <?php if($row['currency_from'] == "JMD") { echo 'selected'; } ?>>JMD - Jamaica Dollar</option>
											<option value="JOD" <?php if($row['currency_from'] == "JOD") { echo 'selected'; } ?>>JOD - Jordan Dinar</option>
											<option value="JPY" <?php if($row['currency_from'] == "JPY") { echo 'selected'; } ?>>JPY - Japan Yen</option>
											<option value="KES" <?php if($row['currency_from'] == "KES") { echo 'selected'; } ?>>KES - Kenya Shilling</option>
											<option value="KGS" <?php if($row['currency_from'] == "KGS") { echo 'selected'; } ?>>KGS - Kyrgyzstan Som</option>
											<option value="KHR" <?php if($row['currency_from'] == "KHR") { echo 'selected'; } ?>>KHR - Cambodia Riel</option>
											<option value="KMF" <?php if($row['currency_from'] == "KMF") { echo 'selected'; } ?>>KMF - Comoros Franc</option>
											<option value="KPW" <?php if($row['currency_from'] == "KPW") { echo 'selected'; } ?>>KPW - Korea (North) Won</option>
											<option value="KRW" <?php if($row['currency_from'] == "KRW") { echo 'selected'; } ?>>KRW - Korea (South) Won</option>
											<option value="KWD" <?php if($row['currency_from'] == "KWD") { echo 'selected'; } ?>>KWD - Kuwait Dinar</option>
											<option value="KYD" <?php if($row['currency_from'] == "KYD") { echo 'selected'; } ?>>KYD - Cayman Islands Dollar</option>
											<option value="KZT" <?php if($row['currency_from'] == "KZT") { echo 'selected'; } ?>>KZT - Kazakhstan Tenge</option>
											<option value="LAK" <?php if($row['currency_from'] == "LAK") { echo 'selected'; } ?>>LAK - Laos Kip</option>
											<option value="LBP" <?php if($row['currency_from'] == "LBP") { echo 'selected'; } ?>>LBP - Lebanon Pound</option>
											<option value="LKR" <?php if($row['currency_from'] == "LKR") { echo 'selected'; } ?>>LKR - Sri Lanka Rupee</option>
											<option value="LRD" <?php if($row['currency_from'] == "LRD") { echo 'selected'; } ?>>LRD - Liberia Dollar</option>
											<option value="LSL" <?php if($row['currency_from'] == "LSL") { echo 'selected'; } ?>>LSL - Lesotho Loti</option>
											<option value="LYD" <?php if($row['currency_from'] == "LYD") { echo 'selected'; } ?>>LYD - Libya Dinar</option>
											<option value="MAD" <?php if($row['currency_from'] == "MAD") { echo 'selected'; } ?>>MAD - Morocco Dirham</option>
											<option value="MDL" <?php if($row['currency_from'] == "MDL") { echo 'selected'; } ?>>MDL - Moldova Leu</option>
											<option value="MGA" <?php if($row['currency_from'] == "MGA") { echo 'selected'; } ?>>MGA - Madagascar Ariary</option>
											<option value="MKD" <?php if($row['currency_from'] == "MKD") { echo 'selected'; } ?>>MKD - Macedonia Denar</option>
											<option value="MMK" <?php if($row['currency_from'] == "MMK") { echo 'selected'; } ?>>MMK - Myanmar (Burma) Kyat</option>
											<option value="MNT" <?php if($row['currency_from'] == "MNT") { echo 'selected'; } ?>>MNT - Mongolia Tughrik</option>
											<option value="MOP" <?php if($row['currency_from'] == "MOP") { echo 'selected'; } ?>>MOP - Macau Pataca</option>
											<option value="MRO" <?php if($row['currency_from'] == "MRO") { echo 'selected'; } ?>>MRO - Mauritania Ouguiya</option>
											<option value="MUR" <?php if($row['currency_from'] == "MUR") { echo 'selected'; } ?>>MUR - Mauritius Rupee</option>
											<option value="MVR" <?php if($row['currency_from'] == "MVR") { echo 'selected'; } ?>>MVR - Maldives (Maldive Islands) Rufiyaa</option>
											<option value="MWK" <?php if($row['currency_from'] == "MWK") { echo 'selected'; } ?>>MWK - Malawi Kwacha</option>
											<option value="MXN" <?php if($row['currency_from'] == "MXN") { echo 'selected'; } ?>>MXN - Mexico Peso</option>
											<option value="MYR" <?php if($row['currency_from'] == "MYR") { echo 'selected'; } ?>>MYR - Malaysia Ringgit</option>
											<option value="MZN" <?php if($row['currency_from'] == "MZN") { echo 'selected'; } ?>>MZN - Mozambique Metical</option>
											<option value="NAD" <?php if($row['currency_from'] == "NAD") { echo 'selected'; } ?>>NAD - Namibia Dollar</option>
											<option value="NGN" <?php if($row['currency_from'] == "NGN") { echo 'selected'; } ?>>NGN - Nigeria Naira</option>
											<option value="NTO" <?php if($row['currency_from'] == "NTO") { echo 'selected'; } ?>>NIO - Nicaragua Cordoba</option>
											<option value="NOK" <?php if($row['currency_from'] == "NOK") { echo 'selected'; } ?>>NOK - Norway Krone</option>
											<option value="NPR" <?php if($row['currency_from'] == "NPR") { echo 'selected'; } ?>>NPR - Nepal Rupee</option>
											<option value="NZD" <?php if($row['currency_from'] == "NZD") { echo 'selected'; } ?>>NZD - New Zealand Dollar</option>
											<option value="OMR" <?php if($row['currency_from'] == "OMR") { echo 'selected'; } ?>>OMR - Oman Rial</option>
											<option value="PAB" <?php if($row['currency_from'] == "PAB") { echo 'selected'; } ?>>PAB - Panama Balboa</option>
											<option value="PEN" <?php if($row['currency_from'] == "PEN") { echo 'selected'; } ?>>PEN - Peru Nuevo Sol</option>
											<option value="PGK" <?php if($row['currency_from'] == "PGK") { echo 'selected'; } ?>>PGK - Papua New Guinea Kina</option>
											<option value="PHP" <?php if($row['currency_from'] == "PHP") { echo 'selected'; } ?>>PHP - Philippines Peso</option>
											<option value="PKR" <?php if($row['currency_from'] == "PKR") { echo 'selected'; } ?>>PKR - Pakistan Rupee</option>
											<option value="PLN" <?php if($row['currency_from'] == "PLN") { echo 'selected'; } ?>>PLN - Poland Zloty</option>
											<option value="PYG" <?php if($row['currency_from'] == "PYG") { echo 'selected'; } ?>>PYG - Paraguay Guarani</option>
											<option value="QAR" <?php if($row['currency_from'] == "QAR") { echo 'selected'; } ?>>QAR - Qatar Riyal</option>
											<option value="RON" <?php if($row['currency_from'] == "RON") { echo 'selected'; } ?>>RON - Romania New Leu</option>
											<option value="RSD" <?php if($row['currency_from'] == "RSD") { echo 'selected'; } ?>>RSD - Serbia Dinar</option>
											<option value="RUB" <?php if($row['currency_from'] == "RUB") { echo 'selected'; } ?>>RUB - Russia Ruble</option>
											<option value="RWF" <?php if($row['currency_from'] == "RWF") { echo 'selected'; } ?>>RWF - Rwanda Franc</option>
											<option value="SAR" <?php if($row['currency_from'] == "SAR") { echo 'selected'; } ?>>SAR - Saudi Arabia Riyal</option>
											<option value="SBD" <?php if($row['currency_from'] == "SBD") { echo 'selected'; } ?>>SBD - Solomon Islands Dollar</option>
											<option value="SCR" <?php if($row['currency_from'] == "SCR") { echo 'selected'; } ?>>SCR - Seychelles Rupee</option>
											<option value="SDG" <?php if($row['currency_from'] == "SDG") { echo 'selected'; } ?>>SDG - Sudan Pound</option>
											<option value="SEK" <?php if($row['currency_from'] == "SEK") { echo 'selected'; } ?>>SEK - Sweden Krona</option>
											<option value="SGD" <?php if($row['currency_from'] == "SGD") { echo 'selected'; } ?>>SGD - Singapore Dollar</option>
											<option value="SHP" <?php if($row['currency_from'] == "SHP") { echo 'selected'; } ?>>SHP - Saint Helena Pound</option>
											<option value="SLL" <?php if($row['currency_from'] == "SLL") { echo 'selected'; } ?>>SLL - Sierra Leone Leone</option>
											<option value="SOS" <?php if($row['currency_from'] == "SOS") { echo 'selected'; } ?>>SOS - Somalia Shilling</option>
											<option value="SRL" <?php if($row['currency_from'] == "SRL") { echo 'selected'; } ?>>SPL* - Seborga Luigino</option>
											<option value="SRD" <?php if($row['currency_from'] == "SRD") { echo 'selected'; } ?>>SRD - Suriname Dollar</option>
											<option value="STD" <?php if($row['currency_from'] == "STD") { echo 'selected'; } ?>>STD - Sao Tome and Principe Dobra</option>
											<option value="SVC" <?php if($row['currency_from'] == "SVC") { echo 'selected'; } ?>>SVC - El Salvador Colon</option>
											<option value="SYP" <?php if($row['currency_from'] == "SYP") { echo 'selected'; } ?>>SYP - Syria Pound</option>
											<option value="SZL" <?php if($row['currency_from'] == "SZL") { echo 'selected'; } ?>>SZL - Swaziland Lilangeni</option>
											<option value="THB" <?php if($row['currency_from'] == "THB") { echo 'selected'; } ?>>THB - Thailand Baht</option>
											<option value="TJS" <?php if($row['currency_from'] == "TJS") { echo 'selected'; } ?>>TJS - Tajikistan Somoni</option>
											<option value="TMT" <?php if($row['currency_from'] == "TMT") { echo 'selected'; } ?>>TMT - Turkmenistan Manat</option>
											<option value="TND" <?php if($row['currency_from'] == "TND") { echo 'selected'; } ?>>TND - Tunisia Dinar</option>
											<option value="TOP" <?php if($row['currency_from'] == "TOP") { echo 'selected'; } ?>>TOP - Tonga Pa'anga</option>
											<option value="TRY" <?php if($row['currency_from'] == "TRY") { echo 'selected'; } ?>>TRY - Turkey Lira</option>
											<option value="TTD" <?php if($row['currency_from'] == "TTD") { echo 'selected'; } ?>>TTD - Trinidad and Tobago Dollar</option>
											<option value="TVD" <?php if($row['currency_from'] == "TVD") { echo 'selected'; } ?>>TVD - Tuvalu Dollar</option>
											<option value="TWD" <?php if($row['currency_from'] == "TWD") { echo 'selected'; } ?>>TWD - Taiwan New Dollar</option>
											<option value="TZS" <?php if($row['currency_from'] == "TZS") { echo 'selected'; } ?>>TZS - Tanzania Shilling</option>
											<option value="UAH" <?php if($row['currency_from'] == "UAH") { echo 'selected'; } ?>>UAH - Ukraine Hryvnia</option>
											<option value="UGX" <?php if($row['currency_from'] == "UGX") { echo 'selected'; } ?>>UGX - Uganda Shilling</option>
											<option value="USD" <?php if($row['currency_from'] == "USD") { echo 'selected'; } ?>>USD - United States Dollar</option>
											<option value="UYU" <?php if($row['currency_from'] == "UYU") { echo 'selected'; } ?>>UYU - Uruguay Peso</option>
											<option value="UZS" <?php if($row['currency_from'] == "UZS") { echo 'selected'; } ?>>UZS - Uzbekistan Som</option>
											<option value="VEF" <?php if($row['currency_from'] == "VEF") { echo 'selected'; } ?>>VEF - Venezuela Bolivar</option>
											<option value="VND" <?php if($row['currency_from'] == "VND") { echo 'selected'; } ?>>VND - Viet Nam Dong</option>
											<option value="VUV" <?php if($row['currency_from'] == "VUV") { echo 'selected'; } ?>>VUV - Vanuatu Vatu</option>
											<option value="WST" <?php if($row['currency_from'] == "WST") { echo 'selected'; } ?>>WST - Samoa Tala</option>
											<option value="XAF" <?php if($row['currency_from'] == "XAF") { echo 'selected'; } ?>>XAF - Communaute Financiere Africaine (BEAC) CFA Franc BEAC</option>
											<option value="XCD" <?php if($row['currency_from'] == "XCD") { echo 'selected'; } ?>>XCD - East Caribbean Dollar</option>
											<option value="XDR" <?php if($row['currency_from'] == "XDR") { echo 'selected'; } ?>>XDR - International Monetary Fund (IMF) Special Drawing Rights</option>
											<option value="XOF" <?php if($row['currency_from'] == "XOF") { echo 'selected'; } ?>>XOF - Communaute Financiere Africaine (BCEAO) Franc</option>
											<option value="XPF" <?php if($row['currency_from'] == "XPF") { echo 'selected'; } ?>>XPF - Comptoirs Francais du Pacifique (CFP) Franc</option>
											<option value="YER" <?php if($row['currency_from'] == "YER") { echo 'selected'; } ?>>YER - Yemen Rial</option>
											<option value="ZAR" <?php if($row['currency_from'] == "ZAR") { echo 'selected'; } ?>>ZAR - South Africa Rand</option>
											<option value="ZMW" <?php if($row['currency_from'] == "ZMW") { echo 'selected'; } ?>>ZMW - Zambia Kwacha</option>
											<option value="ZWD" <?php if($row['currency_from'] == "ZWD") { echo 'selected'; } ?>>ZWD - Zimbabwe Dollar</option>
											<?php } ?>
										</select>
							</div>
							<div class="form-group">
								<label>To currency</label>
								<select class="form-control" name="currency_to" id="currency_to" onchange="changeCur2(this.value);">
											<option value="">----- Coins -----</option>
											<option value="BTC" <?php if($row['currency_to'] == "BTC") { echo 'selected'; } ?>>BTC - Bitcoin</option>
											<option value="LTC" <?php if($row['currency_to'] == "LTC") { echo 'selected'; } ?>>LTC - Litecoin</option>
											<option value="DOGE" <?php if($row['currency_to'] == "DOGE") { echo 'selected'; } ?>>DOGE - Dogecoin</option>
											<option value="">----- Valuta -----</option>
											<option value="AED" <?php if($row['currency_to'] == "AED") { echo 'selected'; } ?>>AED - United Arab Emirates Dirham</option>
											<option value="AFN" <?php if($row['currency_to'] == "AFN") { echo 'selected'; } ?>>AFN - Afghanistan Afghani</option>
											<option value="ALL" <?php if($row['currency_to'] == "ALL") { echo 'selected'; } ?>>ALL - Albania Lek</option>
											<option value="AMD" <?php if($row['currency_to'] == "AMD") { echo 'selected'; } ?>>AMD - Armenia Dram</option>
											<option value="ANG" <?php if($row['currency_to'] == "ANG") { echo 'selected'; } ?>>ANG - Netherlands Antilles Guilder</option>
											<option value="AOA" <?php if($row['currency_to'] == "AOA") { echo 'selected'; } ?>>AOA - Angola Kwanza</option>
											<option value="ARS" <?php if($row['currency_to'] == "ARS") { echo 'selected'; } ?>>ARS - Argentina Peso</option>
											<option value="AUD" <?php if($row['currency_to'] == "AUD") { echo 'selected'; } ?>>AUD - Australia Dollar</option>
											<option value="AWG" <?php if($row['currency_to'] == "AWG") { echo 'selected'; } ?>>AWG - Aruba Guilder</option>
											<option value="AZN" <?php if($row['currency_to'] == "AZN") { echo 'selected'; } ?>>AZN - Azerbaijan New Manat</option>
											<option value="BTC" <?php if($row['currency_to'] == "BTC") { echo 'selected'; } ?>>BTC - Bitcoin</option>
											<option value="BAM" <?php if($row['currency_to'] == "BAM") { echo 'selected'; } ?>>BAM - Bosnia and Herzegovina Convertible Marka</option>
											<option value="BBD" <?php if($row['currency_to'] == "BBD") { echo 'selected'; } ?>>BBD - Barbados Dollar</option>
											<option value="BDT" <?php if($row['currency_to'] == "BDT") { echo 'selected'; } ?>>BDT - Bangladesh Taka</option>
											<option value="BGN" <?php if($row['currency_to'] == "BGN") { echo 'selected'; } ?>>BGN - Bulgaria Lev</option>
											<option value="BHD" <?php if($row['currency_to'] == "BHD") { echo 'selected'; } ?>>BHD - Bahrain Dinar</option>
											<option value="BIF" <?php if($row['currency_to'] == "BIF") { echo 'selected'; } ?>>BIF - Burundi Franc</option>
											<option value="BMD" <?php if($row['currency_to'] == "BMD") { echo 'selected'; } ?>>BMD - Bermuda Dollar</option>
											<option value="BND" <?php if($row['currency_to'] == "BND") { echo 'selected'; } ?>>BND - Brunei Darussalam Dollar</option>
											<option value="BOB" <?php if($row['currency_to'] == "BOB") { echo 'selected'; } ?>>BOB - Bolivia Boliviano</option>
											<option value="BRL" <?php if($row['currency_to'] == "BRL") { echo 'selected'; } ?>>BRL - Brazil Real</option>
											<option value="BSD" <?php if($row['currency_to'] == "BSD") { echo 'selected'; } ?>>BSD - Bahamas Dollar</option>
											<option value="BTN" <?php if($row['currency_to'] == "BTN") { echo 'selected'; } ?>>BTN - Bhutan Ngultrum</option>
											<option value="BWP" <?php if($row['currency_to'] == "BWP") { echo 'selected'; } ?>>BWP - Botswana Pula</option>
											<option value="BYR" <?php if($row['currency_to'] == "BYR") { echo 'selected'; } ?>>BYR - Belarus Ruble</option>
											<option value="BZD" <?php if($row['currency_to'] == "BZD") { echo 'selected'; } ?>>BZD - Belize Dollar</option>
											<option value="CAD" <?php if($row['currency_to'] == "CAD") { echo 'selected'; } ?>>CAD - Canada Dollar</option>
											<option value="CDF" <?php if($row['currency_to'] == "CDF") { echo 'selected'; } ?>>CDF - Congo/Kinshasa Franc</option>
											<option value="CHF" <?php if($row['currency_to'] == "CHF") { echo 'selected'; } ?>>CHF - Switzerland Franc</option>
											<option value="CLP" <?php if($row['currency_to'] == "CLP") { echo 'selected'; } ?>>CLP - Chile Peso</option>
											<option value="CNY" <?php if($row['currency_to'] == "CNY") { echo 'selected'; } ?>>CNY - China Yuan Renminbi</option>
											<option value="COP" <?php if($row['currency_to'] == "COP") { echo 'selected'; } ?>>COP - Colombia Peso</option>
											<option value="CRC" <?php if($row['currency_to'] == "CRC") { echo 'selected'; } ?>>CRC - Costa Rica Colon</option>
											<option value="CUC" <?php if($row['currency_to'] == "CUC") { echo 'selected'; } ?>>CUC - Cuba Convertible Peso</option>
											<option value="CUP" <?php if($row['currency_to'] == "CUP") { echo 'selected'; } ?>>CUP - Cuba Peso</option>
											<option value="CVE" <?php if($row['currency_to'] == "CVE") { echo 'selected'; } ?>>CVE - Cape Verde Escudo</option>
											<option value="CZK" <?php if($row['currency_to'] == "CZK") { echo 'selected'; } ?>>CZK - Czech Republic Koruna</option>
											<option value="DJF" <?php if($row['currency_to'] == "DJF") { echo 'selected'; } ?>>DJF - Djibouti Franc</option>
											<option value="DKK" <?php if($row['currency_to'] == "DKK") { echo 'selected'; } ?>>DKK - Denmark Krone</option>
											<option value="DOP" <?php if($row['currency_to'] == "DOP") { echo 'selected'; } ?>>DOP - Dominican Republic Peso</option>
											<option value="DZD" <?php if($row['currency_to'] == "DZD") { echo 'selected'; } ?>>DZD - Algeria Dinar</option>
											<option value="EGP" <?php if($row['currency_to'] == "EGP") { echo 'selected'; } ?>>EGP - Egypt Pound</option>
											<option value="ERN" <?php if($row['currency_to'] == "ERN") { echo 'selected'; } ?>>ERN - Eritrea Nakfa</option>
											<option value="ETB" <?php if($row['currency_to'] == "ETB") { echo 'selected'; } ?>>ETB - Ethiopia Birr</option>
											<option value="EUR" <?php if($row['currency_to'] == "EUR") { echo 'selected'; } ?>>EUR - Euro Member Countries</option>
											<option value="FJD" <?php if($row['currency_to'] == "FJD") { echo 'selected'; } ?>>FJD - Fiji Dollar</option>
											<option value="FKP" <?php if($row['currency_to'] == "FKP") { echo 'selected'; } ?>>FKP - Falkland Islands (Malvinas) Pound</option>
											<option value="GBP" <?php if($row['currency_to'] == "GBP") { echo 'selected'; } ?>>GBP - United Kingdom Pound</option>
											<option value="GEL" <?php if($row['currency_to'] == "GEL") { echo 'selected'; } ?>>GEL - Georgia Lari</option>
											<option value="GGP" <?php if($row['currency_to'] == "GGP") { echo 'selected'; } ?>>GGP - Guernsey Pound</option>
											<option value="GHS" <?php if($row['currency_to'] == "GHS") { echo 'selected'; } ?>>GHS - Ghana Cedi</option>
											<option value="GIP" <?php if($row['currency_to'] == "GIP") { echo 'selected'; } ?>>GIP - Gibraltar Pound</option>
											<option value="GMD" <?php if($row['currency_to'] == "GMD") { echo 'selected'; } ?>>GMD - Gambia Dalasi</option>
											<option value="GNF" <?php if($row['currency_to'] == "GNF") { echo 'selected'; } ?>>GNF - Guinea Franc</option>
											<option value="GTQ" <?php if($row['currency_to'] == "GTQ") { echo 'selected'; } ?>>GTQ - Guatemala Quetzal</option>
											<option value="GYD" <?php if($row['currency_to'] == "GYD") { echo 'selected'; } ?>>GYD - Guyana Dollar</option>
											<option value="HKD" <?php if($row['currency_to'] == "HKD") { echo 'selected'; } ?>>HKD - Hong Kong Dollar</option>
											<option value="HNL" <?php if($row['currency_to'] == "HNL") { echo 'selected'; } ?>>HNL - Honduras Lempira</option>
											<option value="HPK" <?php if($row['currency_to'] == "HPK") { echo 'selected'; } ?>>HRK - Croatia Kuna</option>
											<option value="HTG" <?php if($row['currency_to'] == "HTG") { echo 'selected'; } ?>>HTG - Haiti Gourde</option>
											<option value="HUF" <?php if($row['currency_to'] == "HUF") { echo 'selected'; } ?>>HUF - Hungary Forint</option>
											<option value="IDR" <?php if($row['currency_to'] == "IDR") { echo 'selected'; } ?>>IDR - Indonesia Rupiah</option>
											<option value="ILS" <?php if($row['currency_to'] == "ILS") { echo 'selected'; } ?>>ILS - Israel Shekel</option>
											<option value="IMP" <?php if($row['currency_to'] == "IMP") { echo 'selected'; } ?>>IMP - Isle of Man Pound</option>
											<option value="INR" <?php if($row['currency_to'] == "INR") { echo 'selected'; } ?>>INR - India Rupee</option>
											<option value="IQD" <?php if($row['currency_to'] == "IQD") { echo 'selected'; } ?>>IQD - Iraq Dinar</option>
											<option value="IRR" <?php if($row['currency_to'] == "IRR") { echo 'selected'; } ?>>IRR - Iran Rial</option>
											<option value="ISK" <?php if($row['currency_to'] == "ISK") { echo 'selected'; } ?>>ISK - Iceland Krona</option>
											<option value="JEP" <?php if($row['currency_to'] == "JEP") { echo 'selected'; } ?>>JEP - Jersey Pound</option>
											<option value="JMD" <?php if($row['currency_to'] == "JMD") { echo 'selected'; } ?>>JMD - Jamaica Dollar</option>
											<option value="JOD" <?php if($row['currency_to'] == "JOD") { echo 'selected'; } ?>>JOD - Jordan Dinar</option>
											<option value="JPY" <?php if($row['currency_to'] == "JPY") { echo 'selected'; } ?>>JPY - Japan Yen</option>
											<option value="KES" <?php if($row['currency_to'] == "KES") { echo 'selected'; } ?>>KES - Kenya Shilling</option>
											<option value="KGS" <?php if($row['currency_to'] == "KGS") { echo 'selected'; } ?>>KGS - Kyrgyzstan Som</option>
											<option value="KHR" <?php if($row['currency_to'] == "KHR") { echo 'selected'; } ?>>KHR - Cambodia Riel</option>
											<option value="KMF" <?php if($row['currency_to'] == "KMF") { echo 'selected'; } ?>>KMF - Comoros Franc</option>
											<option value="KPW" <?php if($row['currency_to'] == "KPW") { echo 'selected'; } ?>>KPW - Korea (North) Won</option>
											<option value="KRW" <?php if($row['currency_to'] == "KRW") { echo 'selected'; } ?>>KRW - Korea (South) Won</option>
											<option value="KWD" <?php if($row['currency_to'] == "KWD") { echo 'selected'; } ?>>KWD - Kuwait Dinar</option>
											<option value="KYD" <?php if($row['currency_to'] == "KYD") { echo 'selected'; } ?>>KYD - Cayman Islands Dollar</option>
											<option value="KZT" <?php if($row['currency_to'] == "KZT") { echo 'selected'; } ?>>KZT - Kazakhstan Tenge</option>
											<option value="LAK" <?php if($row['currency_to'] == "LAK") { echo 'selected'; } ?>>LAK - Laos Kip</option>
											<option value="LBP" <?php if($row['currency_to'] == "LBP") { echo 'selected'; } ?>>LBP - Lebanon Pound</option>
											<option value="LKR" <?php if($row['currency_to'] == "LKR") { echo 'selected'; } ?>>LKR - Sri Lanka Rupee</option>
											<option value="LRD" <?php if($row['currency_to'] == "LRD") { echo 'selected'; } ?>>LRD - Liberia Dollar</option>
											<option value="LSL" <?php if($row['currency_to'] == "LSL") { echo 'selected'; } ?>>LSL - Lesotho Loti</option>
											<option value="LYD" <?php if($row['currency_to'] == "LYD") { echo 'selected'; } ?>>LYD - Libya Dinar</option>
											<option value="MAD" <?php if($row['currency_to'] == "MAD") { echo 'selected'; } ?>>MAD - Morocco Dirham</option>
											<option value="MDL" <?php if($row['currency_to'] == "MDL") { echo 'selected'; } ?>>MDL - Moldova Leu</option>
											<option value="MGA" <?php if($row['currency_to'] == "MGA") { echo 'selected'; } ?>>MGA - Madagascar Ariary</option>
											<option value="MKD" <?php if($row['currency_to'] == "MKD") { echo 'selected'; } ?>>MKD - Macedonia Denar</option>
											<option value="MMK" <?php if($row['currency_to'] == "MMK") { echo 'selected'; } ?>>MMK - Myanmar (Burma) Kyat</option>
											<option value="MNT" <?php if($row['currency_to'] == "MNT") { echo 'selected'; } ?>>MNT - Mongolia Tughrik</option>
											<option value="MOP" <?php if($row['currency_to'] == "MOP") { echo 'selected'; } ?>>MOP - Macau Pataca</option>
											<option value="MRO" <?php if($row['currency_to'] == "MRO") { echo 'selected'; } ?>>MRO - Mauritania Ouguiya</option>
											<option value="MUR" <?php if($row['currency_to'] == "MUR") { echo 'selected'; } ?>>MUR - Mauritius Rupee</option>
											<option value="MVR" <?php if($row['currency_to'] == "MVR") { echo 'selected'; } ?>>MVR - Maldives (Maldive Islands) Rufiyaa</option>
											<option value="MWK" <?php if($row['currency_to'] == "MWK") { echo 'selected'; } ?>>MWK - Malawi Kwacha</option>
											<option value="MXN" <?php if($row['currency_to'] == "MXN") { echo 'selected'; } ?>>MXN - Mexico Peso</option>
											<option value="MYR" <?php if($row['currency_to'] == "MYR") { echo 'selected'; } ?>>MYR - Malaysia Ringgit</option>
											<option value="MZN" <?php if($row['currency_to'] == "MZN") { echo 'selected'; } ?>>MZN - Mozambique Metical</option>
											<option value="NAD" <?php if($row['currency_to'] == "NAD") { echo 'selected'; } ?>>NAD - Namibia Dollar</option>
											<option value="NGN" <?php if($row['currency_to'] == "NGN") { echo 'selected'; } ?>>NGN - Nigeria Naira</option>
											<option value="NTO" <?php if($row['currency_to'] == "NTO") { echo 'selected'; } ?>>NIO - Nicaragua Cordoba</option>
											<option value="NOK" <?php if($row['currency_to'] == "NOK") { echo 'selected'; } ?>>NOK - Norway Krone</option>
											<option value="NPR" <?php if($row['currency_to'] == "NPR") { echo 'selected'; } ?>>NPR - Nepal Rupee</option>
											<option value="NZD" <?php if($row['currency_to'] == "NZD") { echo 'selected'; } ?>>NZD - New Zealand Dollar</option>
											<option value="OMR" <?php if($row['currency_to'] == "OMR") { echo 'selected'; } ?>>OMR - Oman Rial</option>
											<option value="PAB" <?php if($row['currency_to'] == "PAB") { echo 'selected'; } ?>>PAB - Panama Balboa</option>
											<option value="PEN" <?php if($row['currency_to'] == "PEN") { echo 'selected'; } ?>>PEN - Peru Nuevo Sol</option>
											<option value="PGK" <?php if($row['currency_to'] == "PGK") { echo 'selected'; } ?>>PGK - Papua New Guinea Kina</option>
											<option value="PHP" <?php if($row['currency_to'] == "PHP") { echo 'selected'; } ?>>PHP - Philippines Peso</option>
											<option value="PKR" <?php if($row['currency_to'] == "PKR") { echo 'selected'; } ?>>PKR - Pakistan Rupee</option>
											<option value="PLN" <?php if($row['currency_to'] == "PLN") { echo 'selected'; } ?>>PLN - Poland Zloty</option>
											<option value="PYG" <?php if($row['currency_to'] == "PYG") { echo 'selected'; } ?>>PYG - Paraguay Guarani</option>
											<option value="QAR" <?php if($row['currency_to'] == "QAR") { echo 'selected'; } ?>>QAR - Qatar Riyal</option>
											<option value="RON" <?php if($row['currency_to'] == "RON") { echo 'selected'; } ?>>RON - Romania New Leu</option>
											<option value="RSD" <?php if($row['currency_to'] == "RSD") { echo 'selected'; } ?>>RSD - Serbia Dinar</option>
											<option value="RUB" <?php if($row['currency_to'] == "RUB") { echo 'selected'; } ?>>RUB - Russia Ruble</option>
											<option value="RWF" <?php if($row['currency_to'] == "RWF") { echo 'selected'; } ?>>RWF - Rwanda Franc</option>
											<option value="SAR" <?php if($row['currency_to'] == "SAR") { echo 'selected'; } ?>>SAR - Saudi Arabia Riyal</option>
											<option value="SBD" <?php if($row['currency_to'] == "SBD") { echo 'selected'; } ?>>SBD - Solomon Islands Dollar</option>
											<option value="SCR" <?php if($row['currency_to'] == "SCR") { echo 'selected'; } ?>>SCR - Seychelles Rupee</option>
											<option value="SDG" <?php if($row['currency_to'] == "SDG") { echo 'selected'; } ?>>SDG - Sudan Pound</option>
											<option value="SEK" <?php if($row['currency_to'] == "SEK") { echo 'selected'; } ?>>SEK - Sweden Krona</option>
											<option value="SGD" <?php if($row['currency_to'] == "SGD") { echo 'selected'; } ?>>SGD - Singapore Dollar</option>
											<option value="SHP" <?php if($row['currency_to'] == "SHP") { echo 'selected'; } ?>>SHP - Saint Helena Pound</option>
											<option value="SLL" <?php if($row['currency_to'] == "SLL") { echo 'selected'; } ?>>SLL - Sierra Leone Leone</option>
											<option value="SOS" <?php if($row['currency_to'] == "SOS") { echo 'selected'; } ?>>SOS - Somalia Shilling</option>
											<option value="SRL" <?php if($row['currency_to'] == "SRL") { echo 'selected'; } ?>>SPL* - Seborga Luigino</option>
											<option value="SRD" <?php if($row['currency_to'] == "SRD") { echo 'selected'; } ?>>SRD - Suriname Dollar</option>
											<option value="STD" <?php if($row['currency_to'] == "STD") { echo 'selected'; } ?>>STD - Sao Tome and Principe Dobra</option>
											<option value="SVC" <?php if($row['currency_to'] == "SVC") { echo 'selected'; } ?>>SVC - El Salvador Colon</option>
											<option value="SYP" <?php if($row['currency_to'] == "SYP") { echo 'selected'; } ?>>SYP - Syria Pound</option>
											<option value="SZL" <?php if($row['currency_to'] == "SZL") { echo 'selected'; } ?>>SZL - Swaziland Lilangeni</option>
											<option value="THB" <?php if($row['currency_to'] == "THB") { echo 'selected'; } ?>>THB - Thailand Baht</option>
											<option value="TJS" <?php if($row['currency_to'] == "TJS") { echo 'selected'; } ?>>TJS - Tajikistan Somoni</option>
											<option value="TMT" <?php if($row['currency_to'] == "TMT") { echo 'selected'; } ?>>TMT - Turkmenistan Manat</option>
											<option value="TND" <?php if($row['currency_to'] == "TND") { echo 'selected'; } ?>>TND - Tunisia Dinar</option>
											<option value="TOP" <?php if($row['currency_to'] == "TOP") { echo 'selected'; } ?>>TOP - Tonga Pa'anga</option>
											<option value="TRY" <?php if($row['currency_to'] == "TRY") { echo 'selected'; } ?>>TRY - Turkey Lira</option>
											<option value="TTD" <?php if($row['currency_to'] == "TTD") { echo 'selected'; } ?>>TTD - Trinidad and Tobago Dollar</option>
											<option value="TVD" <?php if($row['currency_to'] == "TVD") { echo 'selected'; } ?>>TVD - Tuvalu Dollar</option>
											<option value="TWD" <?php if($row['currency_to'] == "TWD") { echo 'selected'; } ?>>TWD - Taiwan New Dollar</option>
											<option value="TZS" <?php if($row['currency_to'] == "TZS") { echo 'selected'; } ?>>TZS - Tanzania Shilling</option>
											<option value="UAH" <?php if($row['currency_to'] == "UAH") { echo 'selected'; } ?>>UAH - Ukraine Hryvnia</option>
											<option value="UGX" <?php if($row['currency_to'] == "UGX") { echo 'selected'; } ?>>UGX - Uganda Shilling</option>
											<option value="USD" <?php if($row['currency_to'] == "USD") { echo 'selected'; } ?>>USD - United States Dollar</option>
											<option value="UYU" <?php if($row['currency_to'] == "UYU") { echo 'selected'; } ?>>UYU - Uruguay Peso</option>
											<option value="UZS" <?php if($row['currency_to'] == "UZS") { echo 'selected'; } ?>>UZS - Uzbekistan Som</option>
											<option value="VEF" <?php if($row['currency_to'] == "VEF") { echo 'selected'; } ?>>VEF - Venezuela Bolivar</option>
											<option value="VND" <?php if($row['currency_to'] == "VND") { echo 'selected'; } ?>>VND - Viet Nam Dong</option>
											<option value="VUV" <?php if($row['currency_to'] == "VUV") { echo 'selected'; } ?>>VUV - Vanuatu Vatu</option>
											<option value="WST" <?php if($row['currency_to'] == "WST") { echo 'selected'; } ?>>WST - Samoa Tala</option>
											<option value="XAF" <?php if($row['currency_to'] == "XAF") { echo 'selected'; } ?>>XAF - Communaute Financiere Africaine (BEAC) CFA Franc BEAC</option>
											<option value="XCD" <?php if($row['currency_to'] == "XCD") { echo 'selected'; } ?>>XCD - East Caribbean Dollar</option>
											<option value="XDR" <?php if($row['currency_to'] == "XDR") { echo 'selected'; } ?>>XDR - International Monetary Fund (IMF) Special Drawing Rights</option>
											<option value="XOF" <?php if($row['currency_to'] == "XOF") { echo 'selected'; } ?>>XOF - Communaute Financiere Africaine (BCEAO) Franc</option>
											<option value="XPF" <?php if($row['currency_to'] == "XPF") { echo 'selected'; } ?>>XPF - Comptoirs Francais du Pacifique (CFP) Franc</option>
											<option value="YER" <?php if($row['currency_to'] == "YER") { echo 'selected'; } ?>>YER - Yemen Rial</option>
											<option value="ZAR" <?php if($row['currency_to'] == "ZAR") { echo 'selected'; } ?>>ZAR - South Africa Rand</option>
											<option value="ZMW" <?php if($row['currency_to'] == "ZMW") { echo 'selected'; } ?>>ZMW - Zambia Kwacha</option>
											<option value="ZWD" <?php if($row['currency_to'] == "ZWD") { echo 'selected'; } ?>>ZWD - Zimbabwe Dollar</option>
										</select>
							</div>
							<div class="form-group">
								<label>Rate from</label>
								<div class="input-group">
								  <input type="text" class="form-control" name="rate_from" aria-label="Amount" value="<?php echo $row['rate_from']; ?>">
								  <span class="input-group-addon"><span id="cur_from"><?php echo $row['currency_from']; ?></span></span>
								</div>
							</div>
							<div class="form-group">
								<label>Rate to</label>
								<div class="input-group">
								  <input type="text" class="form-control" name="rate_to" aria-label="Amount" value="<?php echo $row['rate_to']; ?>">
								  <span class="input-group-addon"><span id="cur_to"><?php echo $row['currency_to']; ?></span></span>
								</div>
							</div>
							<div class="form-group">
								<label>Reserve</label>
								<div class="input-group">
								  <input type="text" class="form-control" name="reserve" aria-label="Amount" value="<?php echo $row['reserve']; ?>">
								  <span class="input-group-addon"><span id="cur_to"><?php echo $row['currency_to']; ?></span></span>
								</div>
							</div>
							<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>