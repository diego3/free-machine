<?php 


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
}


class NoAclConfigException extends Exception {};