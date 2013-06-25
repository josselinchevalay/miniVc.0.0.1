<?php
/** Model      **/


/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
*	@class model
*   @comment : classe mère de tout les model
*  @version 0.0.1 
*/
class Model{
	public $table; // nom de la table
	public $id;   // id -> doit être reporter en réel sur la table en bdd
	public $db;   // dd type objet contient Le datasotre

	
	public function __construct(){
		$this->db = DataStore::getInstance()->getDb();
	}
	

	// méthode qui va permettre de recherche un objet par son id
	public function read($fields = null){		
		if($fields == null){ $fields="*";}
		$sql = "SELECT ".$fields." FROM ".$this->table." WHERE id=".$this->id;		
		$req = $this->db->query($sql);		
		foreach ($req as $key => $value) {
			foreach ($value as $rows => $f) {
				$this->$rows = $f;
			}			
		}
	}

	// méthode qui update ou persit la classe
	public function save($data){
		if(isset($data['id']) && !empty($data['id'])){
			$sql = "UPDATE ".$this->table." SET ";
			foreach ($data as $key => $value) {
				if($key != 'id'){$sql .= "$key='$value',";}	// a modifier 						
			}
			$sql = substr($sql, 0, -1);
			$sql .= " WHERE id =".$data['id'];
		}
		else
		{
			unset($data['id']); // au cas ou
			$sql = "INSERT INTO ".$this->table." (";
			foreach ($data as $key => $value) {
				$sql .= "$key ,";
			}
			$sql = substr($sql, 0, -1);
			$sql .= ") values (";
			foreach ($data as $key => $value) {
				if(is_string($value)){$sql .="'$value' ,";}else{$sql .="$value ,";}				
			}
			$sql = substr($sql, 0, -1);
			$sql .=")";			
		}
		echo $sql;
		$req= $this->db->query($sql);
		if(!isset($data['id'])){
			$this->id =mysql_insert_id();
		}else{
			$this->id = $data['id'];
		}
	}


	// fonction qui permet la recherche
	public function find($data =  array()){
		if(isset($data['conditions'])){$conditions = $data['conditions'];}else{$conditions= null;}
		if(isset($data['fields'])){$fields = $data['fields'];}else{$fields [] =  array("*");}
		if(isset($data["limit"])){$limit = " LIMIT ".$data['limit'];}else{$limit= "";}
		if(isset($data["order"])){$order = " ORDER BY ".$data['order'];}else{$order = null;}
		
		$sql = "SELECT ";
		foreach ($fields as $key => $value) {
			$sql .= "$value ,";
		}
		$sql = substr($sql, 0, -1);
		$sql .= " FROM ".$this->table;
		if(!is_null($conditions)){
			$sql .= " WHERE ".$conditions;	
			
		}		
		if(!is_null($order)){
			$sql .= $order;
		}
		if(!empty($limit)){
			$sql .= $limit;
		}
		
		$dataSet =  array();
		$req = $this->db->query($sql);
		foreach ($req as $o => $occurence) {
			$dataSet[] = $occurence;
		}		
		return $dataSet;
	}

	// méthode qui permet de supprimer
	public function delete($id = null){
		if($id == null){$id = $this->id;}
		$sql = "DELETE FROM ".$this->table." WHERE id =".$id;		
		$this->db->query($sql);
	}

	// fonction qui mount un modele
	static function load($name){
		require($name.".php");
		return new $name();

	}

}

/****************/
?>