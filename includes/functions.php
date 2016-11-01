<?php
function protect($string) {
	$protection = htmlspecialchars(trim($string), ENT_QUOTES);
	return $protection;
}

function randomHash($lenght = 7) {
	$random = substr(md5(rand()),0,$lenght);
	return $random;
}

function isValidURL($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function checkAdminSession() {
	if(isset($_SESSION['ec_a_user']) and isset($_SESSION['ec_a_uid'])) {
		return true;
	} else {
		return false;
	}
}

function isValidUsername($str) {
    return preg_match('/^[a-zA-Z0-9-_]+$/',$str);
}

function isValidEmail($str) {
	return filter_var($str, FILTER_VALIDATE_EMAIL);
}

function checkSession() {
	if(isset($_SESSION['ec_user']) and isset($_SESSION['ec_uid'])) {
		return true;
	} else {
		return false;
	}
}

function check_unpayed() {
	global $db;
	$query = $db->query("SELECT * FROM ec_exchanges WHERE status='1' or status='2' ORDER BY id");
	if($query->num_rows>0) {
		while($row = $query->fetch_assoc()) {
			$time = $row['created']+86400;
			if(time() > $time) {
				$time = time();
				$update = $db->query("UPDATE ec_exchanges SET status='5',expired='$time' WHERE id='$row[id]'");
			}
		}
	} 
}

function success($text) {
	return '<div class="alert alert-success text-left"><i class="fa fa-check"></i> '.$text.'</div>';
}

function error($text) {
	return '<div class="alert alert-danger text-left"><i class="fa fa-times"></i> '.$text.'</div>';
}

function info($text) {
	return '<div class="alert alert-info text-left"><i class="fa fa-info-circle"></i> '.$text.'</div>';
}

function admin_pagination($query,$ver,$per_page = 10,$page = 1, $url = '?') { 
    	global $db;
		$query = $db->query("SELECT * FROM $query");
    	$total = $query->num_rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='active'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='$ver&page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver&page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled'>...</li>";
    				$pagination.= "<li><a href='$ver&page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='$ver&page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='$ver&page=1'>1</a></li>";
    				$pagination.= "<li><a href='$ver&page=2'>2</a></li>";
    				$pagination.= "<li class='disabled'><a>...</a></li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver&page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled'><a>..</a></li>";
    				$pagination.= "<li><a href='$ver&page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='$ver&page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='$ver&page=1'>1</a></li>";
    				$pagination.= "<li><a href='$ver&page=2'>2</a></li>";
    				$pagination.= "<li class='disabled'><a>..</a></li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver&page=$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='$ver&page=$next'>Next</a></li>";
                $pagination.= "<li><a href='$ver&page=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='disabled'>Next</a></li>";
                $pagination.= "<li><a class='disabled'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
} 

function web_pagination($query,$ver,$per_page = 10,$page = 1, $url = '?') { 
    	global $db;
		$query = $db->query("SELECT * FROM $query");
    	$total = $query->num_rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='active'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='$ver/$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver/$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled'>...</li>";
    				$pagination.= "<li><a href='$ver/$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='$ver/$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='$ver/1'>1</a></li>";
    				$pagination.= "<li><a href='$ver/2'>2</a></li>";
    				$pagination.= "<li class='disabled'><a>...</a></li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver/$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled'><a>..</a></li>";
    				$pagination.= "<li><a href='$ver/$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='$ver/$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='$ver/1'>1</a></li>";
    				$pagination.= "<li><a href='$ver/2'>2</a></li>";
    				$pagination.= "<li class='disabled'><a>..</a></li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='active'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='$ver/$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='$ver/$next'>Next</a></li>";
                $pagination.= "<li><a href='$ver/$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='disabled'>Next</a></li>";
                $pagination.= "<li><a class='disabled'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
} 

function idinfo($uid,$value) {
	global $db;
	$query = $db->query("SELECT * FROM ec_users WHERE id='$uid'");
	$row = $query->fetch_assoc();
	return $row[$value];
}	

function getLanguage($url, $ln = null, $type = null) {
	// Type 1: Output the available languages
	// Type 2: Change the path for the /requests/ folder location
	// Set the directory location
	if($type == 2) {
		$languagesDir = '../languages/';
	} else {
		$languagesDir = './languages/';
	}
	// Search for pathnames matching the .png pattern
	$language = glob($languagesDir . '*.php', GLOB_BRACE);

	if($type == 1) {
		// Add to array the available images
		foreach($language as $lang) {
			// The path to be parsed
			$path = pathinfo($lang);
			
			// Add the filename into $available array
			$available .= '<a href="'.$url.'index.php?lang='.$path['filename'].'">'.ucfirst(strtolower($path['filename'])).'</a> - ';
		}
		return substr($available, 0, -3);
	} else {
		// If get is set, set the cookie and stuff
		$lang = 'Español'; // DEFAULT LANGUAGE
		if($type == 2) {
			$path = '../languages/';
		} else {
			$path = './languages/';
		}
		if(isset($_GET['lang'])) {
			if(in_array($path.$_GET['lang'].'.php', $language)) {
				$lang = $_GET['lang'];
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			} else {
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			}
		} elseif(isset($_COOKIE['lang'])) {
			if(in_array($path.$_COOKIE['lang'].'.php', $language)) {
				$lang = $_COOKIE['lang'];
			}
		} else {
			setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
		}

		if(in_array($path.$lang.'.php', $language)) {
			return $path.$lang.'.php';
		}
	}
}

function getIcon($name,$width,$height) {
	global $settings;
	$path = "assets/icons/";
	if($name == "PayPal") { $icon = 'PayPal.png'; }
	elseif($name == "Skrill") { $icon = 'Skrill.png'; }
	elseif($name == "WebMoney") { $icon = 'WebMoney.png'; }
	elseif($name == "Payeer") { $icon = 'Payeer.png'; }
        elseif($name == "MercadoPago") { $icon = 'logo-mp.png'; }
	elseif($name == "Perfect Money") { $icon = 'PerfectMoney.png'; }
	elseif($name == "AdvCash") { $icon = 'AdvCash.png'; }
	elseif($name == "OKPay") { $icon = 'OKPay.png'; }
	elseif($name == "Entromoney") { $icon = 'Entromoney.png'; }
	elseif($name == "SolidTrust Pay") { $icon = 'SolidTrustPay.png'; }
	elseif($name == "2checkout") { $icon = '2checkout.png'; }
	elseif($name == "Litecoin") { $icon = 'Litecoin.png'; }
	elseif($name == "Dash") { $icon = 'Dash.png'; }
	elseif($name == "Dogecoin") { $icon = 'Dogecoin.png'; }
	elseif($name == "Payza") { $icon = 'Payza.png'; }
	elseif($name == "Bitcoin") { $icon = 'Bitcoin.png'; }
	elseif($name == "Bank Transfer") { $icon = 'BankTransfer.png'; }
	elseif($name == "Western Union") { $icon = 'Westernunion.png'; }
	elseif($name == "Moneygram") { $icon = 'Moneygram.png'; }
	else { $icon = 'Missing.png'; }
	return '<img src="'.$settings[url].$path.$icon.'" width="'.$width.'" height="'.$height.'">';
}

function decodeStatus($num) {
	global $lang;
	if($num == "1") { $msg = '<span class="label label-info">'.$lang[status_1].'</span>'; }
	elseif($num == "2") { $msg = '<span class="label label-info">'.$lang[status_2].'</span>'; }
	elseif($num == "3") { $msg = '<span class="label label-info">'.$lang[status_3].'</span>'; }
	elseif($num == "4") { $msg = '<span class="label label-success">'.$lang[status_4].'</span>'; }
	elseif($num == "5") { $msg = '<span class="label label-danger">'.$lang[status_5].'</span>'; }
	elseif($num == "6") { $msg = '<span class="label label-danger">'.$lang[status_6].'</span>'; }
	elseif($num == "7") { $msg = '<span class="label label-danger">'.$lang[status_7].'</span>'; }
	else { $msg = '<span class="label label-default">'.$lang[unknown].'</span>'; }
	return $msg;
}
//function emailsys_new_exchange($email,$exchange_id) {
//	global $db, $settings;
//	$eQuery = $db->query("SELECT * FROM ec_exchanges WHERE id='$exchange_id'");
//	if($eQuery->num_rows>0) {
//		$e = $eQuery->fetch_assoc();
//		$emailname = explode("@",$email);
//		$msubject = '['.$settings[name].'] Solicitud de compra de bitcoin '.$e[exchange_id];
//		$mreceiver = $email;
//		$message = 'Hola, '.$emailname[0].''. "\r\n".
//		
//'Hiciste una solicitud de intercambio de '.$e[cfrom].' a '.$e[cto].' para '.$e[amount_from].' '.$e[currency_from].'.
//Debe realizar el pago antes de pasar 24 horas porque cuando expire el tiempo de su solicitud, esta no sera activa.
//Puede completar el pago en este enlace: '.$settings[url].'become_payment/'.$e[id].'
//	
//Si usted tiene algunos problemas por favor no dude en ponerse en contacto con nosotros en '.$settings[email];
//		$headers =  'From '.$settings[email].'' . "\r\n" .
//                            'Responder a: '.$settings[email].'' . "\r\n" .
//                            'X-Mailer: PHP/'. phpversion().''. 
//                            "Content-type:text/html;charset=UTF-8" . "\r\n";
//                               //  Always set content-type when sending HTML email
////                $headers = "MIME-Version: 1.0" . "\r\n".
////                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n".
////
////                // More headers
////                $headers .= 'From: '.$settings[email].'' . "\r\n".
////                $headers .= "Reply-To: ".$settings[email].'' . "\r\n";
//                
//                
//                
//                
//		$mailto = mail($mreceiver, $msubject, $message, $headers);
//		if($mailto) { 
//			return true;
//		} else {
//			return false;
//		}
//	}
//}
function emailsys_new_exchange($email,$exchange_id) {
	global $db, $settings;

      
	$eQuery = $db->query("SELECT * FROM ec_exchanges WHERE id='$exchange_id'");
	if($eQuery->num_rows>0) {
            require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");
            $e = $eQuery->fetch_assoc();
            $mailto->Subject = "[".$settings[name]."] Solicitud de compra de bitcoin ".$e[exchange_id];
            
            $bodyQuery = $db->query("SELECT compra FROM ec_emails WHERE id=1");
            $mybody = $bodyQuery->fetch_assoc();

                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{FROM}/';
                $patrones[2] = '/{TO}/';
                $patrones[3] = '/{AMOUNT_FROM}/';
                $patrones[4] = '/{CURRENCY_FROM}/';
                $patrones[5] = '/{URL}/';
                $patrones[6] = '/{ID}/';
                $patrones[7] = '/{EMAIL}/';
                $patrones[8] = '/{EXID}/';
                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $e[cfrom];
                $sustituciones[2] = $e[cto];
                $sustituciones[3] = $e[amount_from];
                $sustituciones[4] = $e[currency_from];
                $sustituciones[5] = $settings[url];
                $sustituciones[6] = $e[id];
                $sustituciones[7] = $settings[email];
                $sustituciones[8] = $e[exchange_id];
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[compra]);
//	    $body = "Hola, ".$emailname[0];
//	    $body .= "Hiciste una solicitud de intercambio de ".$e[cfrom]." a ".$e[cto]." para ".$e[amount_from]." ".$e[currency_from];
//            $body .= "Debe realizar el pago antes de pasar 24 horas porque cuando expire el tiempo de su solicitud, esta no será activa.";
//            $body .= "Puede completar el pago en este enlace: ".$settings[url]."become_payment/".$e[id];
//            $body .= "Si usted tiene algunos problemas por favor no dude en ponerse en contacto con nosotros en ".$settings[email];
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));
//            $mailto->Send();
            
                  if(!$mailto->send()) {
                    return false;
                } else {
                    
            $gmail = new PHPMailer(true);
            $gmail->isSMTP();
            $gmail->Host = "mail.intermoneda.com";
            $gmail->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $gmail->Username   = "admin@intermoneda.com";
            $gmail->Password = "Escalinata1";
            $gmail->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $gmail->Timeout   = 30;                         
            $gmail->Port = 465;
            $gmail->setFrom("admin@intermoneda.com", "Admin");
            $gmail->addAddress('admin@intermoneda.com', "Nuevo intercambio producido");
            $gmail->Subject = "Nueva solicitud de compra de bitcoin";
            $gmail->MsgHTML("Una nueva compra se ha procesado en el sitio, favor de checar. ID de compra: ".$e[exchange_id]);
            $gmail->Send();
                 return true;

                }
             
	}
}

