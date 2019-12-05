<?php 

require 'Classes/Model.php';
require 'Classes/User.php';
require 'Database/Database.php';
require 'Database/QueryBuilder.php';


$pdo = Database::connect();

$query = QueryBuilder::getInstance($pdo);

Model::setDBQueryBuilder($query);

$user = new User();

$user->firstName = "Davor";
$user->lastName = "Komljenovic";
$user->password = "Pass123";

$query->update('tests', [
    'firstName' => 'Ime',
    'lastName'=> 'Prezime'
], [
    'firstName' => 'ImeT',
    'lastName'=> 'PrezimeT'
]);

$user->databaseOriginal();

var_dump($user->update(['firstName' => 'Test', 'lastName' => 'TestPrezime']));



