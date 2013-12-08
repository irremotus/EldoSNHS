<?php
class Response {
	public function send($status, $data) {
		$response = array("status" => $status, "data" => $data);
		echo json_encode($response);
		return true;
	}
}
?>