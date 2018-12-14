<?php 
require_once 'Router.php';
require_once 'AlunosController.php';

$routes = [
	'/alunos' => [
		'GET'  => 'AlunosController.get',
		'POST' => 'AlunosController.post'
	]
];


try {
	Router::resolve($routes);
} catch (Exception $e) {
	http_response_code($e->getCode());
	echo $e->getMessage();
}





