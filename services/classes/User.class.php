<?php

	class User extends DBObject {
		
		public $tablename = "users";
		public $sortby = "lname";
		
		public $keys = array(
			'id' => array("key" => "id", "name" => "ID", "type" => "num", "ro" => true),
			'username' => array("key" => "username", "name" => "Username", "type" => "text"),
			'fname' => array("key" => "fname", "name" => "First Name", "type" => "text", "required" => true),
			'lname' => array("key" => "lname", "name" => "Last Name", "type" => "text", "required" => true),
			'email' => array("key" => "email", "name" => "Email", "type" => "text"),
			'pass' => array("key" => "pass", "name" => "Password", "type" => "num"),
			'student' => array("key" => "student", "name" => "Student", "type" => "text", "def" => 1),
			'grade' => array("key" => "grade", "name" => "Grade", "type" => "num"),
			'perms' => array("key" => "perms", "name" => "Permissions", "type" => "num", "def" => 1)
		);
		
		public function set_pass($val) {
			return md5($val);
		}
	}

?>