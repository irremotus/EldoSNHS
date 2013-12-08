<?php
	
	class checkin_service {
		
		private static $publicActions = array("getDataFormat","checkin","setup");
		
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
		
		public function setup() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			$retval = true;
			if(! isset($_POST['event'])) $retval = false;
			$_SESSION['checkinevent'] = $_POST['event'];
			return array('status'=>$retval,'data'=>NULL);
		}
		
		public function checkin() {
			if(! Permissions::hasPerm(Permissions::OFFICER)) {
				return array('status'=>false,'data'=>Permissions::noPermissionError);
			}
			$retval = false;
			$data = "";
			if(isset($_POST['username']) && isset($_POST['pass'])) {
				$users = new User();
				$users->find("username", $_POST['username'], true);
				if($users->numresults>0) {
					$user = $users->results[0];
					$valid = Control::validateUserPass($user->id,$_POST['pass']);
					if($valid) {
						$eventid = $_SESSION['checkinevent'];
						$event = new Event();
						$event->find('id',$eventid,true);
						if($event->numresults>0) {
							$event = $event->results[0];
							$pointval = $event->pointvalue;
							$points = new Points();
							$points->customFind("SELECT * FROM points WHERE uid=".$user->id." AND eid=".$eventid);
							if($points->numresults>0) {
								$points = $points->results[0];
								$points->points = $pointval;
								$retval = $points->save();
							} else {
								$points->uid = $user->id;
								$points->eid = $eventid;
								$points->points = $pointval;
								$retval = $points->save();
							}
						}
					} else {
						$data = "Wrong username or password";
					}
				}
			}
			return array('status'=>$retval,'data'=>$data);
		}
	}
?>