function emailsys_notify_exchange_processed($exchange_id,$pin) {
	global $db, $settings;
	$eQuery = $db->query("SELECT * FROM ec_exchanges WHERE id='$exchange_id'");
        if($eQuery->num_rows>0) {
            require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $e = $eQuery->fetch_assoc();
            $email = $e[u_field_2];
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");
			
		if($pin) {
			$yourpin = 'Tu '.$e[cto].' PIN: '.$pin;
		} else {
			$yourpin = '';
		}
                $mailto->Subject = "[".$settings[name]."] Tu compra de ".$e[exchange_id]." fue procesado";
		$bodyQuery = $db->query("SELECT procesado FROM ec_emails WHERE id=1");
                $mybody = $bodyQuery->fetch_assoc();
                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{CFROM}/';
                $patrones[2] = '/{CTO}/';
                $patrones[3] = '/{AMOUNT_TO}/';
                $patrones[4] = '/{CURRENCY_TO}/';
                $patrones[5] = '/{PIN}/';
                $patrones[6] = '/{SETEMAIL}/';
                $patrones[7] = '/{EXID}/';
                $patrones[8] = '/{URL}/';
                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $e[cfrom];
                $sustituciones[2] = $e[cto];
                $sustituciones[3] = $e[amount_to];
                $sustituciones[4] = $e[currency_to];
                $sustituciones[5] = $yourpin;
                $sustituciones[6] = $settings[email];
                $sustituciones[7] = $e[exchange_id];
                $sustituciones[8] = $settings[url];
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[procesado]);
                
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));
                   if(!$mailto->send()) {
                    
                    return false;
                } else {
                    return true;
                }
	}
}

