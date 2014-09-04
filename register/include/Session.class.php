<?php
	session_start();

	class Session {
		static function init() {
			$_SESSION['logged_in'] = false;
			$_SESSION['admin_logged_in'] = false;

			$_SESSION['DBhostname'] = "localhost";
			$_SESSION['DBusername'] = "mohit";
			$_SESSION['DBpassword'] = "Nope";
			$_SESSION['DBdatabase'] = "registrations_srijan13";

		}
				
		static function getCurrentUserId() {
			if(Session::_isLoggedIn())	return $_SESSION['id'];
			return false;			
		}

		static function getCurrentAdminId() {
			if(Session::_isAdminLoggedIn())	return $_SESSION['id'];
			return false;			
		}
		
		static function set($key, $val) {
			$_SESSION[$key] = $val;
		}
		
		static function get($key) {
			if(isset($_SESSION[$key]))	return $_SESSION[$key];
			return false;
		}
		
		static function logout() {
			session_destroy();
		}
		
		static function _isLoggedIn() {
			if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
				Session::init();
				return false;
			}
			
			return true;
		}

		static function _isAdminLoggedIn() {
			if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] == false) {
				Session::init();
				return false;
			}
			
			return true;
		}
		
		static function loadData($data) {
			foreach($data as $key => $val)	Session::set($key, $val);
		}
	}
?>