<?php

require 'connect.inc.php';

$login_msg = '';
$username = '';
$password = '';

if (isset($_POST['login']))
{
	session_start();

	if (!empty($_POST['username']) && !empty($_POST['password']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];

		if ($_POST['csrf_token'] == 'pongme')
		{
			// Form submission was successful. Check for user in database.
			$login_query = 'SELECT * FROM `registrations` WHERE `username`="'.$username.'" AND `password`="'.$password.'"';

			if ($run_login_query = mysql_query($login_query))
			{
				// Make sure password is case sensitive as well.
				if (mysql_num_rows($run_login_query) == 1  && strcmp(mysql_result($run_login_query, 0, 'password'), $password) == 0)
				{
					// User successfully logged in.
					$login_msg = 'Login successful.';

					$_SESSION['username'] = $username;

					if ($run_id_query = mysql_query('SELECT `id` FROM `registrations` WHERE `username`="'.$username.'"'))
					{
						$userid = mysql_result($run_id_query, 0, 'id');

						$_SESSION['userid'] = $userid;
					}
				}
				else
				{
					// Username or password is incorrect.
					$login_msg = 'The username or password you have entered is incorrect.';
				}
			}
		}
	}
	else
	{
		$login_msg = 'You must enter a username and a password.';
	}

	$_SESSION['login_msg'] = $login_msg;

	header('Location: index.php');
}

?>

<html>
<head>
<title>Pong Champ</title>
<link rel="stylesheet" href="css/pc.css" />
<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC' rel='stylesheet' type='text/css' />
</head>
<body>
<?php if (!isset($_SESSION['username'])) { ?>
<div id="login_form">
	<form action="login.inc.php" method="post">
		<input type="hidden" name="csrf_token" value="pongme">
		<input type="text" name="username" placeholder="Username" class="form_input" />
		<input type="password" name="password" placeholder="Password" class="form_input" />
		<input type="submit" name="login" value="Login" class="form_submit" />
	</form>
	<p id="need_account">Don't have an account? <a href="new_registration.php">Register here.</a></p>
	<p id="error_msg"><?php if (isset($_SESSION['login_msg'])) { echo $_SESSION['login_msg']; } ?></p>
</div>
<?php } else { ?>
<div id="logout_form">
	<table>
		<tr>
			<td><h3 id="welcome_msg">Welcome, <?php echo $_SESSION['username']; ?></h3></td>
			<td>
				<form action="logout.inc.php" method="post">
					<input type="submit" name="logout" value="Logout" class="form_submit" />
				</form>
			</td>
		</tr>
	</table>
</div>
<?php } ?>
</body>
</html>