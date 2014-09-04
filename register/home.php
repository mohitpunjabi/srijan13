<?php
	include_once "include/Session.class.php";
	include_once "include/User.class.php";
	
	if(Session::getCurrentUserId() === false) {
		header("Location: index.php");
		exit;
	}

	$user = new User();

	$pay_status = $user->get(Session::get('id'), 'pay_status');
	$pay_type = $user->get(Session::get('id'), 'pay_type');

	$error = "";

	Session::loadData($user->getDataFromId(Session::get('id')));
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
<title>Srijan 13 | Registrations</title>
</head>

<style type="text/css">
body {
	background: #FFFFEC;
	font-family: Arial, Helvetica, sans-serif;
}

form {
	border-radius: 5px;
	border: 1px #8A6952 solid;
	background: #FFFDB5;
	width: 560px;
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

#red {
	color: #B00;
}

#orange {
	color: #F93;
}

#green {
	color: #0B0;
}

#rules {
	width: 600px;
}

#rules ol {
	text-align: justify;
}

#rules ol li {
	padding: 5px;
}
</style>
<body>

<center>
<img src="../img/srijan13-logo-dark.png" width="400" /><br />
<br />
<table cellpadding="5" border="0">
	<tr>
    	<td></td>
        <td align="right"><a href="logout.php">Logout</a></td>
    </tr>
	<tr>
    	<td align="right"><strong>Your Srijan ID</strong></td>
        <td><strong><?php echo $user->makeSrijanId(Session::get('id')); ?></strong></td>
    </tr>
	<tr>
    	<td align="right"><strong>Name</strong></td>
        <td><?php echo Session::get('name'); ?></td>
    </tr>
	<tr>
    	<td align="right"><strong>Institution</strong></td>
        <td><?php echo Session::get('institution'); ?></td>
    </tr>
	<tr>
    	<td align="right"><strong>Mobile</strong></td>
        <td><?php echo Session::get('mobile'); ?></td>
    </tr>
	<tr>
    	<td align="right" width="150"><strong>Status of payment</strong></td>
        <td width="250">
			<?php
            	if(Session::get('pay_status') == "pending") {
					echo "<h3 id=\"red\">Your payment is pending.</h3>";
				}
            	else if(Session::get('pay_status') == "processing") {
					echo "<h3 id=\"orange\">Your payment is being processed.</h3>";
				}
            	else {
					echo "<h3 id=\"green\">Your payment of Rs. " . Session::get("pay_amount") . "/- has been confirmed.</h3>";
				}
			?>
        </td>
    </tr>
</table>
<br />
<br />
<br />

<h2>Online registrations have now been closed.</h2>

<hr width="600" />
        </ol>
    </div>
</center>
</body>

</html>