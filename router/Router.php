<?php 


class Router {
	

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

		$executor = explode('.', $call);
		$controller = $executor[0];
		$method     = $executor[1];

		// TODO, try to make a require file here
		// TODO use autoload

		$controllerInstance = new $controller();

		$params = [];
		if($httpMethod == 'GET'){
			$params = $_GET;
		}
		else if($httpMethod == 'POST'){
			$params = $_POST;
		}else {
			throw new InvalidRouteException(' Request method ['.$httpMethod.'] not implemented yet', 500);
		}

		if( !method_exists($controllerInstance, $method)){
			throw new InvalidRouteException(' Controller method ['.$controller.'.'.$method.'] doesn\'t exist.', 404);
		}

		call_user_func_array([$controllerInstance, $method], [$params]);
	}
}


class RouteNotFoundException extends Exception {}

class InvalidRouteException extends Exception {}

class HttpMethodNotFoundException extends Exception {}