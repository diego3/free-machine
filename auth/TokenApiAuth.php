<?php 

class TokenApiAuth {

	public $apps = [
		'academy1'
	];

	//public $expire = 10;//seconds

	public function expired($app, $token) {
		$currentToken = md5($app.'-time-'.date('d'));
		return $currentToken != $token;
	}	

	public function take($app) {
		$token = md5($app.'-time-'.date('d'));
		return $token;
	}


}