function emailsys_notify_exchange_denied($exchange_id,$reason) {
	global $db, $settings;
	$eQuery = $db->query("SELECT * FROM ec_exchanges WHERE id='$exchange_id'");
	if($eQuery->num_rows>0) {
            require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $e = $eQuery->fetch_assoc();
            $email = $e[u_field_2];
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");
		

                $mailto->Subject = "[".$settings[name]."] Tu compra de ".$e[exchange_id]." fue denegada";
		$bodyQuery = $db->query("SELECT denegado FROM ec_emails WHERE id=1");
                $mybody = $bodyQuery->fetch_assoc();
                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{CFROM}/';
                $patrones[2] = '/{CTO}/';
                $patrones[3] = '/{AMOUNT_FROM}/';
                $patrones[4] = '/{CURRENCY_FROM}/';
                $patrones[5] = '/{RAZON}/';
                $patrones[6] = '/{SETEMAIL}/';
                $patrones[7] = '/{EXID}/';
                $patrones[8] = '/{URL}/';
                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $e[cfrom];
                $sustituciones[2] = $e[cto];
                $sustituciones[3] = $e[amount_to];
                $sustituciones[4] = $e[currency_to];
                $sustituciones[5] = $reason;
                $sustituciones[6] = $settings[email];
                $sustituciones[7] = $e[exchange_id];
                $sustituciones[8] = $settings[url];
                
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[denegado]);
                
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));
            
                  if(!$mailto->send()) {
                    
                    return false;
                } else {
                    return true;
                }
	}
}

