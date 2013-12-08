<?php
	class Control {
		public static function getLoginStatus() {
			if(isset($_SESSION['loggedIn'])) {
				$loginStatus = $_SESSION['loggedIn'];
				return $loginStatus;
			}
			return false;
		}
		
		public static function getCurrentUser() {
			if(isset($_SESSION['userID'])) {
				$userID = $_SESSION['userID'];
				return $userID;
			}
			return false;
		}
		
		public static function login($username,$pass) {
			$u = new User();
			$u->find('username',$username,true);
			if($u->numresults < 1) return false;
			$user = $u->results[0];
			if(self::validateUserPass($user->id,$pass)) {
				$_SESSION['loggedIn'] = true;
				$_SESSION['userID'] = $user->id;
				return true;
			};
			return false;
		}
		
		public static function logout() {
			$_SESSION['loggedIn'] = false;
			unset($_SESSION['userID']);
			session_destroy();
			return true;
		}
		
		public static function validateUserPass($userid, $pass) {
			$u = new User();
			$u->find('id',$userid,true);
			if($u->numresults < 1) return false;
			$user = $u->results[0];
			if($user->pass == $pass) return true;
			return false;
		}
	}
?>