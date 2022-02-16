#!/usr/local/bin/php
<?php
	session_name('hw6');
	session_start();
	$_SESSION['loggedin'] = true;

	// add email and password to member list
	$member = fopen('password.txt', 'a') or die('cannot open');
	$hashed_pass = hash('md2', $_SESSION['pass']);
	fwrite($member, $_SESSION['email']);
	fwrite($member, "\t");
	fwrite($member, $hashed_pass);
	fwrite($member, "\n");
	fclose($member);

	// alert successful registration and send user to welcome page
	$message = 'Registration successful';
	echo("<script>alert('$message')</script>");
	echo("<script>window.location = 'welcome.php';</script>");
?>