<?php
	class Services {
		public static $services = array("login","users","events","points","setup","pagecontent","checkin");
		
		public static function getAvailableServices() {
			return self::$services;
		}
		
		public function serve($service) {
			$service_name = $service.'_service';
			if(in_array($service,self::getAvailableServices()) && class_exists($service_name)) {
				$serv = new $service_name();
				if(method_exists($serv,'serve')) {
					return $serv->serve();
				} else {
					return array('status'=>false,'data'=>"The specified service did not respond.");
				}
			} else {
				return array('status'=>false,'data'=>"The specified service does not exist.");
			}
		}
	}
?>