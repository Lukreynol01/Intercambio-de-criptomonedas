 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "edit") {
					include("faq/edit.php");
				} elseif($b == "delete") {
					include("faq/delete.php");
				} elseif($b == "add") {
					include("faq/add.php");
				} else {
					include("faq/index.php");
				}
				?>
		</div>
   </div>