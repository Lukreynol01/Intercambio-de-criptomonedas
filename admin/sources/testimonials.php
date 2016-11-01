 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "approve") {
					include("testimonials/approve.php");
				} elseif($b == "delete") {
					include("testimonials/delete.php");
				} else {
					include("testimonials/index.php");
				}
				?>
		</div>
   </div>