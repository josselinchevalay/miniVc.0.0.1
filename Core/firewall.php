<?php

/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
*/
abstract class Firewall{

	protected $acl;


	public function __construct(){

	}

	public function doFilter($controller, $action, $params = null){
		if($params == null){$params = array();}
		// nous allons ici allons recupere les visibility du controller et de l'action
		$droit_action = isset($this->acl[$controller][$action]) ?  $this->acl[$controller][$action] : "private";
		// nous verifions les droit sur les actions
		// si une action est public on laisse passer
		// sinon on doit checker le user
		if($droit_action == "public"){
			$this->router->redirect($controller, $action, $params);
		}else{
			$this->check($controller, $action, $params);

		}

	}

	abstract public function check($controller, $action, $params);
	
}