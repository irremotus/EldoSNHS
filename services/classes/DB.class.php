<?php
	
	class DB {
		public static $db = NULL;
		
		public static function connect() {
			include($_SERVER['DOCUMENT_ROOT'].'/db_cred.php');
			
			if(self::$db == NULL) {				
				self::$db = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8", $username, $password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			}
			return self::$db;
		}
		
		public static function close() {
			self::$db = NULL;
		}
	}

?>