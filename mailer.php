<?

function envio_email($correo, $subject, $body){

	require 'class.phpmailer.php';
	try {
		$mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas
		
		
		$mail->IsSMTP();                           // Usamos el metodo SMTP de la clase PHPMailer
		$mail->SMTPAuth   = true;                  // habilitado SMTP autentificación
		$mail->SMTPSecure = "ssl"; 
		$mail->Timeout   = 30;                  // habilitado SMTP autentificación
		
		$mail->Port       = 465;                // puerto del server SMTP
		$mail->Host       = "mail.intermoneda.com"; 	// SMTP server
		$mail->Username   = "admin@intermoneda.com";     // SMTP server Usuario
		$mail->Password   = "Escalinata1";            // SMTP server password
		$mail->From       = "admin@intermoneda.com"; //Remitente de Correo
		$mail->FromName   = "Prueba PHPmailer"; //Nombre del remitente
		$to = $correo; //Para quien se le va enviar
		$mail->AddAddress($to);
		$mail->Subject  = $subject; //Asunto del correo
		$mail->MsgHTML($body);
		$mail->IsHTML(true); // Enviar como HTML
		$mail->Send();//Enviar
		// echo 'El Mensaje a sido enviado.';
		              if(!$mail->send()) {
                    
                    echo 'Mailer Error: '.$mailto->ErrorInfo;
                    echo 'Mailer Error: '.$mailto->errorMessage();
                    return false;
                } else {
                    return true;
                }
	} catch (phpmailerException $e) {
		echo $e->errorMessage();//Mensaje de error si se produciera.
	}
}
?>

