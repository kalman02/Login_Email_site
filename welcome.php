#!/usr/local/bin/php
<?php
	session_name('hw6');
	session_start();
	if($_SESSION['loggedin']) { ?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="UTF-8">
	<title>Welcome</title>
</head>
<body>
	<p>Welcome!</p>
	<?php echo "Your email address is " . $_SESSION['email'];?><br>
	<?php
		$member = fopen('password.txt', 'r') or die('cannot open file');
		echo "Here is a list of all registered addresses: ";
		while(!feof($member)){
			$memberEmail = explode("\t", fgets($member)); // iterate list of members to paste on page
			echo $memberEmail[0] . " ";
		}
		fclose($member);
	?>
	<form method="get" action="logout.php">
		<br>
		<input type="submit" name="log_out" value="log out" />
	</form>
</body>
</html> 
<?php } ?>