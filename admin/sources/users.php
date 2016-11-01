 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "edit") {
					include("users/edit.php");
				} elseif($b == "delete") {
					include("users/delete.php");
				} elseif($b == "exchanges") {
					include("users/exchanges.php");
				} else {
					include("users/index.php");
				}
				?>
		</div>
   </div>