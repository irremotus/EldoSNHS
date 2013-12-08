<?php
	class Permissions {
		const STUDENT = 1;
		const OFFICER = 2;
		const ADMIN   = 3;
		
		const noPermissionError = "You do not have permission to do that.";
		
		public static function hasPerm($perm,$userID=NULL) {
			if($userID==NULL) $userID = Control::getCurrentUser();
			if($userID==false) return false;
			$u = new User();
			$u->find('id',$userID,true);
			if($u->numresults > 0) {
				$user = $u->results[0];
				if($user->perms >= $perm) return true;
			}
			return false;
		}
	}
?>