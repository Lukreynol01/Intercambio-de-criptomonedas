<?php
$id = protect($_GET['id']);
$query = $db->query("SELECT * FROM ec_users WHERE id='$id'");
if($query->num_rows==0) {
	$redirect = './?a=users';
	header("Location: $redirect");
}
$row = $query->fetch_assoc();
?>
	<div class="content-wrapper">
        <div class="container">
			 <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Users / Edit</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							if(isset($_POST['btn_save'])) { 
								$name = protect($_POST['name']);
								$username = protect($_POST['username']);
								$email = protect($_POST['email']);
								$status = protect($_POST['status']);
								$npass = protect($_POST['npass']);
								$check_u = $db->query("SELECT * FROM ec_users WHERE username='$username'");
								$check_e = $db->query("SELECT * FROM ec_users WHERE email='$email'");
								if(empty($name) or empty($username) or empty($email) or empty($status)) { echo error("Required fields: Name,Username,Email and Status."); }
								elseif(!isValidUsername($username)) { echo error("Please enter valid username."); }
								elseif($row['username'] !== $username && $check_u->num_rows>0) { echo error("This username is already used. Please choose another."); }
								elseif(!isValidEmail($email)) { echo error("Please enter valid email address."); }
								elseif($row['email'] !== $email && $check_e->num_rows>0) { echo error("This email address is already used. Please choose another."); }
								elseif(!empty($npass) && strlen($npass)<8) { echo error("New password must be more than 8 characters."); }
								else {
									if(empty($npass)) { $password = $row['password']; } else { $password = md5($npass); }
									$update = $db->query("UPDATE ec_users SET name='$name',username='$username',password='$password',email='$email',status='$status' WHERE id='$row[id]'");
									$query = $db->query("SELECT * FROM ec_users WHERE id='$row[id]'");
									$row = $query->fetch_assoc();
									echo success("Your changes was saved successfully.");
								}
							}
							?>
							
							<form action="" method="POST">
								<div class="form-group">
									<label>Name</label>
									<input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
								</div>
								<div class="form-group">
									<label>Username</label>
									<input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>">
								</div>
								<div class="form-group">
									<label>Email address</label>
									<input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>">
								</div>
								<div class="form-group">
									<label>Status</label>
									<select class="form-control" name="status">
										<option value="1" <?php if($row['status'] == "1") { echo 'selected'; } ?>>Active</option>
										<option value="2" <?php if($row['status'] == "2") { echo 'selected'; } ?>>Blocked</option>
										<option value="666" <?php if($row['status'] == "666") { echo 'selected'; } ?>>Admin</option>
									</select>
								</div>
								<div class="form-group">
									<label>New password</label>
									<input type="text" class="form-control" name="npass" placeholder="Leave blank if do not want to change password.">
								</div>
								<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
   </div>