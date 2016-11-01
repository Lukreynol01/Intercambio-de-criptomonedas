<?php
 define("DB_HOST", 	$CONF['host']);				// hostname
 define("DB_USER", 	$CONF['user']);		// database username
 define("DB_PASSWORD", 	$CONF['pass']);		// database password
 define("DB_NAME", 	$CONF['name']);	// database name




/**
 *  ARRAY OF ALL YOUR CRYPTOBOX PRIVATE KEYS
 *  Place values from your gourl.io signup page
 *  array("your_privatekey_for_box1", "your_privatekey_for_box2 (otional), etc...");
 */
 
 $cryptobox_private_keys = array($acc[a_field_2]);




 define("CRYPTOBOX_PRIVATE_KEYS", implode("^", $cryptobox_private_keys));
 unset($cryptobox_private_keys); 

?>