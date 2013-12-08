<?php
	
	class events_service {
		
		private static $publicActions = array("getDataFormat","view","add","edit","delete");
		
		public function serve() {
			if(isset($_GET['action'])) {
				$action = $_GET['action'];
				if(in_array($action,self::$publicActions)) {
					return $this->$action();
				}
				return(array('status'=>false,'data'=>'No action was specified or the specified action does not exist.'));
			}
		}
		
		public function getDataFormat() {
			$events = new Event();
			return array('status'=>true, 'data'=>$events->getDataFormat());
		}
		
		public function view() {
			if(! Permissions::hasPerm(Permissions::STUDENT)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$events = new Event();
			$strict = 1;
			if(isset($_REQUEST['strict'])) {
				$strict = $_REQUEST['strict'];
			}
			$findby = 'id';
			if(isset($_REQUEST['findby'])) {
				$findby = $_REQUEST['findby'];
			}
			$findval = '';
			if(isset($_REQUEST[$findby])) {
				$findval = $_REQUEST[$findby];
			}
			
			$events->find($findby,$findval,$strict);
			$data = array();
			foreach($events->results as $e) {
				$data[] = $e->toArray();
			}
			return array('status'=>true,'data'=>$data);
		}
		
		public function add() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$event = new Event();
			foreach($event->key_names as $k) {
				if(isset($event->keys[$k]['required']) && $event->keys[$k]['required'] == true) {
					if(! isset($_POST[$k])) {
						return array('status'=>false, 'data'=>"All required keys must be assigned.");
					}
				}
				
				if(isset($_POST[$k])) {
					$event->$k = $_POST[$k];
				}
			}
			if($event->save()) {
				$event->find('id',$event->id,true);
				$event = $event->results[0];
				$data = array();
				$data[] = $event->toArray();
				return array('status'=>true, 'data'=>$data);
			} else {
				return array('status'=>false, 'data'=>$event->get_error_message());
			}
		}
		
		public function edit() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$events = new Event();
			$events->find("id", $_POST['id'], true);
			$event = $events->results[0];
			foreach($event->writable_keys as $k) {
				if(isset($_POST[$k])) {
					$event->$k = $_POST[$k];
				}
			}
			if($event->save()) {
				$event->find('id',$event->id,true);
				if($event->numresults > 0) {
					$event = $event->results[0];
				}
				$data = array();
				$data[] = $event->toArray();
				return array('status'=>true, 'data'=>$data);
			} else {
				return array('status'=>false, 'data'=>$event->get_error_message());
			}
		}
		
		public function delete() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
				
			$events = new Event();
			$events->find("id", $_POST['id'], true);
			$events->results[0]->delete();
			return array('status'=>true,'data'=>NULL);
		}
	}
?>