<?php if(checkSession()) { header("Location: $settings[url]"); } ?>
	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h3><?php echo $lang['login']; ?></h3>
					<form action="" method="POST">
						<div class="row">
								<hr>
								<?php 
								if(isset($_POST['ec_login'])) {
									$username = protect($_POST['username']);
									$password = protect($_POST['password']);
									$password = md5($password);
									$check = $db->query("SELECT * FROM ec_users WHERE username='$username' and password='$password'");
									if(empty($username) or empty($password)) { echo error("Por favor ingrese su usuario y contrase&ntilde;a."); }
									elseif($check->num_rows==0) { echo error($lang['error_3']); }
									else {
										$row = $check->fetch_assoc();
										if($row['status'] == "2") { 
											echo error($lang['error_4']); 
										} else {
											if($_POST['remember_me'] == "yes") {
												setcookie("ec_user_id", $row['id'], time() + (86400 * 30), '/'); // 86400 = 1 day
												setcookie("ec_username", $row['username'], time() + (86400 * 30), '/'); // 86400 = 1 day
											}
											$_SESSION['ec_uid'] = $row['id'];
											$_SESSION['ec_user'] = $row['username'];
											header("Location: ./");
										}
									}
								}
								?>
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="username" placeholder="<?php echo $lang['username']; ?>">
								</div>
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="password" placeholder="<?php echo $lang['password']; ?>">
								</div>
								<div class="button-checkbox">
									<label><input type="checkbox" name="remember_me" id="remember_me" checked="checked" value="yes"> <?php echo $lang['remember_me']; ?></label>
								</div>
								<hr>
								<button type="submit" class="btn btn-primary pull-left" name="ec_login"><i class="fa fa-sign-in"></i> <?php echo $lang['btn_login']; ?></button>
								<span class="pull-left" style="margin-left:15px;">
								<?php echo $lang['no_have_account']; ?> <a href="<?php echo $settings['url']; ?>register"><?php echo $lang['create']; ?></a><br/>
								<?php echo $lang['forgot_your_password']; ?> <a href="<?php echo $settings['url']; ?>password/reset"><?php echo $lang['reset']; ?></a>
								</span>
						</div>
					</form>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>