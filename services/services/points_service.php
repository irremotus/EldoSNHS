<?php
	
	class points_service {
		
		private static $publicActions = array("getDataFormat","view","edit");
		
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
			
		}
		
		public function view() {
			if(! Permissions::hasPerm(Permissions::STUDENT)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$points = new Points();
			$data = array();
			$points->find('id','',false);
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				if($points->numresults>0) {
					$uid = Control::getCurrentUser();
					foreach($points->results as $p) {
						if($p->uid == $uid) {
							$data[] = $p->toArray();
						}
					}
				}
			} else {
				foreach($points->results as $p) {
					$data[] = $p->toArray();
				}
			}
			return array('status'=>true,'data'=>$data);
		}
		
		public function edit() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$points = new Points();
			
			$id = $_POST['id'];
			$uid = $_POST['uid'];
			$eid = $_POST['eid'];
			$ps = $_POST['points'];
			$points->find('id',$id,true);
			if($points->numresults > 0) {
				$points = $points->results[0];
				$points->points = $ps;
				$succeeded = $points->save();
			} else {
				$points = new Points();
				$points->uid = $uid;
				$points->eid = $eid;
				$points->points = $ps;
				$succeeded = $points->save();
			}
			
			if($succeeded) {
				return array('status'=>true, 'data'=>array('elid'=>$_POST['elid'],'id'=>$points->id));
			} else {
				return array('status'=>false, 'data'=>$points->get_error_message());
			}
		}
		
		public function delete() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			
			$users = new User();
			$users->find("id", $_POST['id'], true);
			$users->results[0]->delete();
			return array('status'=>true,'data'=>NULL);
		}
	}
?>