function emailsys_payment_received($email,$exchange_id) {
	global $db, $settings;
	$eQuery = $db->query("SELECT * FROM ec_exchanges WHERE id='$exchange_id'");
	if($eQuery->num_rows>0) {
                        require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $e = $eQuery->fetch_assoc();
            $email = $e[u_field_2];
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");

                $mailto->Subject = "[".$settings[name]."] Hemos recibido tu pago para de la compra ".$e[exchange_id];
		$bodyQuery = $db->query("SELECT recibido FROM ec_emails WHERE id=1");
                $mybody = $bodyQuery->fetch_assoc();
                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{CFROM}/';
                $patrones[2] = '/{CTO}/';
                $patrones[3] = '/{AMOUNT_FROM}/';
                $patrones[4] = '/{CURRENCY_FROM}/';
                $patrones[5] = '/{RAZON}/';
                $patrones[6] = '/{SETEMAIL}/';
                $patrones[7] = '/{EXID}/';
                $patrones[8] = '/{URL}/';
                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $e[cfrom];
                $sustituciones[2] = $e[cto];
                $sustituciones[3] = $e[amount_to];
                $sustituciones[4] = $e[currency_to];
                $sustituciones[5] = $reason;
                $sustituciones[6] = $settings[email];
                $sustituciones[7] = $e[exchange_id];
                $sustituciones[8] = $settings[url];
                
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[recibido]);
                
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));

                  if(!$mailto->send()) {
                    
                    return false;
                } else {
                    return true;
                }
	}
}

