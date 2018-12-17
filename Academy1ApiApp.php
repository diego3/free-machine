<?php
require_once 'router/Router.php';
require_once 'auth/Acl.php';
require_once 'auth/TokenApiAuth.php';
//require_once 'academy1/AlunosController.php';


class Academy1ApiApp {


	public static function resolve() {
		$app = 'academy1';

		$routes = [
			'/alunos' => [
				'GET'  => __DIR__.'/academy1/AlunosController->get',
				'POST' => __DIR__.'/academy1/AlunosController->post'
			],
			'/professores' => [
				'GET'  => __DIR__.'/academy1/ProfessoresController->get',
				'POST' => __DIR__.'/academy1/ProfessoresController->post'
			]
		];
		

		$modules = [
			1 => 'Interno',
			2 => 'Cadastros',
			3 => 'Pagamentos'
		];

		$aclModule = [
			//this id can be a user profile/module too
			1 => [
				'/alunos' => [
					'read'  => 'GET', 
					'write' => 'POST|PUT|DELETE'
				] 
			],
			2 => [
				'/alunos' => [
					'read'  => 'GET', 
					'write' => 'POST|PUT'
				] 
			]
		];

		// usuario 57748 ver dados cadastrais dos alunos, não pode alterar nem apagar
		$aclUser = [
			57748 => [
				'rules' => [
					'/alunos' => [
						'POST' => 'r'
					]
				]
			]
		];
		$acls = [
			'/alunos' => [
				'GET'  => true,
				'POST' => false
			]
		];

		$acl = new Acl($acls);

		try {
			Router::resolve($routes, $acl);
		} catch (Exception $e) {
			http_response_code($e->getCode());
			echo json_encode(['error' => $e->getMessage()]);
		}


		// Application class
		// each module extends from this application class
		// Router class should be reusefull by the other modules
	}

}