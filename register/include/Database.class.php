<?php
	include_once "Session.class.php";
	
	class Database {
		private $connection;

		function __construct() {
			$this->connection = mysql_pconnect(Session::get('DBhostname'), Session::get('DBusername'), Session::get('DBpassword'));
			mysql_select_db(Session::get('DBdatabase'));
			
	//		echo mysql_error();
		}

		function __destruct() {
//			mysql_close($this->connection);
		}
			
		function queryData($query, $item) {
			$arr = mysql_fetch_array(mysql_query($query), MYSQL_ASSOC);
			return $arr[$item];
		}

		function queryRow($query) {
			return mysql_fetch_array(mysql_query($query), MYSQL_ASSOC);
		}

		function query($query) {
			return mysql_query($query);
		}
		
		function insert($table, $data) {
			$query = "insert into $table (" . implode(", ", array_keys($data)) . ") ";
			$values = "values (" . implode(", ", $data) . ")";
			$query .= $values;
			
//			echo "Inserting: $query<br>";
//			mysql_query($query);
			if(!mysql_query($query)) {
				echo mysql_error() . "<br>";
				throw new Exception("<h2>AN ERROR OCCURED</h2>");
//				exit;
			}
			else	echo "Inserted 1 row<br>";
		}
	}
?>