<?php

	class PageContent extends DBObject {
		
		public $tablename = "content";
		public $sortby = "id";
		
		public $keys = array(
			'id' => array("key" => "id", "name" => "ID", "type" => "num", "ro" => true),
			'name' => array("key" => "name", "name" => "Name", "type" => "text"),
			'title' => array("key" => "title", "name" => "Title", "type" => "text"),
			'data' => array("key" => "data", "name" => "Data", "type" => "text")
		);
		
		public function set_pass($val) {
			return md5($val);
		}
	}

?>