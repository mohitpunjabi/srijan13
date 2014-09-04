<?php
	include_once "../include/Session.class.php";

	
	Session::logout();
	header("Location: index.php");
	exit;
?>