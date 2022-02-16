#!/usr/local/bin/php
<?php
	session_name('hw6');
	session_start();
	$_SESSION['loggedin'] = false;
	$_SESSION['email'] = '';


	/**
	checkRegistrationEmail - function to test if email contains an @ with numbers or letters on each side
	@param $address - string user input from "email:"
	@param &$error -  pointer to bool, will become true if an error exists
	*/

	function checkRegistrationEmail($address, &$error){
		$length = strlen($address);
		$error = true;
		for ($i=0; $i<$length; $i++){
			if (ctype_alpha($address[$i]) === false and ctype_digit($address[$i]) === false) {$error = true;}
			if ($address[$i] === '@') {$error = false;}
			if ($address[$i] === '.') {$error = false;}
		}
		if ($address[0] === '@' || $address[$length-1] === '@'){
			$error = true;
		}
	}

	/**
	checkRegistrationPassword - function makes sure password is appropriate length and only consists of letters and numbers
	@param $password - string user input
	@param $error - pointer to bool, will become true if an error exists
	*/	

	function checkRegistrationPassword($password, &$error){
		$length = strlen($password);
		if ($length < 6) {$error = true;}
		for ($i=0; $i<$length; $i++){
			if (ctype_alpha($password[$i]) === false and ctype_digit($password[$i]) === false) {$error = true;}
		}
	}

	/**
	validate function - makes sure user has not already been added to list
	@param $address - string email user input
	@param $error - pointer to bool, will become true if an error exists
	*/

	function validate($address, &$error){
		$member = fopen('password.txt', 'r') or die('cannot open file');
		while(!feof($member)){
			$line=fgets($member);
			$fields = explode("\t", $line);
			if ($fields[0] === $address){
				$error = true;
			}
		}
		fclose($member);
	}

	/**
	loginEmailPassword - function checks encoded list to make sure email exists and matches password
	@param $address - string email user input
	@param $error - pointer to bool, will become true if an error exists
	*/
	function loginEmailPassword($address, $password, &$error){
		$member = fopen('password.txt', 'r') or die('cannot open file');
		$error = true;
		$h_pass = hash('md2', $password);
		while(!feof($member)){
			$line=fgets($member);
			$fields = explode("\t", $line);
			$fields[1] = trim($fields[1]);
			if ($fields[1] === $h_pass && $fields[0] === $address){ 
				$error = false;
			}
		}
		fclose($member);
	}

	$error1 = false; //email registration
	$error2 = false; //password registration
	$error3 = false; //repeated email?
	$error4 = false; //email and password login
	if(isset($_POST['Register'])){ 
		validate($_POST['email'], $error3);
		if(!$error3){ 
			checkRegistrationEmail($_POST['email'], $error1);
			if(!$error1){
				checkRegistrationPassword($_POST['pass'], $error2);
				if(!$error2){					
					$_SESSION['email'] = $_POST['email'];
					$_SESSION['pass'] = $_POST['pass'];

					echo "A validation email has been sent to: " . $_SESSION['email'] . ". Please follow the link.";
    					$to = $_POST['email'];
    					$subject = "Validation";
   					$link = 'https://www.pic.ucla.edu/~kalman02/HW6/validate.php';
    					$message = "Validate by clicking here: ".$link;
    					mail($to,$subject,$message,"From:" . $_POST['email']);
				}
			}
		}
	}
	else if (isset($_POST['Log_in'])){
		loginEmailPassword($_POST['email'], $_POST['pass'], $error4);
		if(!$error4){ 
			$_SESSION['loggedin'] = true;
			$_SESSION['email'] = $_POST['email'];
			echo("<script>window.location = 'welcome.php';</script>");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login/Register</title>
</head>
<body>
	<main>
		<form method = "post" action ="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset>
				<p>
					<label for="email">email address: </label>
					<input type="text" name="email" id="email"/>
				</p>
				<p>
					<label for="pass">password(&ge; 6 characters letters or digits): </label>
					<input type="password" name="pass" id="pass"/>
				</p>
			</fieldset>
			<input type="submit" name="Register" value="Register" />
			<input type="submit" name="Log_in" value="Log in" />
			
			<?php if($error1) { ?>
				<p>The email address is invalid, must contain @ surrounded by letters and numbers</p> <?php
			} ?>
			<?php if($error2) { ?>
				<p>The password must be at least 6 characters, with letters and digits only.</p> <?php
			} ?>
			<?php if($error3) { ?>
				<p>Already registered. Please log in/validate.</p> <?php
			} ?>

			<?php if($error4) { ?>
				<p>No such email address or invalid password</p> <?php
			} ?>
					
		</form>
	</main>
</body>
</html>