function emailsys_new_user($name,$username,$password,$email) {
	global $db, $settings;
        
            require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");
            $mailto->addCC('admin@intermoneda.com', "Nuevo usuario registrado");
                $mailto->Subject = "Registro de cuenta en ".$settings[name];
		$bodyQuery = $db->query("SELECT nuevo FROM ec_emails WHERE id=1");
                $mybody = $bodyQuery->fetch_assoc();
                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{USER}/';
                $patrones[2] = '/{PASS}/';
                $patrones[3] = '/{SETEMAIL}/';

                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $username;
                $sustituciones[2] = $password;
                $sustituciones[3] = $settings[email];
                
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[nuevo]);
                
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));

                  if(!$mailto->send()) {
                    
                    return false;
                } else {
            $gmail = new PHPMailer(true);
            $gmail->isSMTP();
            $gmail->Host = "mail.intermoneda.com";
            $gmail->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $gmail->Username   = "admin@intermoneda.com";
            $gmail->Password = "Escalinata1";
            $gmail->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $gmail->Timeout   = 30;                         
            $gmail->Port = 465;
            $gmail->setFrom("admin@intermoneda.com", "Admin");
            $gmail->addAddress('admin@intermoneda.com', "Nuevo intercambio producido");
            $gmail->Subject = "Nuevo usuario en el sitio";
            $gmail->MsgHTML("Un nuevo usuario se ha registrado en el sitio, favor de checar");
            $gmail->Send();
                 return true;
                }
}

function emailsys_new_password($email,$key,$username,$name) {
	global $db, $settings;
            require 'class.phpmailer.php';
            $mailto = new PHPMailer(true);
            $mailto->isSMTP();
            $mailto->Host = "mail.intermoneda.com";
            $mailto->SMTPAuth = true;
            //$mailto->Debugoutput = 'html';
            $mailto->Username   = "admin@intermoneda.com";
            $mailto->Password = "Escalinata1";
            $mailto->SMTPSecure = 'ssl';                           // Enable TLS encryption, `ssl` also accepted
            $mailto->Timeout   = 30;                         
            $mailto->Port = 465;
            $mailto->setFrom("admin@intermoneda.com", "Admin");
            $emailname = explode("@",$email);
            $mailto->addAddress($email, $emailname[0]);
            $mailto->addReplyTo("admin@intermoneda.com", "Informacion");
                $mailto->Subject = "Recuperación de contraseña en ".$settings[name];
		$bodyQuery = $db->query("SELECT password FROM ec_emails WHERE id=1");
                $mybody = $bodyQuery->fetch_assoc();
                $patrones = array();
                $patrones[0] = '/{EMAIL_NAME}/';
                $patrones[1] = '/{URL}/';
                $patrones[2] = '/{KEY}/';
                $patrones[3] = '/{SETEMAIL}/';
                $sustituciones = array();
                $sustituciones[0] = $emailname[0];
                $sustituciones[1] = $settings[url];
                $sustituciones[2] = $key;
                $sustituciones[3] = $settings[email];
                
                $mybody  = preg_replace($patrones ,$sustituciones, $mybody[password]);
                
            $mailto->IsHTML(true);
            $mailto->MsgHTML(html_entity_decode($mybody));

                  if(!$mailto->send()) {
                    
                    return false;
                } else {
                    return true;
                }
}

function emailsys_message_to_admin($uname,$uemail,$subject,$message) {
	global $settings;
	$msubject = '['.$settings[name].'] '.$subject;
	$mreceiver = $settings[email];
	$headers = 'From: '.$uemail.'' . "\r\n" .
		'Reply-To: '.$uemail.'' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	$mail = mail($mreceiver, $msubject, $message, $headers);
	if($mail) { 
		return true;
	} else {
		return false;
	}
}

// function checklicense() {
// 	global $db, $settings;
// 	$license_key = 'madebyadmin-02c2afd565d3f19-031d7390-13823-e12';
// 	$domain = $_SERVER['SERVER_NAME'];
// 	$checkurl = 'http://license.exchangesoftware.info/?check=1&license_key='.$license_key.'&domain='.$domain;
// 	$contents = file_get_contents($checkurl);
// 	$json_a=json_decode($contents,true);
	
// 	foreach ($json_a as $key => $value){
// 		$string[$key] = $value;
// 	}
								
// 	if($string['status'] == "error") {
// 		die($string['message']);
// 	}
// } 

// if(!file_exists("install.php")) { checklicense(); }
?>