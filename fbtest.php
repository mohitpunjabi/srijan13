<?php
	define('DB_HOST', '118.67.250.96');
    define('DB_USER', 'ismdhanb_fb');
    define('DB_PASSWORD', 'nahd!@ism');
    define('DB_DATABASE', 'ismdhanb_fbtest');
	

	try {	
		mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		mysql_select_db(DB_DATABASE);
	}
	catch(Exception $e) {
		echo $e;
	}
?>