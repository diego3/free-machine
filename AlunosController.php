<?php 


class AlunosController {


	public function get($params){
		echo __METHOD__.PHP_EOL;
		var_dump($params);
	}

	public function post($params){
		echo __METHOD__.PHP_EOL;
		var_dump($params);
	}

}


class InvalidAlunoException extends Exception { }