<?php
/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
* Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
*
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
* @author Josselin chevalay
* @version 0.0.1
*
*/
class Controller{

	// Propriètées qui définit si nous utilisons ou non un layout
	var $layout = "default";
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