<?php 


class AlunosController {


	public function get($params){
		echo __METHOD__.PHP_EOL;
		var_dump($params);
	}

	public function post($params){
		echo __METHOD__.PHP_EOL;
		var_dump($params);

		// todo , return a link to a new resource
	}

	public function exampleAcl($params) {

	}
}


class InvalidAlunoException extends Exception { }