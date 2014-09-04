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

<h2><a href="onspot.php">CLICK HERE</a> TO ACCESS ON SPOT REGISTRATIONS.</h2>
<h2><a href="allregistrations.php">CLICK HERE</a> VIEW ALL PAID REGISTRATIONS.</h2>

<h2>Total registrations:
<?php
	echo $db->queryData("select count(*) as count from user", "count");
?></h2>

<h2>Total registrations from ISM(approx.):
<?php
	echo $db->queryData("select count(*) as count from user where institution like 'ISM' or institution like '%indian%school%of%mines%' or institution like 'ism%dhanbad' or institution like '%i.s.m.%' or institution like '%i.s.m%' or institution like 'ISM%dhanbad%' or institution like 'Indian School of Mines, Dhanbad'", "count");
?></h2>

<table border="1" cellpadding="10" cellspacing="0">
<?php

	$pending = $user->getUsersByStatus('processing');
	
	echo "<h2>Pending Receipts: " . mysql_num_rows($pending) . "</h2>";	
	
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		echo "<tr>";
			echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
			echo "<td><strong>".$curr['name']."</strong>".
					"<br>".$curr['institution'].
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
<br />
<br />
<hr />
<br />

<font color="#030">

<?php
	$pending = $user->getUsersByStatus('confirm');
	
	echo "<h2>Accepted Receipts: " . mysql_num_rows($pending) . "</h2>";	
?>
<table border="1" cellpadding="10" cellspacing="0">
<?php	
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		if($curr['pay_receipt_id'] != "") {
			echo "<tr>";
				echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
				echo "<td><strong>".$curr['name']."</strong>".
						"<br>".$curr['institution'].
						"<br>".$curr['mobile']."<br>".
						"<br>".$curr['email']."<br>".
						"</td>";
			echo "<td>".$curr['pay_receipt_id']."</td>";
			echo "<td>Paid on<br>" . date("F j, Y g:i a",strtotime($curr['pay_date'])) . "</td>";		
			echo "<td>Confirmed on<br>" . date("F j, Y g:i a",strtotime($curr['confirm_date'])) . "</td>";		
?>		
            <td>
            	<a href="receipts/<?php echo $curr['pay_receipt_name']; ?>" target="_new">View receipt</a>
            </td>
<?php
			echo "</tr>";
		}
	}
?>
</table>
</font>

<br />
<br />
<hr />
<br />

<font color="#300">
<?php
	$pending = $db->query("select * from user where pay_status='pending' and pay_amount=0 and pay_receipt_name != ''");
	
	echo "<h2>Declined Receipts: " . mysql_num_rows($pending) . "</h2>";	
?>
<table border="1" cellpadding="10" cellspacing="0">
<?php		
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		echo "<tr>";
			echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
			echo "<td><strong>".$curr['name']."</strong>".
					"<br>".$curr['institution'].
					"<br>".$curr['mobile']."<br>".
					"<br>".$curr['email']."<br>".
					"</td>";
		
		echo "<td>Paid on<br>" . date("F j, Y g:i a",strtotime($curr['pay_date'])) . "</td>";	
		echo "<td>Declined on<br>" . date("F j, Y g:i a",strtotime($curr['confirm_date'])) . "</td>";		
?>
            <td>
            	<a href="receipts/<?php echo $curr['pay_receipt_name']; ?>" target="_new">View receipt</a>
            </td>
<?php
		echo "</tr>";
	}

?>
</table>
</font>

<br />
<br />
<hr />
<br />

<font color="#222">

<?php
	$pending = $db->query("select* from user where !(institution like 'ISM' or institution like '%indian%school%of%mines%' or institution like 'ism%dhanbad' or institution like '%i.s.m.%' or institution like '%i.s.m%' or institution like 'ISM%dhanbad%' or institution like 'Indian School of Mines, Dhanbad')");
	
	echo "<h2>Total registrations(From colleges other than ISM): " . mysql_num_rows($pending) . "</h2>";	
?>
<table border="1" cellpadding="10" cellspacing="0">
<?php	
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		echo "<tr>";
			echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
			echo "<td><strong>".$curr['name']."</strong>".
					"<br>".$curr['institution'].
					"<br>".$curr['mobile']."<br>".
					"<br>".$curr['email']."<br>".
					"</td>";

		echo "<td>Pay Status<br>" . $curr['pay_status'] . "</td>";				
		if($curr['pay_date'] != "0000-00-00 00:00:00") echo "<td>Paid on<br>" . date("F j, Y g:i a",strtotime($curr['pay_date'])) . "</td>";		
		if($curr['confirm_date'] != "0000-00-00 00:00:00") echo "<td>Confirmed on<br>" . date("F j, Y g:i a",strtotime($curr['confirm_date'])) . "</td>";		

		if($curr['pay_date'] != "0000-00-00 00:00:00") {
?>		
            <td>
            	<a href="receipts/<?php echo $curr['pay_receipt_name']; ?>" target="_new">View receipt</a>
            </td>
<?php
		}
		echo "</tr>";
	}
?>
</table>
</font>



</body>

</html>