<h4><?php echo $lang['contact_us']; ?></h4>

<?php
if(isset($_POST['ec_send'])) {
	$uname = protect($_POST['uname']);
	$uemail = protect($_POST['uemail']);
	$subject = protect($_POST['subject']);
	$message = protect($_POST['message']);
	
	if(empty($uname) or empty($uemail) or empty($subject) or empty($message)) { echo error($lang['error_5']); }
	elseif(!isValidEmail($uemail)) { echo error($lang['error_8']); }
	else {
		$msg = emailsys_message_to_admin($uname,$uemail,$subject,$message);
		if($msg) {
			echo success($lang['success_4']);
		} else {
			echo error("$lang[error_21] $settings[email]");
		}
	}
}
?>

<form action="" method="POST">
	<div class="form-group">
		<label><?php echo $lang['your_name']; ?></label>
		<input type="text" class="form-control" name="uname">
	</div>
	<div class="form-group">
		<label><?php echo $lang['your_email_address']; ?></label>
		<input type="text" class="form-control" name="uemail">
	</div>
	<div class="form-group">
		<label><?php echo $lang['subject']; ?></label>
		<input type="text" class="form-control" name="subject">
	</div>
	<div class="form-group">
		<label><?php echo $lang['message']; ?></label>
		<textarea class="form-control" rows="3" name="message"></textarea>
	</div>
	<button type="submit" class="btn btn-primary" name="ec_send"><i class="fa fa-reply"></i> <?php echo $lang['btn_send_message']; ?></button>
</form>