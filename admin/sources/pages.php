 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "edit") {
					include("pages/edit.php");
				} elseif($b == "delete") {
					include("pages/delete.php");
				} elseif($b == "add") {
					include("pages/add.php");
				} else {
					include("pages/index.php");
				}
				?>
		</div>
   </div>