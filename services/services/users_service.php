<?php
	
	class users_service {
		
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
			$users = new User();
			return array('status'=>true, 'data'=>$users->getDataFormat());
		}
		
		public function view() {
			if(! Permissions::hasPerm(Permissions::STUDENT)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$users = new User();
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
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				$findby = 'id';
				$findval = Control::getCurrentUser();
				$strict = true;
			}
			$users->find($findby,$findval,$strict);
			$data = array();
			foreach($users->results as $u) {
				$data[] = $u->toArray();
			}
			return array('status'=>true,'data'=>$data);
		}
		
		public function add() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$user = new User();
			foreach($user->key_names as $k) {
				if(isset($user->keys[$k]['required']) && $user->keys[$k]['required'] == true) {
					if(! isset($_POST[$k])) {
						return array('status'=>false, 'data'=>"All required keys must be assigned.");
					}
				}
				
				if(isset($_POST[$k])) {
					$user->$k = $_POST[$k];
				}
			}
			if(isset($user->perms)) {
				if($user->perms>2) {
					if(! Permissions::hasPerm(Permissions::ADMIN)) {
						return array('status'=>false,'data'=>Permissions::noPermissionError);
					}
				}
			}
			$user->username = substr($user->fname,0,1).substr($user->lname,0,6);
			if($user->save()) {
				$user->find('id',$user->id,true);
				$user = $user->results[0];
				$data = array();
				$data[] = $user->toArray();
				return array('status'=>true, 'data'=>$data);
			} else {
				return array('status'=>false, 'data'=>$user->get_my_error_message_code());
			}
		}
		
		public function edit() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$users = new User();
			$users->find("id", $_POST['id'], true);
			$user = $users->results[0];
			if($user->perms>2) {
				if(! Permissions::hasPerm(Permissions::ADMIN)) {
					return array('status'=>false,'data'=>Permissions::noPermissionError);
				}
			}
			foreach($user->writable_keys as $k) {
				if(isset($_POST[$k])) {
					$user->$k = $_POST[$k];
				}
			}
			if($user->save()) {
				$user->find('id',$user->id,true);
				if($user->numresults > 0) {
					$user = $user->results[0];
				}
				$data = array();
				$data[] = $user->toArray();
				return array('status'=>true, 'data'=>$data);
			} else {
				return array('status'=>false, 'data'=>$user->get_error_message());
			}
		}
		
		public function delete() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$users = new User();
			$users->find("id", $_POST['id'], true);
			$user = $users->results[0];
			if($user->perms>2) {
				if(! Permissions::hasPerm(Permissions::ADMIN)) {
					return array('status'=>false,'data'=>Permissions::noPermissionError);
				}
			}
			$user->delete();
			return array('status'=>true,'data'=>NULL);
		}
	}
?>