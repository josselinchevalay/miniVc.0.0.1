<?php
/*define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));



require(ROOT.'core/model.php');
require(ROOT.'core/Controller.php');
require(ROOT.'core/DataStore.php');




$params = explode("/", $_GET["p"]);
$Controller = $params[0];
$Action = isset($params[1]) ? $params[1] : 'index';


require('Controllers/'.$Controller .'Controller.php');
$Controller = new $Controller();

if(method_exists($Controller, $Action.'Action')){
		unset($params[0]); unset($params[1]);
		$actionName = $Action.'Action';
		call_user_func_array(array($Controller, $actionName), $params );
}
else{
	echo "404 not found";
}
*/
require("router.php");

Router::init()->route(explode("/", $_GET["p"]));

?>