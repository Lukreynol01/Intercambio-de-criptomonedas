	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$b = protect($_GET['b']);
							if($b == "submit") {
								if(checkSession()) {
									echo '<h4>'.$lang[submit_testimonial].'</h4>';
									$checkExchange = $db->query("SELECT * FROM ec_exchanges WHERE uid='$_SESSION[ec_uid]' and status='4'");
									if($checkExchange->num_rows>0) {
										$checkTestimonial = $db->query("SELECT * FROM ec_testimonials WHERE uid='$_SESSION[ec_uid]'");
										if($checkTestimonial->num_rows>0) {
											echo error($lang['error_13']);
										} else {
											if(isset($_POST['ec_submit'])) {
												$hide_form=0;
												$content = protect($_POST['content']);
												$time = time();
												if(empty($content)) { echo error($lang['error_14']); }
												elseif(strlen($content)<30) { echo error($lang['error_15']); }
												else {
													$insert = $db->query("INSERT ec_testimonials (uid,content,status,time) VALUES ('$_SESSION[ec_uid]','$content','0','$time')");
													echo success($lang['success_2']);
													$hide_form=1;
												}
											}
											if($hide_form==0) {
											?>
											<form action="" method="POST">
												<div class="form-group">
													<textarea class="form-control" name="content" rows="5" placeholder="<?php echo $lang['enter_feedback']; ?>"></textarea>
												</div>
												<button type="submit" class="btn btn-primary" name="ec_submit"><i class="fa fa-check"></i> <?php echo $lang['btn_submit']; ?></button>
											</form>
											<?php
											}
										}
									} else {
										echo error($lang['error_16']);
									}
								} else {
									header("Location:$settings[url]");
								}
							} else {
							?>
							<h4><?php echo $lang['testimonials']; ?></h4>
							<?php
							$query = $db->query("SELECT * FROM ec_testimonials WHERE status='1' ORDER BY id");
							if($query->num_rows>0) {
								while($row = $query->fetch_assoc()) {
									?>
									<div class="col-sm-6 col-md-6 col-lg-6">
										<blockquote>
										  <p><?php echo $row['content']; ?></p>
										  <footer><cite><?php echo idinfo($row['uid'],"name"); ?> (<?php echo idinfo($row['uid'],"username"); ?>)</cite></footer>
										</blockquote>
									</div>
									<?php
								}
							} else {
								echo $lang['no_testimonials'];
							}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>