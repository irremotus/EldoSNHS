<?php
	
	class pagecontent_service {
		
		private static $publicActions = array("getDataFormat","edit");
		
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
		
		public function edit() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$content = new PageContent();
			$content->find("name", $_POST['name'], true);
			$content = $content->results[0];
			if(isset($_POST['title'])) {
				$content->title = $_POST['title'];
			}
			if(isset($_POST['data'])) {
				$content->data = $_POST['data'];
				$content->data = str_replace("\r\n","<br>",$content->data);
			}
			if($content->save()) {
				return array('status'=>true, 'data'=>NULL);
			} else {
				return array('status'=>false, 'data'=>$user->get_error_message());
			}
		}
		
	}
?>