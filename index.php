<?php 
require_once 'Application.php';
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


// Application class
// each module extends from this application class
// Router class should be reusefull by the other modules





