<?php 


class Router {
	
	const METHOD_SEPARATOR = '->';

	public static function resolve(array $routes , $acl = null){
		$uri        = $_SERVER['REQUEST_URI']; 
		$httpMethod = $_SERVER['REQUEST_METHOD']; 
		
		$parsedUrl = parse_url($uri);
		$uriPath   = $parsedUrl['path'];

		$routesOnly = array_keys($routes);
		if( !in_array($uriPath, $routesOnly)) {
			throw new RouteNotFoundException('Route ['.$uriPath.'] not found.', 404);
		}

		$routeConfig = $routes[ $uriPath ];
		if( !isset($routeConfig[$httpMethod])){
			throw new HttpMethodNotFoundException('Http Method ['.$httpMethod.'] not found.', 404);
		}

		$call = $routeConfig[$httpMethod];

		$executor = explode(Router::METHOD_SEPARATOR, $call);
		$controllerFile = $executor[0];
		$method     	= $executor[1];
		$controllerClass = end(explode('/',$controllerFile));

		// TODO use autoload
		if( !file_exists($controllerFile.'.php')) {
			throw new ControllerNotFoundException(' Controller file ['.$controllerFile.'.php] not found.', 404);
		}
		
		include_once $controllerFile.'.php';

		$controllerInstance = new $controllerClass();

		$params = [];
		if($httpMethod == 'GET'){
			$params = $_GET;
		}
		else if($httpMethod == 'POST'){
			$params = $_POST;
			if( empty($params) ) { // empty when payload came raw/binary
				$params = file_get_contents( "php://input" );
        		$params = json_decode( $params, true );
			}
		}
		else if($httpMethod == 'PUT' || $httpMethod == 'DELETE'){
			$params = file_get_contents( "php://input" );
        	$params = json_decode( $params, true );
		}
		else {
			throw new InvalidRouteException(' Request method ['.$httpMethod.'] not implemented yet', 500);
		}

		if( !method_exists($controllerInstance, $method)){
			throw new ControllerMethodNotFoundException(' Controller method ['.$controllerClass.'.'.$method.'] doesn\'t exist.', 404);
		}

		call_user_func_array([$controllerInstance, $method], [$params]);
	}
}


class RouteNotFoundException extends Exception {}
class InvalidRouteException extends Exception {}
class HttpMethodNotFoundException extends Exception {}
class ControllerNotFoundException extends Exception {}
class ControllerMethodNotFoundException extends Exception {}