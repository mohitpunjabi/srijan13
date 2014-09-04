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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN</title>
</head>

<body>

<h1>ALL PAID REGISTRATIONS</h1>
<h2>Authorised Personnel only</h2>
<a href="logout.php">Logout</a>
<br />
<br />
<br />

<table border="1" cellpadding="10" cellspacing="0">
<?php

	$pending = $db->query("select * from user where pay_status='confirm'");
	
	echo "<h2>Total Registations: " . mysql_num_rows($pending) . "</h2>";	
	$total = 0;
	$totalOnSpot = 0;
	
	echo "<tr><th>ID</th><th>INFO</th><th>AMOUNT PAID</th><th>RECEIPT</th></tr>";
	for($i = 0; $i < mysql_num_rows($pending); $i++) {
		$curr = mysql_fetch_array($pending, MYSQL_ASSOC);
		if($curr['pay_type'] == 'online')	echo "<tr bgcolor=\"#66FFFF\">";
		else								echo "<tr bgcolor=\"#FFF\">";
			echo "<td>".$user->makeSrijanId($curr['id'])."</td>";
			echo "<td><strong>".$curr['name']."</strong>".
					"<br>".$curr['institution'].
					"<br>".$curr['mobile']."<br>".
					"<br>".$curr['email']."<br>".
					"</td>";
			
			
			echo "<td>".$curr['pay_amount']."</td>";
			$total += $curr['pay_amount'];
			if($curr['pay_type'] == 'online') {
			?>
            <td>
            	<a href="receipts/<?php echo $curr['pay_receipt_name']; ?>" target="_new">View Receipt</a>
            </td>
            <?php
			}
			else
				$totalOnSpot += $curr['pay_amount'];
			
		echo "</tr>";
	}
	
	echo "<h1>TOTAL MONEY COLLECTED: Rs. $total</h1>";
	echo "<h1>TOTAL ON SPOT MONEY COLLECTED: Rs. $totalOnSpot</h1>";

?>
</table>

</body>

</html>