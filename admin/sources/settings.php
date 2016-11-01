 <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Web Settings</h4>
                </div>
            </div>
			
            <div class="row">
				<div class="col-md-12">
			
                        <div class="panel panel-default">
                            <div class="panel-body">
								<?php
								if(isset($_POST['btn_save'])) {
									$title = protect($_POST['title']);
									$description = protect($_POST['description']);
									$keywords = protect($_POST['keywords']);
									$name = protect($_POST['name']);
									$email = protect($_POST['email']);
									$url = protect($_POST['url']);
									$fb_link = protect($_POST['fb_link']);
									$tw_link = protect($_POST['tw_link']);
									$gp_link = protect($_POST['gp_link']);
									$li_link = protect($_POST['li_link']);
									$dr_link = protect($_POST['dr_link']);
                                                                        $emailhost = protect($_POST['emailhost']);
									$usermail = protect($_POST['usermail']);
									$emailpass = protect($_POST['emailpass']);
									
									if(empty($title) or empty($description) or empty($keywords) or empty($name) or empty($email) or empty($url)) { echo error("Required fields: Title,Description,Keywords,Name,Email and Url address."); }
									elseif(!isValidEmail($email)) { echo error("Please enter valid site email address."); }
                                                                        elseif(!isValidEmail($usermail)) { echo error("Please enter valid site email address."); }
									elseif(!isValidURL($url)) { echo error("Please enter valid site url address."); }
									elseif(!empty($fb_link) && !isValidURL($fb_link)) { echo error("Please enter valid Facebook link."); }
									elseif(!empty($tw_link) && !isValidURL($tw_link)) { echo error("Please enter valid Twitter link."); }
									elseif(!empty($gp_link) && !isValidURL($gp_link)) { echo error("Please enter valid Google+ link."); }
									elseif(!empty($li_link) && !isValidURL($li_link)) { echo error("Please enter valid Linkedin link."); }
									elseif(!empty($dr_link) && !isValidURL($dr_link)) { echo error("Please enter valid Dribbble link."); }
									else {
										$update = $db->query("UPDATE ec_settings SET title='$title',description='$description',keywords='$keywords',name='$name',url='$url',email='$email',fb_link='$fb_link',tw_link='$tw_link',gp_link='$gp_link',li_link='$li_link',dr_link='$dr_link',emailhost='$emailhost',usermail='$usermail',emailpass='$emailpass'");
										$settingsQuery = $db->query("SELECT * FROM ec_settings ORDER BY id DESC LIMIT 1");
										$settings = $settingsQuery->fetch_assoc();
										echo success("Your changes was saved successfully.");
									}
								}
								?>
								
								<form action="" method="POST">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" name="title" value="<?php echo $settings['title']; ?>">
									</div>
									<div class="form-group">
										<label>Description</label>
										<textarea class="form-control" rows="2" name="description"><?php echo $settings['description']; ?></textarea>
									</div>
									<div class="form-group">
										<label>Keywords</label>
										<textarea class="form-control" rows="2" name="keywords"><?php echo $settings['keywords']; ?></textarea>
									</div>
									<div class="form-group">
										<label>Site name</label>
										<input type="text" class="form-control" name="name" value="<?php echo $settings['name']; ?>">
									</div>
									<div class="form-group">
										<label>Site email address (For contact form, notifications and etc.)</label>
										<input type="text" class="form-control" name="email" value="<?php echo $settings['email']; ?>">
									</div>
									<div class="form-group">
										<label>Site url address</label>
										<input type="text" class="form-control" name="url" value="<?php echo $settings['url']; ?>">
									</div>
									<div class="form-group">
										<label>Facebook link</label>
										<input type="text" class="form-control" name="fb_link" value="<?php echo $settings['fb_link']; ?>">
									</div>
									<div class="form-group">
										<label>Google+ link</label>
										<input type="text" class="form-control" name="gp_link" value="<?php echo $settings['gp_link']; ?>">
									</div>
									<div class="form-group">
										<label>Twitter link</label>
										<input type="text" class="form-control" name="tw_link" value="<?php echo $settings['tw_link']; ?>">
									</div>
									<div class="form-group">
										<label>Linkedin link</label>
										<input type="text" class="form-control" name="li_link" value="<?php echo $settings['li_link']; ?>">
									</div>
									<div class="form-group">
										<label>Dribbble link</label>
										<input type="text" class="form-control" name="dr_link" value="<?php echo $settings['dr_link']; ?>">
									</div>
                                                                    <H3>CONFIGURACION SERVIDOR DE CORREO</H3>
                                                                    <div class="form-group">
										
                                                                                <label>Servidor de correo saliente SMTP</label>
										<input type="text" class="form-control" name="emailhost" value="<?php echo $settings['emailhost']; ?>">
									</div>
                                                                        <div class="form-group">
										
                                                                                <label>Correo electrónico</label>
										<input type="text" class="form-control" name="usermail" value="<?php echo $settings['usermail']; ?>">
									</div>
                                                                    <div class="form-group">
										
                                                                                <label>Contraseña</label>
										<input type="password" class="form-control" name="emailpass" value="<?php echo $settings['emailpass']; ?>">
									</div>
									<button type="submit" class="btn btn-primary" name="btn_save"><i class="fa fa-check"></i> Save changes</button>
								</form>
                            </div>
                        </div>
						
                </div>
          
            </div>
        </div>
    </div>