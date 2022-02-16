#!/usr/local/bin/php
<?php
	session_name('hw6');
	session_start();
	// if logout pressed, destroy session and return to index.php
	if(isset($_GET['log_out'])){
		session_unset();
		session_destroy();
		echo("<script>window.location = 'index.php';</script>");
	}
?>