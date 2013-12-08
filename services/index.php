<?php
	require_once '../config.php';
	
	if(isset($_GET['service']) ) {
		$requested_service = $_GET['service'];
		$services = new Services();
		$response = $services->serve($requested_service);
		Response::send($response['status'],$response['data']);
		return;
	}
	Response::send(false,"No service was specified.");
	return;
?>