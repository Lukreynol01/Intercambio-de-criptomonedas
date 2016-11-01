 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "explore") {
					include("exchanges/explore.php");
				} elseif($b == "delete") {
					include("exchanges/delete.php");
				} else {
					include("exchanges/index.php");
				}
				?>
		</div>
   </div>