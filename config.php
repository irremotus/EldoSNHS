<?php
    session_start();
	
	function my_autoload($classname) {
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/services/classes/'.$classname.'.class.php')) {
			require_once $_SERVER['DOCUMENT_ROOT'].'/services/classes/'.$classname.'.class.php';
		} elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/services/services/'.$classname.'.php')) {
			require_once $_SERVER['DOCUMENT_ROOT'].'/services/services/'.$classname.'.php';
		}
	}
	spl_autoload_register('my_autoload');
?>