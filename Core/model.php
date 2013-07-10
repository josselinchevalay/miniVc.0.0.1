<?php
/** Model      **/


/**
* Copyright (c) 2013 Chevalay josselin
* All rights reserved.
* Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
*
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*	
*  
*  @version 0.0.1 
*  @package MiniVc
*/
class Model{

	/**
	* 
	* <p>
	*	table
	* </p>
	*/
	public $table; // nom de la table


	/**
	* 
	* <p>
	*	id
	* </p>
	*/
	public $id;   // id -> doit être reporter en réel sur la table en bdd
	

	/**
	*
	* <p>
	*	db
	* </p>
	*/
	public $db;   // dd type objet contient Le datasotre

	
	/**
	* <p> constructeur </p>
	*/
	public function __construct(){       
		$this->db = DataStore::getInstance()->getDb();
	}
	

	/**
	*
	* <p> read</p>
	*/
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

	/**
	* <p> save /<p>
	*
	*/
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
		
		$req= $this->db->query($sql);
		if(!isset($data['id'])){
			$this->id =mysql_insert_id();
		}else{
			$this->id = $data['id'];
		}
	}


	/**
	* <p> find </p>
	*
	*/
	public function find($data =  array()){
		if(isset($data['conditions'])){$conditions = $data['conditions'];}else{$conditions= null;}
		if(isset($data['fields'])){$fields = $data['fields'];}else{$fields =  array("*");}
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

	/**
	* <p> delete </p>
	*
	*/
	public function delete($id = null){
		if($id == null){$id = $this->id;}
		$sql = "DELETE FROM ".$this->table." WHERE id =".$id;		
		$this->db->query($sql);
	}

	/**
	*
	* <p>load</p>
	*
	*/
	static function load($name){
		require($name.".php");
		return new $name();

	}

}

/****************/
?>