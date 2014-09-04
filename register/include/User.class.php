<?php
	include_once "Database.class.php";
	include_once "Session.class.php";
	include_once "Log.class.php";

	class User {
		
		function __construct() {
			$this->db = new Database();
		}
		
		function addUser($args, $validate = true) {
			if($validate == true) {	$this->_throwExceptionIfInvalidUser($args); }
			$args['id'] = $this->_getNextUserId();
			$this->db->insert("user", $args);
			Log::addUser($this->db, $args['register_date'], $args['id'], $_SERVER['REMOTE_ADDR']);
		}
		
		function getUserData($email, $password) {
			$count = $this->db->queryData("select count(*) as count from user where email='$email' and password=md5('$password')", "count");
			if($count != 1)	return false;
			
			return $this->db->queryRow("select * from user where email='$email' and password=md5('$password')");
		}

		function getDataFromId($id) {
			return $this->db->queryRow("select * from user where id=$id");
		}

		function logUserIn($email, $password) {
			$data = $this->getUserData($email, $password);
			if($data === false)	return false;
			
			Session::loadData($data);
			Session::set('logged_in', true);

			$currTime = $this->getCurrentTimestamp();
			$this->update($data['id'], "last_login = '$currTime'");

			Log::logUserIn($this->db, $currTime, $data['id'], $_SERVER['REMOTE_ADDR']);
			return true;
		}
		
		function get($id, $data) {
			return $this->db->queryData("select $data from user where id = $id", $data);
		}
		
		function update($id, $query) {
			$this->db->query("update user set " . $query . " where id = $id");
		}
		
		function makeSrijanId($id) {
			$pay_status = $this->get($id, 'pay_status');
			$pay_status = ($pay_status == "confirm")? "C": "P";
			$user_type = $this->get($id, 'user_type');
			if($user_type == 'mun')		$user_type = "MUN";
			if($user_type == 'gaming')	$user_type = "GAM";
			if($user_type == 'normal')	$user_type = "";
			return sprintf("SR13%s%04d%s", $pay_status, $id, $user_type);
		}
		
		function payFee($id, $receipt) {
			$name = $receipt["name"];
			$extension = strtolower(end(explode(".", $name)));
			$type = $receipt["type"];
			$error = $receipt["error"];
			$size = $receipt["size"];
			$tmp_name = $receipt["tmp_name"];
			$upload_name = $this->makeSrijanId($id) . ".$extension";
			$currTime = $this->getCurrentTimestamp();
			
			if($size > 1050000)	throw new Exception("File too large");
			if($error > 0)		throw new Exception($error);
	
			if($type == "image/jpeg" ||
			   $type == "image/png" ||
			   $type == "application/pdf") {
					move_uploaded_file($tmp_name, "21232f297a57a5a743894a0e4a801fc3/receipts/" . $upload_name);
					$this->update($id, "pay_type='online', pay_status='processing', pay_amount=500, pay_date='" .$currTime. "', pay_receipt_format='$type', pay_receipt_name = '$upload_name'");
					Log::payUserFee($this->db, $currTime, $id, $_SERVER['REMOTE_ADDR']);
			}
			else	throw new Exception("Unsupported format");
		}
				
		function logUserOut($id) {
		}
	
	
		function getUsersByStatus($status) {
			return $this->db->query("select * from user where pay_status='$status' and pay_type='online'");
		}

		function getNextUserId() {
			return intval($this->db->queryData("select (max(id)+1) as nextId from user", "nextId"));
		}
	
		private function _getNextUserId() {
			return intval($this->db->queryData("select (max(id)+1) as nextId from user", "nextId"));
		}
		
		
		private function _isMobileNumberValid($mobile) {
			return preg_match("/^[0-9]{10}$/", $mobile);
		}
		
		private function _isEmailValid($email) {
			return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email);
		}
		
		private function _isEmailTaken($email) {
//			echo "Checking if email is taken... <br>";
			$count = intval($this->db->queryData("select count(email) as count from user where email = '$email'", "count"));
//			echo "Number of emails found: $count <br>";
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
				
		private function _throwExceptionIfInvalidUser($args) {
			$email = substr($args['email'], 1, strlen($args['email']) - 2);
			
			if(!$this->_isNameValid($args['name']))					throw new Exception('Your name contains invalid characters.');
			if(!$this->_isMobileNumberValid($args['mobile']))		throw new Exception('Your mobile number must be of exactly 10 digits.');
			if(!$this->_isInstitutionValid($args['institution']))	throw new Exception('Please enter the full name of your institution.');
			if(!$this->_isEmailValid($email))						throw new Exception('Please enter a valid email address. It will be used to contact you.');
			if($this->_isEmailTaken($email))						throw new Exception('This email address has already registered.');
			if(!$this->_isPasswordValid($args['password']))			throw new Exception('Please enter your password.');
		}		
	
		function getCurrentTimestamp() {
			return date("Y-m-d H:i:s");  
		}
		
		private $db;
	}
?>