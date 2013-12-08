<?php
	
	class login_service {
		
		private static $publicActions = array("login","logout","status");
		
		public function serve() {
			if(isset($_GET['action'])) {
				$action = $_GET['action'];
				if(in_array($action,self::$publicActions)) {
					return $this->$action();
				}
				return(array('status'=>false,'data'=>'No action was specified or the specified action does not exist.'));
			}
		}
		
		public function login() {
			if(isset($_POST['username']) && isset($_POST['pass']) && $_POST['username'] != "" && $_POST['pass'] != "") {
				$username = $_POST['username'];
				$password = $_POST['pass'];
				$status = Control::login($username,$password);
				if($status == true) {
					return(array('status'=>true,'data'=>NULL));
				}
				return(array('status'=>false,'data'=>'Wrong username or password.'));
			}
			return(array('status'=>false,'data'=>'Username or password was not given.'));
		}
		
		public function logout() {
			$status = Control::logout();
			if($status == true) {
				return(array('status'=>true,'data'=>NULL));
			}
		}
		
		public function status() {
			$loggedIn = Control::getLoginStatus();
			$curuser = Control::getCurrentUser();
			return(array('status'=>$loggedIn,'data'=>array('current_user'=>$curuser)));
		}
	}
?>