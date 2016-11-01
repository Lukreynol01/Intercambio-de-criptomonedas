<?php if(checkSession()) { header("Location: $settings[url]"); } ?>
	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h3><?php echo $lang['password_recovery']; ?></h3>
					<form action="" method="POST">
						<div class="row">
								<hr>
								<?php 
								$hash = protect($_GET['hash']);
								$query = $db->query("SELECT * FROM ec_users WHERE password_recovery='$hash'");
								if($query->num_rows==0) { header("Location: $settings[url]"); }
								if(isset($_POST['ec_change'])) {
									$password = protect($_POST['password']);
									$cpassword = protect($_POST['cpassword']);
									$mpass = md5($password);
									if(empty($password)) { echo error($lang['error_17']); }
									elseif(strlen($password)<8) { echo error($lang['error_18']); }
									elseif($password !== $cpassword) { echo error($lang['error_19']); }
									else {
										$update = $db->query("UPDATE ec_users SET password='$mpass',password_recovery='' WHERE id='$row[id]'");
										$_SESSION['ec_uid'] = $row['id'];
										$_SESSION['ec_user'] = $row['username'];
										header("Location: $settings[url]");
									}
								}
								?>
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="password" placeholder="<?php echo $lang['new_password']; ?>">
								</div>
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="cpassword" placeholder="<?php echo $lang['confirm_password']; ?>">
								</div>
								<hr>
								<button type="submit" class="btn btn-primary pull-left" name="ec_change"><i class="fa fa-check"></i> <?php echo $lang['btn_change_password']; ?></button>
						</div>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>