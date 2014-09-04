<?php
	include_once "include/Session.class.php";
	include_once "include/User.class.php";
	
	if(Session::getCurrentUserId()) {
		header("location: home.php");
		exit;
	}

	$error = "";

	if(isset($_POST) && isset($_POST['login-submit'])) {	
		$user = new User();
		$loggedIn = $user->logUserIn(mysql_real_escape_string($_POST['email']), mysql_real_escape_string($_POST['password']));

		if($loggedIn) {
			header("Location: home.php");
			exit;
		}
		else {
			$error = "The username and password did not match. Please try again.";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Srijan is the annual socio-cultural fest of Indian School of Mines, Dhanbad. The festival is not only a panorama of events to test the cultural and artistic skills of the youth from all over the country, but it also endeavours to bring together eminent artists as well as celebrities across the globe, all in an attempt to bestow a memorable and spellbinding experience.">
<meta name="keywords" content="srijan, srijan 13, fest, ism fest, cult fest, sr13an, srijan 2013, cultural fest">
<meta name="robots" content="index, follow">
<link rel="icon" type="image/png" href="../favico.png">
<link rel="image_src" href="../favico.png" />
<title>Srijan 13 | Registrations</title>

<style type="text/css">
body {
	background: #FFFFEC;
	font-family: Arial, Helvetica, sans-serif;
}

form {
	border-radius: 5px;
	border: 1px #8A6952 solid;
	background: #FFFDB5;
	width: 400px;
	padding: 20px;
}
input[type=text], input[type=password] {
	padding: 5px;
	border-radius: 5px;
	border: 1px #C9BC9D solid;
}

input[type=button], input[type=submit] {
	padding: 5px 20px;
	font-size: 1.1em;
	background: #8A6952;
	color: #FFF;
	border-radius: 5px;
}

#error {
	color: #A00;
}

a {
	color: #8A6952;
	text-decoration: none;
	font-style: italic;
}

a:hover {
	text-decoration: underline;
}
</style>

</head>

<body>
<center>
<img src="../img/srijan13-logo-dark.png" width="400" />
<h1>Please login to continue</h1>

<p id="error">
<?php echo $error; ?>
</p>
<form id="login-form" name="login-form" method="post" action="index.php">
	<table cellpadding="5">
    	<tr>
        	<td align="right">Email</td>
            <td><input type="text" name="email" id="email" value="<?php echo ($error == "")? "": $_POST["email"]; ?>" /></td>
        </tr>

    	<tr>
        	<td align="right">Password</td>
            <td><input type="password" name="password" id="password" /></td>
        </tr>

        <tr>
        	<td></td>
        	<td><input type="submit" value="Login" name="login-submit" /></td>
        </tr>
    </table>
</form>
</center>
</body>
</html>