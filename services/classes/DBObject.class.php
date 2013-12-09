<?php

	class DBObject {
		
		public $tablename = "";
		public $new = true;
		public $keys = array();
		public $key_names = array();
		public $writable_keys = array();
		private $vars;
		public $error;
		public $sortby = "";
		
		private $dbo;
		private $db;
		
		public $results = array();
		public $numresults = 0;
		
		public function toArray() {
			$output = array();
			
			foreach($this->key_names as $k) {
				$output[$k] = $this->__get($k);
			}
			
			return $output;
		}
		
		public function __set($var, $val) {
			if(array_key_exists($var, $this->vars)) {
				/*if(method_exists($this, "set_".$var)) {
					$func = "set_".$var;
					$this->vars[$var] = $this->$func($val);
				} else {*/
					$this->vars[$var] = $val;
				//}
			}
		}
		
		private function direct_set($var, $val) {
			$this->vars[$var] = $val;
		}
		
		public function __get($var) {
			if(method_exists($this, "get_".$var)) {
				$func = "get_".$var;
				return $this->$func();
			}
			return (isset($this->vars[$var])) ? stripslashes($this->vars[$var]) : "";
		}
		
		public function get_error() {
			if(isset($this->error))
				return $this->error;
			return false;
		}
		
		public function get_error_message() {
			$e = $this->get_error();
			if($e) {
				$message = $e->getMessage();
				return $message;
			} else {
				return false;
			}
		}
		
		public function get_my_error_message_code() {
			$e = $this->get_error();
			$code = $e->errorInfo[1];
			return MyErrors::$ERRORS[$code];
		}
		
		public function getDataFormat() {
			return $this->keys;
		}
		
		public function save() {
			$this->db = $this->dbo->connect();
			$keys_colons = array();
			foreach($this->key_names as $k) {
				if($k != "id")
					$keys_colons[$k] = ":".$k;
			}
			$keys_string = implode(",", array_keys($keys_colons));
			$colons_string = implode(",", array_values($keys_colons));
			
			$vars = $this->vars;
			unset($vars['id']);
			try {
				if($this->new) {
					$st = $this->db->prepare("INSERT INTO {$this->tablename}({$keys_string}) VALUES({$colons_string})");
				} else {
					$vars["id"] = $this->id;
					foreach($keys_colons as $k => $c) {
						$qstring[] = $k."=".$c;
					}
					$qstring = implode(",", $qstring);
					$st = $this->db->prepare("UPDATE {$this->tablename} SET {$qstring} WHERE id = :id");
				}
				if($st->execute($vars) && ($st->rowCount() > 0))
					$lastInsertId = 0;
					$lastInsertId = $this->db->lastInsertId();
					if($lastInsertId != 0) {
						$this->id = $lastInsertId;
					}
					return true;
					throw new Exception("Could not save data");
			} catch(Exception $e) {
				$this->error = $e;
			}
		}
		
		public function delete() {
			$this->db = $this->dbo->connect();
			try {
				$st = $this->db->prepare("DELETE FROM {$this->tablename} WHERE id = :id");
				if($st->execute(array('id' => $this->vars['id'])) && ($st->rowCount() > 0))
					return true;
					throw new Exception("Could not delete data");
			} catch(Exception $e) {
				$this->error = $e;
			}
		}
		
		public function find($key, $val, $strict=false) {
			try {
				if(in_array($key, $this->key_names)) {
					if(! $strict) {
						$val = "$val%";
					}
					$this->db = $this->dbo->connect();
					$st = $this->db->prepare("SELECT * FROM {$this->tablename} WHERE $key LIKE :val ORDER BY {$this->sortby}");
					$st->execute(array("val" => $val));
					$st->setFetchMode(PDO::FETCH_OBJ);
					if($u = $st->fetchAll()) {
						foreach($u as $i=>$v) {
							$current_class = get_class($this);
							$newobj = new $current_class();
							foreach($this->key_names as $k) {
								$newobj->$k = $v->$k;
							}
							$newobj->new = false;
							$this->results[] = $newobj;
							$this->numresults++;
						}
						$this->new = false;
						return true;
					} else {
						throw new Exception("No records found");
					}
				} else {
					throw new Exception("Search key does not exist");
				}
			} catch(Exception $e) {
				$this->error = $e;
			}
		}
		
		public function customFind($string) {
			try {
				$this->db = $this->dbo->connect();
				$st = $this->db->prepare($string);
				$st->execute();
				$st->setFetchMode(PDO::FETCH_OBJ);
				if($u = $st->fetchAll()) {
					foreach($u as $i=>$v) {
						$current_class = get_class($this);
						$newobj = new $current_class();
						foreach($this->key_names as $k) {
							$newobj->$k = $v->$k;
						}
						$newobj->new = false;
						$this->results[] = $newobj;
						$this->numresults++;
					}
					$this->new = false;
					return true;
				} else {
					throw new Exception("No records found");
				}
			} catch(Exception $e) {
				$this->error = $e;
			}
		}
		
		public function __construct($new = true) {
			$this->dbo = new DB();
			
			$this->new = $new;
			if($this->new) {
				$i = 0;
				$this->key_names = array_keys($this->keys);
				foreach($this->keys as $k => $v) {
					$this->vars[$v['key']] = "";
					if(! (array_key_exists('ro', $v) && ($v['ro'] == true) )) {
						$this->writable_keys[] = $v['key'];
					}
				}
			}
		}
		
		public function __destruct() {
			//$this->dbo->close();
		}
				
	}
	
?>