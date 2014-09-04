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

	try {
		if(isset($_POST['acceptReceipt'])) {
			if(trim($_POST['transactionId']) == '')	throw new Exception("Invalid TiD");

			$data = $db->queryRow("select * from user where pay_receipt_id = '".$_POST['transactionId']."'");
			if($data)	throw new Exception("TiD Exists", $data['id']);

			$admin->confirmPayment($_POST['userId'], $user, Session::get('id'), $_POST['transactionId'], $user->getCurrentTimestamp());
		}
		
		if(isset($_POST['declineReceipt'])) {
			$admin->declinePayment($_POST['userId'], $user, Session::get('id'), $user->getCurrentTimestamp());
		}
	}
	catch(Exception $e) {
		echo $e . "<br>";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN</title>
</head>

<body>

<h1>ADMIN SECTION</h1>
<h2>Authorised Personnel only</h2>
Welcome, 
<?php
	echo Session::get('name') . "!<br>";
	echo Session::get('email') . "<br>";
	echo Session::get('mobile') . "<br>";
?>

<a href="logout.php">Logout</a>
<br />
<br />
<br />

<table border="1" cellpadding="5" cellspacing="5">
<?php

	$pending = $user->getUsersByStatus('processing');

	
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		echo "<tr>";
			echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
			echo "<td>".$curr['name'].
					"<br>".$curr['mobile']."<br>".
					"<br>".$curr['email']."<br>".
					"</td>";
			?>
            
            <td>
            	<a href="receipts/<?php echo $curr['pay_receipt_name']; ?>" target="_new"><img src="receipts/<?php echo $curr['pay_receipt_name']; ?>" width="300" /></a>
            </td>
            <td>
            <form id="receipt-form" method="post" action="#">
            	<input type="hidden" value="<?php echo $curr['id']; ?>" name="userId" />
            	<input type="text" value="" name="transactionId" /><br />
                <input type="submit" value="Accept" name="acceptReceipt" />
                <input type="submit" value="Decline" name="declineReceipt" />
            </form>
            </td>
            <?php
		echo "</tr>";
	}

?>
</table>
</body>

</html>