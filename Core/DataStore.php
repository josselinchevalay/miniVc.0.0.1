<?php
    /***************************************/
    /*              PHP Class              */
    /***************************************/

    /**
    * Copyright (c) 2013 Chevalay josselin
    * All rights reserved.
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
               self::$_instance_db = new PDO('mysql:host='.self::Serveur.';dbname='.self::Db, self::login, self::Password);
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

