<?php
	include_once "../include/Session.class.php";
	include_once "../include/Admin.class.php";
	include_once "../include/User.class.php";
	include_once "../include/Database.class.php";
	
	if(Session::getCurrentAdminId() === false) {
		header("Location: index.php");
		exit;
	}

	$admin = new Admin();
	$user = new User();
	$db = new Database();

	$msg = "";

	try {
		if(isset($_POST['newSubmit'])) {
			$arr = array('name' => "'".$_POST['name']."'",
						 'mobile' => $_POST['mobile'],
						 'institution' => "'".$_POST['institution']."'",
						 'email' => "'".$_POST['email']."'",
						 'register_ip' => "'" . $_SERVER['REMOTE_ADDR'] . "'",
						 'register_date' => "'" . date("Y-m-d H:i:s") . "'",
						 'pay_status' => "'confirm'",
						 'user_type' => "'".$_POST['type']."'",
						 'pay_amount' => $_POST['amount'],
						 'pay_type' => "'spot'",
						 'confirmed_by' => Session::get('id'),						 
						  );
			var_dump($arr);

			$user->addUser($arr, false);
			
			$msg = "<h3>User added:</h3>";
			$msg .= "<strong>".$arr['name']."</strong><br />".
					"<strong>".$user->makeSrijanId($_POST['id'])."</strong><br />".
					$arr['mobile']."<br />".
					$arr['institution']."<br />".
					"Rs. ".$arr['pay_amount']."/- paid.";
		}
	}
	catch(Exception $e) {
		echo $e->getMessage() . "<br>";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN</title>
</head>

<body>

<h1>WELCOME TO SRIJAN 13 REGISTRATIONS</h1>
<a href="logout.php">Logout</a>
<hr />

<hr />

<div style="background:#FFFF42">
<?php echo $msg; ?>
</div>

<h2>ADD A NEW PARTICIPANT</h2>
<form name="newParticipant" action="#" method="post">
	<table>
    	<tr>
        	<td>ID</td>
            <td><?php
					$uid = $user->getNextUserId();
					echo "<strong>".sprintf("SR13%s%04d%s", 'P', $uid, "")."</strong>";
					echo "<input type=\"hidden\" value=\"$uid\" name=\"id\" />";
				?>
            </td>
        </tr>
    	<tr>
        	<td>Name</td>
            <td><input type="text" name="name"  /></td>
        </tr>
    	<tr>
        	<td>Institution</td>
            <td><input type="text" name="institution"  /></td>
        </tr>
    	<tr>
        	<td>Mobile</td>
            <td><input type="text" name="mobile"  /></td>
        </tr>
        
    	<tr>
        	<td>Email</td>
            <td><input type="text" name="email"  /></td>
        </tr>
        
    	<tr>
        	<td>Type</td>
            <td><select name="type">
            	<option value="mun">MUN</option>
            	<option value="gaming">GAMING</option>
            	<option value="normal" selected="selected">NORMAL</option>
            </select></td>
        </tr>
    	<tr>
        	<td>Amount Paid</td>
            <td><input type="text" name="amount" /></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" name="newSubmit" value="ADD" /></td>
        </tr>
        
    </table>
</form>

<hr />


</body>
</html>