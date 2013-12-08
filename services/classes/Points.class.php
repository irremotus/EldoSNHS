<?php

	class Points extends DBObject {
		
		public $tablename = "points";
		public $sortby = "id";
		
		public $keys = array(
			'id' => array("key" => "id", "name" => "ID", "type" => "num", "ro" => true),
			'uid' => array("key" => "uid", "name" => "User", "type" => "num", "required" => true),
			'eid' => array("key" => "eid", "name" => "Event", "type" => "num", "required" => true),
			'points' => array("key" => "points", "name" => "Points", "type" => "num", "required" => true)
		);
	}

?>