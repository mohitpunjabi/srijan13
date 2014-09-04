<?php
	include_once "../include/Session.class.php";
	include_once "../include/Admin.class.php";

	if(Session::getCurrentAdminId()) {
		header("Location: welcome.php");
		exit;
	}

	if(isset($_POST) && isset($_POST['login-submit'])) {	
		$admin = new Admin();
		$loggedIn = $admin->logAdminIn(mysql_real_escape_string($_POST['username']), mysql_real_escape_string($_POST['password']));

		if($loggedIn) {
			header("Location: welcome.php");
			exit;
		}
		else {
			echo "Invalid username/password";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>

</head>

<body>

<form id="login-form" name="login-form" method="post" action="index.php">
	<table>
    	<tr>
        	<td>Username</td>
            <td><input type="text" name="username" id="username" /></td>
        </tr>

    	<tr>
        	<td>Password</td>
            <td><input type="password" name="password" id="password" /></td>
        </tr>

        <tr>
        	<td></td>
        	<td><input type="submit" value="Login" name="login-submit" /></td>
        </tr>
    </table>
</form>

New users <a href="register.php">Register here</a>
</body>
</html>