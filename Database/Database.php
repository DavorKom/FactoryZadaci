<?php  

class Database {

    public static function connect() {

        try {

            return new \PDO('mysql:host=localhost;port=3306;dbname=Trening', 'root', 'Vubo8421$');
            
        } catch (PDOException $e) {

            die($e);
        
        }

    }
    
}