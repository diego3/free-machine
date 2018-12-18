<?php 
namespace FreeMachine\Auth;

class Acl {

	public $config = null;

	public function __construct($aclConfigs) {
		$this->config = $aclConfigs;
	}

	public function hasPermission($acl, $route, $user) {
		$config = $this->config;	
		if(empty($config)) {
			throw new NoAclConfigException("No acl config found", 404);
		}

		return false;
	}

	public function get($user, $route){
		$config = $this->config;	
		if(empty($config)) {
			throw new NoAclConfigException("No acl config found", 404);
		}

		// verify if this user have acl config , if yes then return it

		if(isset($config[$user])){
			$userAcls = $config[$user];
			if(isset($userAcls[$route])) {
				return $userAcls[$route];
			}
		}
		return null;	
	}
}


class NoAclConfigException extends \Exception {};