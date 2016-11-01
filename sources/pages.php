	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
							  <li><a href="<?php echo $settings['url']; ?>pages/faq" class="list-group-item"><?php echo $lang['faq']; ?></a></li>
							  <li><a href="<?php echo $settings['url']; ?>pages/contact-us" class="list-group-item"><?php echo $lang['contact_us']; ?></a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$b = protect($_GET['b']);
							if($b == "faq") {
								include("pages/faq.php");
							} elseif($b == "contact-us") {
								include("pages/contact-us.php");
							} else {
								$redirect = $settings['url']."pages/faq";
								header("Location: $redirect");
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>