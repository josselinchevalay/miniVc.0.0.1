<?php
/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
*
*
* @author Josselin Chevalay
* @date 17/06/2013
* @version 0.0.1
* @class Router
* @comment : C'est une classe avec singleton pour instancier veuillez utiliser 
* la méthode statique init()
*/
class Router{
	/** Propriété statique permetant de connaître si nous avons instancier un router  **/
	private static $instance;

	/** propriété qui définis le home ou le controller et l'action à faire si la route est a la racine du site**/
	private $home = array("index", "list");

	/**                         **/
	private $firewall;
	
	/** 
	*
	* @function constructeur
	* @comment : dans un design pattern Singleton le constructeur est privé
	*/
	private function __construct(){
		session_start();
		Router::$instance = $this;
		define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
		define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
		//define('FOUINE', WEBROOT."/ressources/images/fouine.jpg");
		// mise en place de l'authentification
		//define('FIREWALL_NAME', 'myFirewall');
		

		require(ROOT.'core/model.php');
		require(ROOT.'core/Controller.php');
		require(ROOT.'core/DataStore.php');
		require(ROOT.'core/firewall.php');

		// mis en place d'une athentification
		//require(ROOT.'Controllers/myFirewall.php');

		//$name_firewall = FIREWALL_NAME;
		//$this->firewall = new $name_firewall($this);


	}


	/**
	*
	* @function init
	* @comment : c'est la fonction à appeler si vous voulez 
	* utiliser Router
	* @return Objet = Un router avec toutes ses méthodes
	*/
	public static function init(){
		if(is_null(Router::$instance))
		{
			$inst = new Router();
		}else{
			$inst = Router::instance;
		}
		return $inst;
	}


	/**
	*
	* @function route
	* @param $path  type array
	* @comment : son utlité et de recuperer toutes les 
	* url et de rediriger tout le flux vers les controleurs
	*
	*/
	public function route($path){
		if(count($path)<2) {$path = $this->home;}
		$Controller = $path[0];
		$Action = isset($path[1]) ? $path[1] : 'index';

		$controllerName = $Controller .'Controller';
		require_once('Controllers/'.$controllerName.'.php');
		$Controller = new $controllerName($this);

		if(method_exists($Controller, $Action.'Action')){				
				if(is_null($this->firewall))
				{
					unset($path[0]); unset($path[1]);
					$actionName = $Action.'Action';
					call_user_func_array(array($Controller, $actionName), $path );
				}
				else{
						$this->firewall->doFilter($path[0], $path[1], $path);
				}
				
		}
		else{
			echo "404 not found";
		}
	}

	/**
	*
	* @function redirect
	* @param Controller type string renseignez le nom de votre controller sans 'Controller' a la fin
	* @param Action  type string  renseignez le nom de votre ction sans 'Action' à la fin
	* @param params tye array (ou null) mettz y les valeur que vous voudriez envoyer à votre action
	*/
	public function redirect($Controller, $Action, $params=null){
		if($params == null){$params = array();}
		$Controller = $Controller;
		$Action = isset($Action) ? $Action : 'index';

		$controllerName = $Controller .'Controller';
		require_once('Controllers/'.$controllerName.'.php');
		$Controller = new $controllerName($this);

		
		if(method_exists($Controller, $Action.'Action')){				
				$actionName = $Action.'Action';
				call_user_func_array(array($Controller, $actionName), $params );
		}
		else{

			echo "404 not found";
		}
	}

	/**
	*
	* @function link
	* @param Controller type string renseignez le nom de votre controller sans 'Controller' a la fin
	* @param Action  type string  renseignez le nom de votre ction sans 'Action' à la fin
	* @param params tye array (ou null) mettz y les valeur que vous voudriez envoyer à votre action
	* @comment : permet de créer des urls respectant la notation attentu par miniVC
	*/
	public function link($Controller, $Action, $params=null){
		$url = WEBROOT.$Controller."/".$Action."/";
		if($params != null){
			foreach ($params as $key => $value) {
				$url .= $value."/";
			}
			$url = substr($url, 0, -1);
		}
		return $url;
	}

}
?>