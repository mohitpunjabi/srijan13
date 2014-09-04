<?php
	include_once "include/Session.class.php";
	include_once "include/User.class.php";
	
	if(Session::getCurrentUserId()) {
		header("Location: home.php");
		exit;
	}

	$error = "";
	
	if(isset($_POST) && isset($_POST['register-submit'])) {
		$user = new User();
		try {
			$arr = array('name' => "",
						 'mobile' => "",
						 'institution' => "",
						 'email' => "",
						 'password' => "",
						 'register_ip' => "'" . $_SERVER['REMOTE_ADDR'] . "'",
						 'register_date' => "'" . date("Y-m-d H:i:s") . "'",
						 'pay_status' => "'pending'" );
						 
			if($_POST['password'] != $_POST['password2'])	throw new Exception("The passwords you entered did not match.");
			
			foreach($arr as $key => $val) {
				if(isset($_POST[$key])) {
					$arr[$key] = mysql_real_escape_string($_POST[$key]);
					if($key == "password")		$arr[$key] = "md5('" . $_POST[$key] . "')";
					else if($key != "mobile")	$arr[$key] = "'" . $arr[$key] . "'";
				}
			}
/*
			$to = $arr['email'];
			$subject = "Srijan 2013 | Registration Successful";
			$password = substr(md5($arr['email']+rand()), 0, 7);
			$arr['password'] = md5($password);
			$message = "<html>
			<body>
			<p>
			Dear $arr[name],
			<br/>
			Your registration for <a href=\"http://www.srijanism.org/\" target=\"_new\">Srijan 2013</a> was successful.<br />
			Your password is <code>$password</code>
			</p>
			<p>
			<a href=\"http://www.srijanism.org/register\">Click here</a> to login.
			</p>
			</body>
			</html>";
			$headers   = array();
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-type:text/html;charset=iso-8859-1";
			$headers[] = "From: Sender Name <noreply@srijanism.org>";
			$headers[] = "Subject: {$subject}";
			$headers[] = "X-Mailer: PHP/".phpversion();

			if(!mail($to, $subject, $message, implode("\r\n", $headers))) {
				echo "ERROR";
				exit;
			}
*/
			$user->addUser($arr);
			header("Location: index.php");
			exit;
		}
		catch(Exception $e) {
			$error = $e->getMessage();
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Srijan is the annual socio-cultural fest of Indian School of Mines, Dhanbad. The festival is not only a panorama of events to test the cultural and artistic skills of the youth from all over the country, but it also endeavours to bring together eminent artists as well as celebrities across the globe, all in an attempt to bestow a memorable and spellbinding experience.">
<meta name="keywords" content="srijan, srijan 13, fest, ism fest, cult fest, sr13an, srijan 2013, cultural fest">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/png" href="../favico.png">
<link rel="image_src" href="../favico.png" />
<title>Srijan 13 | Register</title>

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
<h2>Register for Srijan 2013</h2>


<form id="regiser-form" name="register-form" method="post" action="register.php">
<div id="error">
<strong><?php echo $error; ?></strong>
</div>
	<table cellspacing="10">
    	<tr>
        	<td align="right">Name</td>
            <td><input type="text" name="name" id="name" value="<?php echo ($error == "")? "": $_POST['name']; ?>" /></td>
        </tr>

    	<tr>
        	<td align="right">College/Institution</td>
            <td><input type="text" name="institution" id="institution" value="<?php echo ($error == "")? "": $_POST['institution']; ?>"  /></td>
        </tr>
        
    	<tr>
        	<td align="right">Mobile</td>
            <td><input type="text" name="mobile" id="mobile" value="<?php echo ($error == "")? "": $_POST['mobile']; ?>" /></td>
        </tr>
                
    	<tr>
        	<td align="right">Email</td>
            <td><input type="text" name="email" id="email" value="<?php echo ($error == "")? "": $_POST['email']; ?>"  /></td>
        </tr>
        
    	<tr>
        	<td align="right">Password</td>
            <td><input type="password" name="password" id="password" /></td>
        </tr>
        
    	<tr>
        	<td align="right">Retype Password</td>
            <td><input type="password" name="password2" id="password2" /></td>
        </tr>
        <tr>
        	<td></td>
        	<td><input type="submit" value="Register" name="register-submit" /></td>
        </tr>
        
    </table>
</form>
</center>

</body>
</html>