<?php
require_once 'router/Router.php';
require_once 'auth/Acl.php';
require_once 'auth/TokenApiAuth.php';
require_once 'academy1/AlunosController.php';


class Academy1ApiApp {


	public static function resolve() {
		$app = 'academy1';

		$routes = [
			'/alunos' => [
				'GET'  => 'AlunosController.get',
				'POST' => 'AlunosController.post'
			],
			'/professores' => [
				'GET'  => 'ProfessoresController.get',
				'POST' => 'ProfessoresController.post'
			]
		];
		
		$acls = [
			'/alunos' => [
				'GET'  => true,
				'POST' => false
			]
		];

		$aclUser = [
			1 => ['/alunos']
		];

		$acl = new Acl($acls);

		try {
			Router::resolve($routes, $acl);
		} catch (Exception $e) {
			http_response_code($e->getCode());
			echo json_encode(['error' => $e->getMessage()];
		}


		// Application class
		// each module extends from this application class
		// Router class should be reusefull by the other modules
	}

}