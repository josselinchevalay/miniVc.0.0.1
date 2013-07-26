<?php
    /***************************************/
    /*              PHP Class              */
    /***************************************/

    /**
    * Copyright (c) 2013 Chevalay josselin
* All rights reserved.
* Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
*
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
    * Cette classe est basée sur 
    * le design pattern Singleton
    * Cette classe retourne une instance
    * qui contient une méthode getDb()
    * qui retourne une connexion PDO 
    *
    */
   class DataStore {
        // constantes 
        const Db = "test";   // nom de la base de donnée
        const Serveur ="127.0.0.1"; // nom ou ip du serveur
        const login ="root";  // login admin
        const Password =""; // password admin
        const Pport ="3306";  // port du serveur default 3306
        const TypeDb = "Mysql"; // gestion du type de db gerer
        /**
        * @var Singleton
        * @access private
        * @static
        */
        private static $_instance = null;

        /**
        * @var instance Db
        * @access private
        * @static
        */
        private static $_instance_db = NULL;

        public function __construc(){
            
        }


        /**
        * Méthode qui crée l'unique instance de la classe
        * si elle n'existe pas encore puis la retourne.
        *
        * @param void
        * @return Singleton
        */
       public static function getInstance() {
 
         if(is_null(self::$_instance)) {
           self::$_instance = new DataStore();  
           try
            {
              
                switch(self::TypeDb){
                    case "MS" : {self::$_instance_db = new PDO('sqlsrv:Server='.self::Serveur.';Database='.self::Db, self::login, self::Password);} break;
                    case "Mysql": {self::$_instance_db = new PDO('mysql:host='.self::Serveur.';dbname='.self::Db, self::login, self::Password);}break;
                    default : {self::$_instance_db = new PDO('sqlsrv:Server='.self::Serveur.';Database='.self::Db, self::login, self::Password);}break;
                }
            }catch(Exception $e)
            {
                die("Erreur :".$e->getMessage());
            }
         }
 
         return self::$_instance;
       }

       public function getDb(){
           return self::$_instance_db;
       }

    }

?>

