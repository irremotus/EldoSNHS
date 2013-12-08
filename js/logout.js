function logout() {
	request("/services/?service=login&action=logout",null,logout_succeeded,logout_failed);
}
function logout_succeeded(ret) {
	location = "/";
}
function logout_failed(ret) {
	alert("Logout failed. Please close your browser to logout.");
}