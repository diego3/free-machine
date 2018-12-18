<?php 
require_once 'Academy1ApiApp.php';


try {
	
	Academy1ApiApp::resolve();
} catch (Exception $e) {
	http_response_code(500);
	echo 'Application error '.$e->getMessage();
}
