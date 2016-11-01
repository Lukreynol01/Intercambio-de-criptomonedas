<?php if(checkSession()) { header("Location: $settings[url]"); } ?>
	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h3><?php echo $lang['password_reset']; ?></h3>
					<form action="" method="POST">
						<div class="row">
								<hr>
								<?php 
								if(isset($_POST['ec_reset'])) {
									$email = protect($_POST['email']);
									$check = $db->query("SELECT * FROM ec_users WHERE email='$email'");
									if($check->num_rows>0) {
										$row = $check->fetch_assoc();
										$random = randomHash(10);
										$update = $db->query("UPDATE ec_users SET password_recovery='$random' WHERE id='$row[id]'");
										emailsys_new_password($row['email'],$random,$row['username'],$row['name']);
										echo success($lang['success_3']);
									} else {
										echo error($lang['error_20']);
									}
								}
								?>
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="email" placeholder="<?php echo $lang['email_address']; ?>">
								</div>
								<hr>
								<button type="submit" class="btn btn-primary pull-left" name="ec_reset"><i class="fa fa-check"></i> <?php echo $lang['btn_reset']; ?></button>
								<span class="pull-left" style="margin-left:15px;">
								<?php echo $lang['no_have_account']; ?> <a href="<?php echo $settings['url']; ?>register"><?php echo $lang['create']; ?></a><br/>
								<?php echo $lang['already_have_account']; ?> <a href="<?php echo $settings['url']; ?>login"><?php echo $lang['login']; ?></a>
								</span>
						</div>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>