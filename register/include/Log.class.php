<?php
	include_once "Database.class.php";
	
	class Log {		
		static function logUserIn($db, $time, $uid, $uip) { 
			$db->insert("log", array("type" => "'user_login'",
						 			 "event_time" => "'$time'",
									 "user" => "$uid",
									 "user_ip" => "'$uip'"));
		}

		static function payUserFee($db, $time, $uid, $uip) { 
			$db->insert("log", array("type" => "'user_pay'",
						 			 "event_time" => "'$time'",
									 "user" => "$uid",
									 "user_ip" => "'$uip'"));
		}
		
		static function addUser($db, $time, $uid, $uip) { 
			$db->insert("log", array("type" => "'user_register'",
						 			 "event_time" => "$time",
									 "user" => "$uid",
									 "user_ip" => "'$uip'"));
		}
		
		static function logAdminIn($db, $time, $uid, $uip) { 
			$db->insert("log", array("type" => "'admin_login'",
						 			 "event_time" => "$time",
									 "admin" => "$uid",
									 "admin_ip" => "'$uip'"));
		}
		
		static function confirmPay($db, $time, $uid, $aid, $aip) { 
			$db->insert("log", array("type" => "'admin_confirm_pay'",
						 			 "event_time" => "'$time'",
									 "user" => "$uid",
									 "admin" => "$aid",
									 "admin_ip" => "'$aip'"));
		}

		static function declinePay($db, $time, $uid, $aid, $aip) { 
			$db->insert("log", array("type" => "'admin_decline_pay'",
						 			 "event_time" => "'$time'",
									 "user" => "$uid",
									 "admin" => "$aid",
									 "admin_ip" => "'$aip'"));
		}
	}
?>