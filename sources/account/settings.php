<h4><?php echo $lang['settings']; ?></h4>

<?php
if(isset($_POST['ec_save'])) {
	$cpassword = protect($_POST['cpassword']);
	$npassword = protect($_POST['npassword']);
	$cnpassword = protect($_POST['cnpassword']);
	$mpass = md5($npassword);
	$cpass = md5($cpassword);
	
	if(empty($cpassword) or empty($npassword) or empty($cnpassword)) { echo error($lang['error_5']); }
	elseif($cpass !== idinfo($_SESSION['ec_uid'],"password")) { echo error($lang['error_22']); }
	elseif($npassword !== $cnpassword) { echo error($lang['error_23']); }
	else {
		$update = $db->query("UPDATE ec_users SET password='$mpass' WHERE id='$_SESSION[ec_uid]'");
		echo success($lang['success_4']);
	}
}
?>

<form action="" method="POST">
	<div class="form-group">
		<label><?php echo $lang['currenct_password']; ?></label>
		<input type="password" class="form-control" name="cpassword">
	</div>
	<div class="form-group">
		<label><?php echo $lang['new_password']; ?></label>
		<input type="password" class="form-control" name="npassword">
	</div>
	<div class="form-group">
		<label><?php echo $lang['confirm_password']; ?></label>
		<input type="password" class="form-control" name="cnpassword">
	</div>
	<button type="submit" class="btn btn-primary" name="ec_save"><i class="fa fa-check"></i> <?php echo $lang['btn_change_password']; ?></button>
</form>