<?php
	
	class setup_service {
		
		private static $publicActions = array("importusers");
		
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
		
		public function importusers() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			if($_FILES["userfile"]["error"] == 0) {
				$filename = $_FILES["userfile"]["tmp_name"];
				$f = fopen($filename,"r");
				$succeeded = true;
				while (($data = fgetcsv($f, 1000, ",")) !== FALSE && $data[0] != "") {
					$user = new User();
					$user->lname = $data[0];
					$user->fname = $data[1];
					if(isset($data[2])) {
						$user->email = $data[2];
					}
					$user->student = 1;
					$user->perms = 1;
					$user->pass = rand(1000,9999);
					
					$user->username = substr($user->fname,0,1).substr($user->lname,0,6);
					if(! $user->save()) {
						$succeeded = false;
					}
				}
			}
			
			//return array('status'=>$succeeded, 'data'=>NULL);
			if($succeeded) {
				echo '<script>alert("Successfully imported users."); location="/users";</script>';
			} else {
				echo '<script>alert("Failed to import users."); location="/users";</script>';
			}
		}
		
		
	}
?>