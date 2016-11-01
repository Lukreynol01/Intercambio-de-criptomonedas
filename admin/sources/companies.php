 <div class="content-wrapper">
        <div class="container">
				<?php
				$b = protect($_GET['b']);
				if($b == "currencies") {
					$c = protect($_GET['c']);
					if($c == "add") {
						include("companies/currencies/add.php");
					} elseif($c == "edit") {
						include("companies/currencies/edit.php");
					} elseif($c == "delete") {
						include("companies/currencies/delete.php");
					} else {
						include("companies/currencies/index.php");
					}
				} elseif($b == "account") {
					include("companies/account.php");
				} elseif($b == "settings") {
					include("companies/settings.php");
				}  elseif($b == "allow") {
					include("companies/allow.php");
				}  elseif($b == "disallow") {
					include("companies/disallow.php");
				} elseif($b == "list") {
					$c = protect($_GET['c']);
					if($c == "allow") {
						include("companies/list/allow.php");
					} elseif($c == "disallow") {
						include("companies/list/disallow.php");	
					} else {	
						include("companies/list/index.php");
					}
				} else {
					include("companies/index.php");
				}
				?>
		</div>
   </div>