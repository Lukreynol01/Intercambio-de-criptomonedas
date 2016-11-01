<?php
$b = protect($_GET['b']);
if($b == "reset") {
	include("password/reset.php");
} elseif($b == "recovery") {
	include("password/recovery.php");
} else {
	header("Location:$settings[url]");
}
?>