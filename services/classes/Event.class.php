<?php

	class Event extends DBObject {
		
		public $tablename = "events";
		public $sortby = "date";
		
		public $keys = array(
			'id' => array("key" => "id", "name" => "ID", "type" => "num", "ro" => true),
			'name' => array("key" => "name", "name" => "Name", "type" => "text", "required" => true),
			'date' => array("key" => "date", "name" => "Date", "type" => "date", "required" => true),
			'details' => array("key" => "details", "name" => "Details", "type" => "text"),
			'pointtype' => array("key" => "pointtype", "name" => "Point Type", "type" => "text", "def" => "points"),
			'pointvalue' => array("key" => "pointvalue", "name" => "Point Value", "type" => "num", "required" => true),
			'defval' => array("key" => "defval", "name" => "Default", "type" => "num")
		);
	}

?>