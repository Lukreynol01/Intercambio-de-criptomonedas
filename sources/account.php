<?php if(!checkSession()) { $redirect = $settings['url']."login"; header("Location: $redirect"); } ?>
	<section style="margin-top:50px;"> 
        <div class="container">
            <div class="row">
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-body">
							<ul class="nav nav-pills nav-stacked">
							  <li><a href="<?php echo $settings['url']; ?>testimonials/submit" class="list-group-item"><?php echo $lang['submit_testimonial']; ?></a></li>
							  <li><a href="<?php echo $settings['url']; ?>account/exchanges" class="list-group-item"><?php echo $lang['my_exchanges']; ?></a></li>
							  <li><a href="<?php echo $settings['url']; ?>account/settings" class="list-group-item"><?php echo $lang['settings']; ?></a></li>
                                                           <li><a href="<?php echo $settings['url']; ?>blog" class="list-group-item">Blog</a></li>
                                                           <li><a href="<?php echo $settings['url']; ?>pages/faq" class="list-group-item">Preguntas frecuentes</a></li>
                                                           <li><a href="<?php echo $settings['url']; ?>pages/contact-us" class="list-group-item">Soporte</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-body">
							<?php
							$b = protect($_GET['b']);
							if($b == "exchanges") {
								include("account/exchanges.php");
							} elseif($b == "settings") {
								include("account/settings.php");
							} else {
								$redirect = $settings['url']."account/exchanges";
								header("Location: $redirect");
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>