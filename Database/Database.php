<?php  

class Database {

    public static function connect() {

        try {

            return new \PDO('mysql:host=localhost;port=3306;dbname=test', 'root', 'Vubo8421$');
            
        } catch (PDOException $e) {

            die($e);
        
        }

    }
    
}