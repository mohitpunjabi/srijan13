<?php
	include_once "Database.class.php";
	include_once "Session.class.php";
	include_once "Log.class.php";

	class Admin {
		
		function __construct() {
			$this->db = new Database();
		}
		
		function addAdmin($args) {
			$this->_throwExceptionIfInvalidAdmin($args);
			$args['id'] = $this->_getNextAdminId();
			$this->db->insert("admin", $args);
		}
		
		function getAdminData($username, $password) {
			$count = $this->db->queryData("select count(*) as count from admin where username='$username' and password=md5('$password')", "count");
			if($count != 1)	return false;
			
			return $this->db->queryRow("select * from admin where username='$username' and password=md5('$password')");
		}

		function getDataFromId($id) {
			return $this->db->queryRow("select * from admin where id=$id");
		}

		function logAdminIn($username, $password) {
			$data = $this->getAdminData($username, $password);
			if($data === false)	return false;
			
			Session::loadData($data);
			Session::set('admin_logged_in', true);

			$currTime = $this->getCurrentTimestamp();
			$this->update($data['id'], "last_login = '$currTime'");

			return true;
		}
		
		function confirmPayment($uid, $user, $aid, $receiptId, $time) {
			$user->update($uid, "pay_status='confirm', pay_amount=500, pay_receipt_id='$receiptId', confirmed_by=$aid, confirm_date='$time'");
			Log::confirmPay($this->db, $time, $uid, $aid, $_SERVER['REMOTE_ADDR']);
		}
		
		function declinePayment($uid, $user, $aid, $time) {
			$user->update($uid, "pay_status='pending', pay_amount=0, pay_receipt_id='', confirmed_by=$aid, confirm_date='$time'");
			Log::declinePay($this->db, $time, $uid, $aid, $_SERVER['REMOTE_ADDR']);
		}
		
		function get($id, $data) {
			return $this->db->queryData("select $data from admin where id = $id", $data);
		}
		
		function update($id, $query) {
			$this->db->query("update admin set " . $query . " where id = $id");
		}
				
		function getCurrentTimestamp() {
			return date("Y-m-d H:i:s");  
		}
		
	
		private function _getNextAdminId() {
			return intval($this->db->queryData("select (max(id)+1) as nextId from admin", "nextId"));
		}
		
		
		private function _isMobileNumberValid($mobile) {
			return preg_match("/^[0-9]{10}$/", $mobile);
		}
		
		private function _isEmailValid($email) {
			return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
		}
		
		private function _isEmailTaken($email) {
			echo "Checking if email is taken... <br>";
			$count = intval($this->db->queryData("select count(email) as count from user where email = '$email'", "count"));
			echo "Number of emails found: $count <br>";
			return ($count > 0);
		}
		
		private function _isNameValid($name) {
			return preg_match("/^\'[a-z\x20]+\'$/i", trim($name));
		}

		private function _isInstitutionValid($institution) {
			return (strlen(trim($institution)) > 2);
		}

		private function _isPasswordValid($password) {
			return ($password != "md5('')");
		}
				
		private function _throwExceptionIfInvalidAdmin($args) {
/*
			$email = substr($args['email'], 1, strlen($args['email']) - 2);
			
			if(!$this->_isNameValid($args['name']))					throw new Exception('Invalid Name');
			if(!$this->_isMobileNumberValid($args['mobile']))		throw new Exception('Invalid Mobile');
			if(!$this->_isInstitutionValid($args['institution']))	throw new Exception('Invalid Institution');
			if(!$this->_isEmailValid($email))						throw new Exception('Invalid Email');
			if($this->_isEmailTaken($email))						throw new Exception('Email Taken');
			if(!$this->_isPasswordValid($args['password']))			throw new Exception('Invalid Password');
*/
		}		

		
		private $db;
	}
?>