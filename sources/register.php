<?php if(checkSession()) { header("Location: $settings[url]"); } ?>
	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<h3><?php echo $lang['create_account']; ?></h3>
					<form action="" method="POST">
						<div class="row">
							<div class="col-md-12"><hr></div>
							<div class="col-md-12">
								<?php 
								if(isset($_POST['ec_register'])) {
									$name = protect($_POST['name']);
									$username = protect($_POST['username']);
									$password = protect($_POST['password']);
									$cpassword = protect($_POST['cpassword']);
									$email = protect($_POST['email']);
									$cemail = protect($_POST['cemail']);
									$mpass = md5($password);
									$check_username = $db->query("SELECT * FROM ec_users WHERE username='$username'");
									$check_email = $db->query("SELECT * FROM ec_users WHERE email='$email'");
									if(empty($name) or empty($username) or empty($password) or empty($cpassword) or empty($email) or empty($cemail)) { echo error($lang['error_5']); }
									elseif(!isValidUsername($username)) { echo error($lang['error_6']); }
									elseif($check_username->num_rows>0) { echo error($lang['error_7']); }
									elseif(!isValidEmail($email)) { echo error($lang['error_8']); }
									elseif($email !== $cemail) { echo error($lang['error_9']); }
									elseif($check_email->num_rows>0) { echo error($lang['error_10']); }
									elseif(strlen($password)<8) { echo error($lang['error_11']); }
									elseif($password !== $cpassword) { echo error($lang['error_12']); }
									else {
										$time = time();
										$ip = $_SERVER['REMOTE_ADDR'];
										$insert = $db->query("INSERT ec_users (name,username,password,email,ip,status,created) VALUES ('$name','$username','$mpass','$email','$ip','1','$time')");
										emailsys_new_user($name,$username,$password,$email);
										echo success($lang['success_1']);
									}
								}

								?>
							</div>
                                                     
                                                        </div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="name" placeholder="<?php echo $lang['your_name']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="username" placeholder="<?php echo $lang['username']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="email" placeholder="<?php echo $lang['email_address']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control input-lg" name="cemail" placeholder="<?php echo $lang['confirm_email_address']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="password" placeholder="<?php echo $lang['password']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="cpassword" placeholder="<?php echo $lang['confirm_password']; ?>">
								</div>
							</div>
							<div class="col-md-12"><hr></div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary pull-left" name="ec_register"><i class="fa fa-check"></i> <?php echo $lang['btn_register']; ?></button>
								<span class="pull-left" style="margin-left:15px;">
								<?php echo $lang['already_have_account']; ?> <a href="<?php echo $settings['url']; ?>login"><?php echo $lang['login']; ?></a><br/>
								<?php echo $lang['forgot_your_password']; ?> <a href="<?php echo $settings['url']; ?>password/reset"><?php echo $lang['reset']; ?></a>
								</span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>