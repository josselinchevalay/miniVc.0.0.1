<?php
/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
* @author Josselin chevalay
* @version 0.0.1
*
*/
class Controller{

	// Propriètées qui définit si nous utilisons ou non un layout
	var $layout = "";
	// tableau de variable qui vous être utlisé dans nos vues
	var $vars = array();


	/**
	* @function constructeur de notre 
	* @param $router type objet permet ainsi de de pouvoir utiliser le router dans la page
	*/
	function __construct($router){
		$this->router = $router;		
		$this->data['token'] = isset($_SESSION['token']) ? $_SESSION['token'] : $_SESSION['token'] = md5(time().uniqid());
		if(isset($this->options) & !empty($this->options)){
			if(isset($this->options['models']) & !empty($this->options['models'])){
				foreach ($this->options["models"] as $key => $value) {
					$this->loadModel($value);
				}
			}
		}

		if(isset($_POST) & !empty($_POST)){ // va mettre le post dans un tableau
			// grosse verification sur le post est le fait qu'il contienne un token valide
			if(isset($_POST['token']) && !empty($_POST['token']) && ($_SESSION['token'] == $_POST['token']))
			{
				$this->requestPost = $_POST;
			}else{
				throw new Exception("Token n'est pas valide", 1);
				
			}
		}
	}

	
	/**
	* @function ser
	* @param $data type array permet de setter les données que l'on veux 'monter' sur la vues
	*/
	function set($data){
			$this->vars = array_merge($this->vars, $data);
	}

	/**
	* @function render
	* @param $filename type string de rediriger sur la vues souhaiter
	*/
	function render($filename){
		extract($this->vars);
		ob_start();
		require(ROOT."vues/".get_class($this)."/".$filename.".php");
		$content_for_layout = ob_get_clean();
		if($this->layout == false){
			echo $content_for_layout;
		}else{
			require(ROOT.'vues/layout/'.$this->layout.".php");
		}

	}

	/**
	* @function loadModel
	* @param param type string 
	* @comment : va monter sur le controller des propriéte pour le model
	*
	*/
	function loadModel($name){
		require_once(ROOT.'Models/'.strtolower($name).'.php');
		$this->$name = new $name();
	}

}


?>