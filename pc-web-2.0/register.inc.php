<?php

// require 'connect.inc.php';

$register_msg = '';
$username = '';
$password = '';
$con_password = '';

// session_start();

if (isset($_POST['register']))
{
	if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['con_password']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$con_password = $_POST['con_password'];

		if (strlen($username) >= 6 && strlen($password) >= 6)
		{
			if ($password == $con_password)
			{
				if ($_POST['csrf_token'] == 'pongme')
				{
					// Form submission was successful. Check for user in database.
					$login_query = 'SELECT * FROM `registrations` WHERE `username`="'.$username.'"';

					if ($run_login_query = mysql_query($login_query))
					{
						if (mysql_num_rows($run_login_query) == 0)
						{
							// The username is available.
							$register_msg = 'Registration successful. Please login.';

							$register_query = 'INSERT INTO `registrations`(`username`, `password`) VALUES ("'.$username.'", "'.$password.'")';

							if ($run_register_query = mysql_query($register_query))
							{
								$_SESSION['login_msg'] = $register_msg;

								header('Location: index.php');
							}
						}
						else
						{
							// The username is already taken.
							$register_msg = 'The username you have chosen has already been taken.';
						}
					}
				}
			}
			else
			{
				$register_msg = 'The passwords do not match.';
			}
		}
		else
		{
			$register_msg = 'Usernames and passwords must be at least six characters long.';
		}
	}
	else
	{
		$register_msg = 'All fields are required.';
	}

	$_SESSION['register_msg'] = $register_msg;
	// header('Location: new_registration.php');
}

?>

<!-- <html>
<head>
<title>Pong Champ</title>
<link rel="stylesheet" href="css/pc.css" />
<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC' rel='stylesheet' type='text/css' />
</head>
<body> -->
<?php if (!isset($_SESSION['username'])) { ?>
<div id="login_form">
	<form action="new_registration.php" method="post">
		<input type="hidden" name="csrf_token" value="pongme">
		<table id="registration">
			<tr>
				<td>Username</td>
				<td><input type="text" name="username" placeholder="New Username" class="form_input" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="password" placeholder="New Password" class="form_input" /></td>
			</tr>
			<tr>
				<td>Confirm Password</td>
				<td><input type="password" name="con_password" placeholder="Confirm Password" class="form_input" /></td>
			</tr>
		</table>
		<input type="submit" name="register" value="Register" class="form_submit" />
	</form>
</div>
<p id="error_msg"><?php if (isset($_SESSION['register_msg'])) { echo $_SESSION['register_msg']; } ?></p>
<?php } else { ?>
<div id="account_already_exists">
	<p>You don't need an account, you are already logged in. If you want to create a new account, please logout first.</p>
</div>
<?php } ?>
<!-- </body>
</html> -->