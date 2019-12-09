<?php 

require 'Classes/Model.php';
require 'Classes/User.php';
require 'Classes/Post.php';
require 'Database/Database.php';
require 'Database/QueryBuilder.php';
require 'Observers/UserObserver.php';


$pdo = Database::connect();

$query = QueryBuilder::getInstance($pdo);

Model::setDBQueryBuilder($query);

$user = new User();

$userObserver = new UserObserver;

$user::observe($userObserver);

$user->fill([
    'firstName' => 'test',
    'lastName' => 'testovi',
    'id' => 'test1'
]);

$user->save();

die(var_dump($user::$observers));




